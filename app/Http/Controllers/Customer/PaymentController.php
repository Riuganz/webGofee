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
        // Pastikan reservasi milik user yang login[cite: 3]
        if ($reservasi->id_user !== Auth::id()) {
            abort(403);
        }

        // Load detail pesanan[cite: 3]
        $reservasi->load('detailPesanans.menu', 'meja', 'user');

        // Pastikan metode bayar transfer dan belum lunas[cite: 3]
        if ($reservasi->metode_bayar !== 'transfer' || $reservasi->status_bayar === 'lunas') {
            return redirect()->route('customer.riwayat')
                ->with('info', 'Pesanan ini tidak perlu dibayar atau sudah lunas.');
        }

        // Cek apakah sudah ada transaksi yang pending[cite: 3]
        $existingPayment = $reservasi->pembayarans()
            ->where('status', 'pending')
            ->latest()
            ->first();

        $snapToken = null;
        if ($existingPayment && $existingPayment->snap_token) {
            // Gunakan Snap token yang sudah ada[cite: 3]
            $snapToken = $existingPayment->snap_token;
        }

        return view('customer.payment', compact('reservasi', 'snapToken'));
    }

    /**
     * Proses pembayaran: buat transaksi Midtrans Snap.
     */
    public function process(Reservasi $reservasi)
    {
        // Pastikan reservasi milik user yang login[cite: 3]
        if ($reservasi->id_user !== Auth::id()) {
            abort(403);
        }

        // Pastikan metode bayar transfer dan belum lunas[cite: 3]
        if ($reservasi->metode_bayar !== 'transfer' || $reservasi->status_bayar === 'lunas') {
            return response()->json(['error' => 'Pesanan tidak perlu dibayar atau sudah lunas.'], 400);
        }

        // Cek apakah sudah ada transaksi pending[cite: 3]
        $existingPayment = $reservasi->pembayarans()
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Load detail pesanan beserta menu
        $reservasi->load('detailPesanans.menu', 'user');

        if ($existingPayment && $existingPayment->snap_token) {
            return response()->json(['snap_token' => $existingPayment->snap_token]);
        }

        try {
            // Set konfigurasi Midtrans[cite: 3]
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            // Fix SSL certificate issue untuk development[cite: 3]
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTPHEADER => [],
            ];

            // Buat order ID unik[cite: 3]
            $orderId = 'INV-' . $reservasi->id_reservasi . '-' . time();

            // Siapkan item details dengan proteksi "Undefined array key"
            $itemDetails = [];
            $calculatedTotal = 0;

            foreach ($reservasi->detailPesanans as $detail) {
                // Pastikan objek menu ada sebelum mengakses propertinya
                if ($detail && $detail->menu) {
                    $hargaMenu = (int) ($detail->menu->harga ?? 0); // Proteksi key jika null
                    $qty = (int) ($detail->jumlah_beli ?? 1);
                    $idMenu = $detail->menu->id_menu ?? rand(1, 999);
                    $namaMenu = $detail->menu->nama_menu ?? 'Menu';

                    $calculatedTotal += ($hargaMenu * $qty);

                    $itemDetails[] = [
                        'id' => 'MENU-' . $idMenu,
                        'price' => $hargaMenu,
                        'quantity' => $qty,
                        'name' => substr($namaMenu, 0, 50), // Batas maksimal 50 karakter Midtrans
                    ];
                }
            }

            // Validasi selisih total_harga database vs kalkulasi keranjang
            $dbTotal = (int) ($reservasi->total_harga ?? 0);
            
            if ($dbTotal > $calculatedTotal) {
                // Jika total di DB lebih besar, selisihnya dianggap biaya tambahan / layanan
                $itemDetails[] = [
                    'id' => 'FEE-LAYANAN',
                    'price' => (int) ($dbTotal - $calculatedTotal),
                    'quantity' => 1,
                    'name' => 'Biaya Tambahan / Layanan',
                ];
            } elseif ($dbTotal < $calculatedTotal) {
                // Jika total di DB lebih kecil, selisihnya adalah potongan / diskon
                $itemDetails[] = [
                    'id' => 'DISCOUNT',
                    'price' => (int) ($dbTotal - $calculatedTotal),
                    'quantity' => 1,
                    'name' => 'Potongan Harga / Diskon',
                ];
            }

            // Dapatkan base URL aplikasi
            $baseUrl = url('/');

            // Siapkan parameter transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $dbTotal, // Pastikan gross_amount berupa angka integer murni
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $reservasi->user->name ?? 'Customer',
                    'email' => $reservasi->user->email ?? 'customer@example.com',
                ],
                'enabled_payments' => [
                    'bank_transfer', 'gopay', 'shopeepay', 'qris', 'other_va'
                ],
                'callbacks' => [
                    'finish' => $baseUrl . '/customer/payment/finish?order_id=' . $orderId,
                    'unfinish' => $baseUrl . '/customer/payment/unfinish?order_id=' . $orderId,
                    'error' => $baseUrl . '/customer/payment/error?order_id=' . $orderId,
                ],
            ];

            // Dapatkan Snap token[cite: 3]
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan data pembayaran dengan snap_token[cite: 3]
            Pembayaran::create([
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
     * Langsung update status karena webhook Midtrans tidak bisa menjangkau localhost.
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id; //[cite: 3]
        $reservasiId = null;

        if ($orderId) {
            // Extract reservasi ID dari order_id: INV-{id}-{timestamp}[cite: 3]
            $parts = explode('-', $orderId);
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                $reservasiId = $parts[1];
            }
        }

        if ($reservasiId) {
            $reservasi = Reservasi::find($reservasiId); //[cite: 3]
            if ($reservasi) {
                // Update status pembayaran menjadi lunas
                $pembayaran = $reservasi->pembayarans()
                    ->where('order_id', $orderId)
                    ->first();

                if ($pembayaran && $pembayaran->status !== 'settlement') {
                    $pembayaran->status = 'settlement';
                    $pembayaran->waktu_dibayar = now();
                    $pembayaran->save();

                    $reservasi->status_bayar = 'lunas';
                    $reservasi->status_reservasi = 'Diterima';
                    $reservasi->save();
                }

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
            ->with('info', 'Pembayaran masih menunggu. Silakan selesaikan pembayaran Anda.'); //[cite: 3]
    }

    /**
     * Halaman jika pembayaran error.
     */
    public function error(Request $request)
    {
        return redirect()->route('customer.riwayat')
            ->with('error', 'Pembayaran gagal. Silakan coba lagi.'); //[cite: 3]
    }

    /**
     * Webhook notification handler dari Midtrans.
     * Endpoint ini dipanggil Midtrans untuk memberi notifikasi status transaksi.
     */
    public function notification(Request $request)
    {
        Log::info('Midtrans Notification received:', $request->all()); //[cite: 3]

        try {
            // Set konfigurasi Midtrans[cite: 3]
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Fix SSL certificate issue untuk development
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTPHEADER => [], // <-- Tambahkan baris ini
            ];

            // Verifikasi notifikasi[cite: 3]
            $notif = new \Midtrans\Notification();

            $transactionStatus = $notif->transaction_status;
            $paymentType = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;
            $transactionId = $notif->transaction_id;

            Log::info("Midtrans Notification - Order: {$orderId}, Status: {$transactionStatus}, Type: {$paymentType}"); //[cite: 3]

            // Cari pembayaran berdasarkan order_id[cite: 3]
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                Log::warning("Pembayaran not found for order_id: {$orderId}");
                return response()->json(['status' => 'ok']);
            }

            $reservasi = $pembayaran->reservasi; //[cite: 3]

            // Update data pembayaran[cite: 3]
            $pembayaran->transaction_id = $transactionId;
            $pembayaran->payment_type = $paymentType;

            // Ambil detail VA / QRIS[cite: 3]
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

            // Handle status transaksi[cite: 3]
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

            // Update pembayaran[cite: 3]
            $pembayaran->status = $statusPembayaran;
            $pembayaran->save();

            // Update reservasi[cite: 3]
            $reservasi->status_bayar = $statusBayar;
            $reservasi->status_reservasi = $statusReservasi;
            $reservasi->save();

            Log::info("Payment updated - Order: {$orderId}, Status: {$statusPembayaran}, Bayar: {$statusBayar}"); //[cite: 3]

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}