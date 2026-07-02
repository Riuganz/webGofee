# Panduan Setup Midtrans Payment Gateway

## 1. Daftar Akun Midtrans

1. Buka https://dashboard.midtrans.com
2. Daftar akun (pilih **Sandbox** untuk development/testing)
3. Setelah verifikasi email, login ke dashboard
4. Pergi ke **Settings → Access Keys**
5. Copy **Server Key** dan **Client Key**

## 2. Konfigurasi Environment

Edit file `.env` di root project:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=false
```

- Untuk **production**, ubah `MIDTRANS_IS_PRODUCTION=true` dan gunakan production keys
- Untuk **sandbox/development**, gunakan sandbox keys (diawali `SB-Mid-`)

## 3. URL Webhook

Di dashboard Midtrans, set **Payment Notification URL** ke:

```
https://your-domain.com/payment/notification
```

Untuk local development, gunakan ngrok:
```bash
ngrok http 8000
```
 Kemudian set URL webhook ke `https://xxxx.ngrok.io/payment/notification`

## 4. Testing

### Metode Testing di Sandbox Midtrans:

| Metode | Nomor/Kode |
|--------|-----------|
| BCA Virtual Account | 1111 1111 1111 1111 |
| BNI Virtual Account | 1111 1111 1112 |
| Mandiri Virtual Account | 1111 1111 1113 |
| GoPay | Gunakan aplikasi Gojek sandbox |
| QRIS | Scan dengan aplikasi pembayaran apapun |

### Status Transaksi Testing:

- **Success**: Bayar sesuai nominal
- **Pending**: Jangan bayar (akan expired dalam 24 jam)
- **Failed**: Bayar dengan nominal salah

## 5. Alur Pembayaran

### Opsi 1: Bayar Langsung (Transfer)
1. Customer buat reservasi → pilih "Bayar Langsung"
2. Redirect ke halaman pembayaran
3. Klik "Bayar Sekarang" → pilih metode (VA/GoPay/QRIS/dll)
4. Bayar sesuai instruksi Midtrans
5. Webhook Midtrans update status → "Lunas" + "Diterima"
6. Customer datang ke resto

### Opsi 2: Bayar di Tempat
1. Customer buat reservasi → pilih "Bayar di Tempat"
2. Status: "Menunggu Konfirmasi"
3. Admin konfirmasi → "Diterima"
4. Customer datang → bayar ke kasir
5. Admin/Kasir ubah status → "Selesai"

## 6. File yang Ditambahkan

### Config
- `config/midtrans.php` — Konfigurasi Midtrans

### Migration
- `2025_06_30_000001_add_payment_columns_to_reservasi_table.php` — Tambah kolom `metode_bayar` dan `status_bayar`
- `2025_06_30_000002_create_pembayaran_table.php` — Tabel pembayaran
- `2025_06_30_000003_add_snap_token_to_pembayaran_table.php` — Tambah kolom `snap_token`

### Model
- `app/Models/Pembayaran.php` — Model pembayaran
- Update `app/Models/Reservasi.php` — Tambah relasi pembayaran

### Controller
- `app/Http/Controllers/Customer/PaymentController.php` — Handle pembayaran & webhook
- Update `app/Http/Controllers/Customer/ReservasiController.php` — Simpan metode bayar

### Routes
- `routes/web.php` — Tambah route payment + webhook

### Views
- `resources/views/customer/payment.blade.php` — Halaman pembayaran
- Update `resources/views/customer/reservasi_dinein.blade.php` — Tambah pilihan metode bayar
- Update `resources/views/customer/reservasi_pickup.blade.php` — Tambah pilihan metode bayar
- Update `resources/views/customer/riwayat.blade.php` — Tampilkan status pembayaran

## 7. Troubleshooting

### Snap.js tidak muncul
- Pastikan `MIDTRANS_CLIENT_KEY` sudah diisi di `.env`
- Cek console browser untuk error

### Webhook tidak terpanggil
- Pastikan URL webhook bisa diakses publik (gunakan ngrok untuk local)
- Cek log Laravel di `storage/logs/laravel.log`
- Pastikan route `/payment/notification` tidak memerlukan auth

### Transaksi tidak ditemukan
- Pastikan `order_id` di Midtrans sesuai dengan yang disimpan di database
- Cek tabel `pembayaran` untuk melihat data yang tersimpan

## 8. Catatan Penting

- **Snap Token** hanya berlaku 1x pakai, setelah itu perlu generate ulang
- **Webhook** adalah cara utama untuk update status, jangan hanya andalkan redirect dari Midtrans
- Untuk production, pastikan menggunakan HTTPS
- Simpan `Server Key` dan `Client Key` dengan aman, jangan commit ke git