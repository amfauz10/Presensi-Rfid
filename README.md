# Sistem Presensi RFID dengan Notifikasi WhatsApp

Aplikasi web presensi berbasis Laravel yang membaca kartu ID lewat RFID reader (plug and play) dan mengirim notifikasi otomatis ke WhatsApp setiap kali ada yang absen.

## Fitur

- Pencatatan presensi otomatis lewat scan kartu RFID
- Notifikasi WhatsApp real-time setiap kali presensi tercatat
- Riwayat dan rekap data kehadiran

## Cara Kerja

RFID reader terhubung ke komputer/laptop sebagai perangkat plug and play (terbaca layaknya keyboard). Saat kartu di-tap, ID kartu masuk ke form input aplikasi, lalu sistem mencatat kehadiran ke database dan mengirim notifikasi lewat WhatsApp Gateway API.

## Teknologi

- **Backend:** Laravel, PHP
- **Database:** MySQL
- **Hardware:** RFID reader (plug and play)
- **Notifikasi:** WhatsApp Gateway API

## Cara Menjalankan

```bash
git clone https://github.com/amfauz10/Presensi-Rfid.git
cd presensi-rfid
composer install
cp .env.example .env
php artisan key:generate
```

Atur koneksi database dan WhatsApp Gateway API di `.env`, lalu:

```bash
php artisan migrate
php artisan serve
```

## Catatan

Dikerjakan secara mandiri sebagai latihan integrasi antara aplikasi web, perangkat RFID, dan layanan pihak ketiga (WhatsApp Gateway).
