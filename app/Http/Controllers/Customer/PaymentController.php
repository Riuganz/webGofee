<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman pembayaran dengan Snap token.
     */
    public function show(Reservasi $reservasi)
    {
        // Pastikan reservasi milik user yang login
        if ($reservasi->id_user !== Auth::id()) {
            abort(403);
        }

        // Load detail pesanan
        $reservasi->load('detailPesanans.menu', 'meja', 'user');

        // Pastikan metode bayar transfer dan belum lunas
        if ($reservasi->metode_bayar !== 'transfer' || $reservasi->status_bayar === 'lunas') {
            return redirect()->route('customer.riwayat')
                ->with('info', 'Pesanan ini tidak perlu dibayar atau sudah lunas.');
        }

        // Cek apakah sudah ada transaksi yang pending
        $existingPayment = $reservasi->pembayarans()
            ->where('status', 'pending')
            ->latest()
            ->first();

        $snapToken = null;
        if ($existingPayment && $existingPayment->snap_token) {
            // Gunakan Snap token yang sudah ada
            $snapToken = $existingPayment->snap_token;
        }

        return view('customer.payment', compact('reservasi', 'snapToken'));
    }

    /**
     * Proses pembayaran: buat transaksi Midtrans Snap.
     */
    public function process(Reservasi $reservasi)
    {
        // Pastikan reservasi milik user yang login
        if ($reservasi->id_user !== Auth::id()) {
            abort(403);
        }

        // Pastikan metode bayar transfer dan belum lunas
        if ($reservasi->metode_bayar !== 'transfer' || $reservasi->status_bayar === 'lunas') {
            return response()->json(['error' => 'Pesanan tidak perlu dibayar atau sudah lunas.'], 400);
        }

        // Cek apakah sudah ada transaksi pending
        $existingPayment = $reservasi->pembayarans()
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Load detail pesanan
        $reservasi->load('detailPesanans.menu', 'user');

        if ($existingPayment && $existingPayment->snap_token) {
            return response()->json(['snap_token' => $existingPayment->snap_token]);
        }

        try {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            // Fix SSL certificate issue untuk development
            // Catatan: Hapus baris ini untuk production dan pastikan CA bundle terinstall
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];

            // Buat order ID unik
            $orderId = 'INV-' . $reservasi->id_reservasi . '-' . time();

            // Siapkan item details
            $itemDetails = [];
            foreach ($reservasi->detailPesanans as $detail) {
                $itemDetails[] = [
                    'id' => 'MENU-' . $detail->menu->id_menu,
                    'price' => (int) $detail->menu->harga,
                    'quantity' => $detail->jumlah_beli,
                    'name' => $detail->menu->nama_menu,
                ];
            }

            // Siapkan parameter transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $reservasi->total_harga,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $reservasi->user->name,
                    'email' => $reservasi->user->email,
                ],
                'enabled_payments' => [
                    'bank_transfer',
                    'gopay',
                    'shopeepay',
                    'qris',
                    'other_va',
                    'bca_klikbca',
                    'bca_klikpay',
                    'bri_epay',
                    'echannel',
                    'permata_va',
                    'cimb_clicks',
                    'danamon_online',
                ],
                'vtweb' => [],
            ];

            // Dapatkan Snap token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan data pembayaran dengan snap_token
            $pembayaran = Pembayaran::create([
                'id_reservasi' => $reservasi->id_reservasi,
                'order_id' => $orderId,
                'snap_token' => $snapToken,
                'status' => 'pending',
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Halaman setelah pembayaran selesai (redirect dari Midtrans).
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $reservasiId = null;

        if ($orderId) {
            // Extract reservasi ID dari order_id: INV-{id}-{timestamp}
            $parts = explode('-', $orderId);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $reservasiId = $parts[1];
            }
        }

        if ($reservasiId) {
            $reservasi = Reservasi::find($reservasiId);
            if ($reservasi) {
                return redirect()->route('customer.riwayat')
                    ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
            }
        }

        return redirect()->route('customer.riwayat')
            ->with('success', 'Pembayaran berhasil! Silakan cek status pesanan Anda.');
    }

    /**
     * Halaman jika pembayaran pending.
     */
    public function unfinish(Request $request)
    {
        return redirect()->route('customer.riwayat')
            ->with('info', 'Pembayaran masih menunggu. Silakan selesaikan pembayaran Anda.');
    }

    /**
     * Halaman jika pembayaran error.
     */
    public function error(Request $request)
    {
        return redirect()->route('customer.riwayat')
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
    }

    /**
     * Webhook notification handler dari Midtrans.
     * Endpoint ini dipanggil Midtrans untuk memberi notifikasi status transaksi.
     */
    public function notification(Request $request)
    {
        Log::info('Midtrans Notification received:', $request->all());

        try {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Fix SSL certificate issue untuk development
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ];

            // Verifikasi notifikasi
            $notif = new \Midtrans\Notification();

            $transactionStatus = $notif->transaction_status;
            $paymentType = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
            $transactionId = $notif->transaction_id;

            Log::info("Midtrans Notification - Order: {$orderId}, Status: {$transactionStatus}, Type: {$paymentType}");

            // Cari pembayaran berdasarkan order_id
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                Log::warning("Pembayaran not found for order_id: {$orderId}");
                return response()->json(['status' => 'ok']);
            }

            $reservasi = $pembayaran->reservasi;

            // Update data pembayaran
            $pembayaran->transaction_id = $transactionId;
            $pembayaran->payment_type = $paymentType;

            // Ambil detail VA / QRIS
            if ($paymentType === 'bank_transfer') {
                $vaNumbers = $notif->va_numbers ?? [];
                $pembayaran->va_numbers = $vaNumbers;
                if (!empty($vaNumbers)) {
                    $pembayaran->metode_pembayaran = $vaNumbers[0]['bank'] ?? null;
                }
            } elseif ($paymentType === 'gopay') {
                $pembayaran->metode_pembayaran = 'gopay';
            } elseif ($paymentType === 'qris') {
                $pembayaran->metode_pembayaran = 'qris';
                $pembayaran->qr_code_url = $notif->actions?->first()?->url ?? null;
            } elseif ($paymentType === 'shopeepay') {
                $pembayaran->metode_pembayaran = 'shopeepay';
            }

            // Handle status transaksi
            $statusPembayaran = 'pending';
            $statusBayar = 'belum_bayar';
            $statusReservasi = $reservasi->status_reservasi;

            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus === 'accept') {
                        $statusPembayaran = 'settlement';
                        $statusBayar = 'lunas';
                        $statusReservasi = 'Diterima';
                        $pembayaran->waktu_dibayar = now();
                    }
                    break;

                case 'settlement':
                    $statusPembayaran = 'settlement';
                    $statusBayar = 'lunas';
                    $statusReservasi = 'Diterima';
                    $pembayaran->waktu_dibayar = now();
                    break;

                case 'pending':
                    $statusPembayaran = 'pending';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $statusPembayaran = $transactionStatus;
                    $statusBayar = 'gagal';
                    $statusReservasi = 'Dibatalkan';
                    break;
            }

            // Update pembayaran
            $pembayaran->status = $statusPembayaran;
            $pembayaran->save();

            // Update reservasi
            $reservasi->status_bayar = $statusBayar;
            $reservasi->status_reservasi = $statusReservasi;
            $reservasi->save();

            Log::info("Payment updated - Order: {$orderId}, Status: {$statusPembayaran}, Bayar: {$statusBayar}");

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}