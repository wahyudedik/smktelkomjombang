# Instruksi Setup Cron & Supervisor — Production Server

> **Server**: `root@ServerSMK`
> **Path project**: `/var/www/telkom`
> **OS**: Ubuntu/Debian (atau VPS Linux umum)
> **Framework**: Laravel 12 (PHP ≥ 8.2)
> **Queue connection**: `database` (table `jobs`, queue name `default`)

Dokumen ini berisi instruksi lengkap untuk mengaktifkan **Laravel Scheduler (cron)** dan **Supervisor (queue worker)** di production server. Semua perintah dijalankan via SSH sebagai `root@ServerSMK`.

---

## Daftar Scheduled Task (Hasil Pembacaan `routes/console.php`)

Seluruh scheduler didefinisikan di [`routes/console.php`](../routes/console.php) (Laravel 12 — tidak ada `app/Console/Kernel.php`). Berikut daftar lengkapnya:

| # | Command / Task | Jadwal | Keterangan |
|---|----------------|--------|------------|
| 1 | `instagram:sync` | Setiap 5 menit | Sinkronisasi post Instagram (frekuensi default 5 menit, bisa diubah user) |
| 2 | `instagram:refresh-token` | Tanggal 1 setiap bulan, jam 02:00 | Refresh token Instagram (token long-lived expire 60 hari, refresh tiap 30 hari). `onOneServer()` |
| 3 | `sarpras:send-notifications --daily` | Setiap hari jam 08:00 | Notifikasi sarpras: barang rusak & sarana yang perlu update |
| 4 | `attendance:sync` | Setiap 5 menit | Sinkronisasi data absensi (ZKTeco iClock) |
| 5 | `attendance:mark-alpha` | Setiap hari jam 23:00 | Tandai alpha untuk user yang tidak punya record absensi (dan tidak ada izin approved) |
| 6 | `attendance:notify --summary` | Setiap hari jam 16:00 | Kirim rekap absensi harian (waktu default `16:00`, override via env `ATTENDANCE_NOTIFY_SUMMARY_TIME`) |
| 7 | `attendance:notify --excuse` | Setiap jam, antara 08:00–17:00 | Notifikasi izin/sakit yang masih pending |
| 8 | `attendance:cleanup` | Setiap hari jam 02:00 | Bersihkan attendance_logs > 90 hari & attendances > 180 hari |
| 9 | *Closure* `cleanup-old-jobs` | Setiap hari jam 03:00 | Hapus AsyncJob > 7 hari beserta file export-nya |
| 10 | *Closure* `iclock-command-queue-recovery` | Setiap 10 menit | Recovery command iClock yang stale/timeout/failed agar bisa diambil ulang device |

> **Catatan**: Semua task menggunakan `->withoutOverlapping()` dan (kecuali closure) `->runInBackground()`. Jadi cron cukup menjalankan `schedule:run` **1x per menit** — Laravel scheduler yang akan mengecek mana task yang waktunya tiba.

---

## Bagian A — Laravel Scheduler (Cron)

### A1. Edit crontab

```bash
crontab -e
```

### A2. Entry cron yang harus ditambahkan

Tambahkan **satu baris berikut** (standar Laravel):

```cron
* * * * * cd /var/www/telkom && php artisan schedule:run >> /dev/null 2>&1
```

**Alternatif** jika `php` tidak ada di PATH cron (umum terjadi di server production), gunakan full path PHP:

```cron
* * * * * cd /var/www/telkom && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

> **Cara menemukan path PHP**: jalankan `which php` atau `readlink -f $(which php)` sebagai root. Path umum: `/usr/bin/php`, `/usr/local/bin/php`, atau `/usr/bin/php8.2`.

Simpan & keluar (pada vim: `:wq`; pada nano: `Ctrl+X` lalu `Y` lalu `Enter`).

### A3. Task yang akan jalan otomatis

Setelah cron aktif, Laravel scheduler akan menjalankan seluruh task pada tabel di bagian awal dokumen ini. Ringkasan jadwal:

| Waktu | Task yang jalan |
|-------|-----------------|
| Setiap 1 menit | `schedule:run` (pengecekan scheduler — cron entry) |
| Setiap 5 menit | `instagram:sync`, `attendance:sync` |
| Setiap 10 menit | *closure* `iclock-command-queue-recovery` |
| Setiap jam (08:00–17:00) | `attendance:notify --excuse` |
| Jam 02:00 | `attendance:cleanup` |
| Jam 03:00 | *closure* `cleanup-old-jobs` |
| Jam 08:00 | `sarpras:send-notifications --daily` |
| Jam 16:00 | `attendance:notify --summary` |
| Jam 23:00 | `attendance:mark-alpha` |
| Tanggal 1, jam 02:00 | `instagram:refresh-token` |

### A4. Verifikasi scheduler

**1. List semua scheduled task (Laravel 11+):**

```bash
cd /var/www/telkom && php artisan schedule:list
```

Harus menampilkan seluruh task pada tabel di atas beserta waktu eksekusi berikutnya (`Next Run`).

**2. Jalankan scheduler manual (test):**

```bash
cd /var/www/telkom && php artisan schedule:run
```

Output harus menampilkan task yang dijalankan pada menit berjalan (misal `No scheduled commands are ready to run.` jika memang tidak ada yang jatuh tempo).

**3. Verifikasi cron daemon aktif:**

```bash
systemctl status cron
```

Jika status `active (running)` — cron berjalan. Jika belum:

```bash
systemctl enable --now cron
```

> **Catatan CentOS/RHEL**: service namanya `crond`, bukan `cron`. Gunakan `systemctl status crond`.

**4. Verifikasi cron entry terdaftar:**

```bash
crontab -l
```

Harus menampilkan baris `* * * * * cd /var/www/telkom && php artisan schedule:run ...`.

**5. Cek log eksekusi cron (opsional):**

```bash
grep CRON /var/log/syslog | tail -20
```

Atau di distro yang memisahkan log cron:

```bash
grep CRON /var/log/cron | tail -20
```

---

## Bagian B — Supervisor (Queue Worker)

Supervisor menjaga queue worker Laravel tetap berjalan (auto-restart jika mati). Dibutuhkan karena project memakai `QUEUE_CONNECTION=database` — job async (export, notification push, import, dll) diproses oleh worker, bukan oleh HTTP request.

### B1. Install Supervisor

**Debian/Ubuntu:**

```bash
apt update && apt install supervisor -y
systemctl enable --now supervisor
```

**CentOS/RHEL/AlmaLinux:**

```bash
yum install supervisor -y
systemctl enable --now supervisor
```

Verifikasi:

```bash
supervisorctl status
```

Jika tidak error (bisa berupa `supervisord is running` atau output kosong tapi tidak gagal), supervisor sudah siap.

### B2. Cek user web server

Worker harus dijalankan dengan user yang sama dengan web server (agar bisa menulis ke `storage/logs`):

```bash
ps aux | grep php-fpm | head -5
```

Atau untuk Apache:

```bash
ps aux | grep apache2 | head -5
```

- **nginx + php-fpm (default Ubuntu)** → user `www-data`
- **Apache (default Ubuntu/Debian)** → user `www-data`
- **CentOS/RHEL default** → user `nginx` atau `apache`

> Gunakan hasilnya untuk parameter `user=` di langkah B3.

### B3. Buat config file supervisor

Buat file `/etc/supervisor/conf.d/telkom-worker.conf`:

```bash
cat > /etc/supervisor/conf.d/telkom-worker.conf << 'EOF'
[program:telkom-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/telkom/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/telkom/storage/logs/worker.log
stopwaitsecs=3600
EOF
```

**Penjelasan parameter:**

| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| `process_name` | `%(program_name)s_%(process_num)02d` | Nama process: `telkom-worker_00`, `telkom-worker_01` |
| `command` | `queue:work --sleep=3 --tries=3 --max-time=3600` | Worker Laravel; sleep 3 detik saat queue kosong; retry 3x sebelum failed; restart worker tiap 1 jam (ambil kode terbaru & hindari memory leak) |
| `numprocs` | `2` | Jumlah worker paralel (sesuaikan dengan beban server; 2–4 umum untuk sekolah) |
| `user` | `www-data` | **Sesuaikan** dengan hasil langkah B2 |
| `stopwaitsecs` | `3600` | Beri waktu worker menyelesaikan job saat stop (maksimal 1 jam) |
| `stdout_logfile` | `/var/www/telkom/storage/logs/worker.log` | Log output worker |

> **Catatan queue name**: `config/queue.php` memakai queue name default `default` (via env `DB_QUEUE`, default `default`), jadi perintah `queue:work` tanpa flag `--queue` sudah benar — akan memproses queue `default`. Jika di `.env` production ada `DB_QUEUE=nama-queue-lain`, tambahkan `--queue=nama-queue-lain` ke baris `command`.

**Cek custom queue name di server (opsional):**

```bash
grep -E '^DB_QUEUE|^QUEUE' /var/www/telkom/.env
```

Jika `DB_QUEUE` tidak di-set atau kosong → queue name `default` (tidak perlu ubah apa pun).

### B4. Reload supervisor & jalankan worker

```bash
supervisorctl reread
supervisorctl update
```

`reread` mendeteksi config baru; `update` membuat/restart program berdasarkan config terdeteksi.

### B5. Status & kontrol worker

**Status:**

```bash
supervisorctl status
```

Output yang diharapkan:

```
telkom-worker:telkom-worker_00   RUNNING   pid 12345, uptime 0:00:05
telkom-worker:telkom-worker_01   RUNNING   pid 12346, uptime 0:00:05
```

**Control (start/stop/restart):**

```bash
supervisorctl start telkom-worker:*
supervisorctl stop telkom-worker:*
supervisorctl restart telkom-worker:*
```

Untuk satu process saja:

```bash
supervisorctl restart telkom-worker:telkom-worker_00
```

**Reload config setelah edit file `.conf`:**

```bash
supervisorctl reread && supervisorctl update
```

**Lihat log worker secara real-time:**

```bash
tail -f /var/www/telkom/storage/logs/worker.log
```

### B6. Pastikan `.env` production punya queue connection database

```bash
grep '^QUEUE_CONNECTION' /var/www/telkom/.env
```

Harus menampilkan:

```
QUEUE_CONNECTION=database
```

Jika belum ada atau nilainya `sync`, ubah:

```bash
sed -i 's/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=database/' /var/www/telkom/.env
```

Lalu pastikan tabel queue sudah ada di database (migration `jobs` & `failed_jobs`):

```bash
cd /var/www/telkom && php artisan migrate --force
```

Clear config cache setelah ubah `.env`:

```bash
cd /var/www/telkom && php artisan config:clear
```

---

## Bagian C — Verifikasi Akhir

Jalankan checklist berikut setelah cron & supervisor terpasang:

**1. Scheduler — semua task terjadwal:**

```bash
cd /var/www/telkom && php artisan schedule:list
```

Pastikan 10 task dari tabel di bagian awal dokumen muncul semua, beserta `Next Run` yang valid.

**2. Supervisor — worker running:**

```bash
supervisorctl status
```

Kedua process `telkom-worker_00` & `telkom-worker_01` harus `RUNNING`.

**3. Queue — tidak ada job tertinggal / failed:**

```bash
cd /var/www/telkom && php artisan queue:monitor
```

Atau cek langsung:

```bash
cd /var/www/telkom && php artisan queue:failed
```

**4. Test kirim notifikasi (contoh test queue):**

```bash
cd /var/www/telkom && php artisan tinker
```

Di tinker, kirim satu job/notification test, lalu pantau:

```
>>> \Illuminate\Support\Facades\Bus::dispatch(new \App\Jobs\SomeTestJob());
```

Atau lebih sederhana — cek worker menerima job dengan mengirim notifikasi dari UI admin, lalu lihat log:

```bash
tail -f /var/www/telkom/storage/logs/worker.log
```

**5. Cek log Laravel:**

```bash
tail -f /var/www/telkom/storage/logs/laravel.log
```

**6. Test cron scheduler jalan:**

```bash
cd /var/www/telkom && php artisan schedule:run
```

Lalu tunggu 5–10 menit dan cek `schedule:list` — `Last Run` harus ter-update untuk task yang jatuh tempo.

---

## Bagian D — Troubleshooting Umum

### D1. Permission ditolak (`Permission denied` / `failed to open stream`)

Pastikan `storage` & `bootstrap/cache` dimiliki oleh user web server:

```bash
chown -R www-data:www-data /var/www/telkom/storage /var/www/telkom/bootstrap/cache
chmod -R 775 /var/www/telkom/storage /var/www/telkom/bootstrap/cache
```

> Ganti `www-data` dengan user web server sesuai langkah B2 (misal `nginx`, `apache`).

Untuk worker log spesifik:

```bash
touch /var/www/telkom/storage/logs/worker.log
chown www-data:www-data /var/www/telkom/storage/logs/worker.log
```

### D2. Cron tidak jalan / scheduler tidak ada yang dieksekusi

- Pastikan path project benar: `cd /var/www/telkom` harus benar-benar ada.
- Pastikan path PHP benar (cek `which php`, bandingkan dengan entry cron). Jika ragu, pakai full path: `/usr/bin/php artisan schedule:run`.
- Pastikan cron daemon aktif: `systemctl status cron` (atau `crond` di CentOS).
- Pastikan `.env` production sudah di-set & `php artisan config:clear` sudah dijalankan (agar jadwal yang membaca config — misal `attendance:notify --summary` jam berapa — terbaca benar).
- Test manual: `cd /var/www/telkom && php artisan schedule:run` — jika manual jalan tapi cron tidak, masalahnya di PATH/permission cron, bukan di Laravel.
- Cek timezone server: `timedatectl`. Pastikan `Asia/Jakarta` (sesuai `APP_TIMEZONE` project). Jika server UTC, jam `dailyAt('23:00')` akan tereksekusi 06:00 WIB.
- Cron tidak punya environment shell yang sama dengan login — karena entry sudah `cd /var/www/telkom` dan memakai path PHP lengkap, biasanya sudah cukup.

### D3. Worker mati / tidak `RUNNING`

- Cek log worker: `tail -100 /var/www/telkom/storage/logs/worker.log`.
- Cek output supervisor: `supervisorctl status` — status `BACKOFF`/`FATAL` berarti worker crash berulang. Cek `worker.log` untuk error-nya (biasanya `.env` belum di-set, DB tidak bisa diakses, atau permission).
- Test worker manual di foreground (verbose):

```bash
cd /var/www/telkom && php artisan queue:work --sleep=3 --tries=3 --verbose
```

Jika manual jalan tapi supervisor tidak — cek `user=` di config supervisor sudah sesuai user web server, dan permission `storage` sudah benar (lihat D1).

- Restart worker: `supervisorctl restart telkom-worker:*`.
- Jika config supervisor berubah: `supervisorctl reread && supervisorctl update`.

### D4. Queue stuck / job tertinggal / job failed

- Lihat job yang gagal:

```bash
cd /var/www/telkom && php artisan queue:failed
```

- Retry semua job yang gagal:

```bash
cd /var/www/telkom && php artisan queue:retry all
```

Atau retry satu kelas tertentu:

```bash
cd /var/www/telkom && php artisan queue:retry "App\Jobs\ExportReport"
```

- Lihat jumlah job di queue (tabel `jobs` di database):

```bash
cd /var/www/telkom && php artisan tinker
```

```
>>> \App\Models\Job::count(); // atau DB::table('jobs')->count()
```

- Pastikan `QUEUE_CONNECTION=database` di `.env` — jika `sync`, job diproses langsung di HTTP request (bukan lewat worker) dan `queue:work` tidak akan memproses apa pun.
- Pastikan tabel `jobs` & `failed_jobs` ada: `php artisan migrate --status | grep -E 'jobs|failed'`.
- `retry_after` di `config/queue.php` default 90 detik. Pastikan `--max-time` worker (3600) dan `--tries` (3) sesuai kebutuhan job berat (export Excel/PDF bisa > 90 detik — jika job berat sering `failed` karena timeout, pertimbangkan menaikkan `DB_QUEUE_RETRY_AFTER` di `.env`, misal `DB_QUEUE_RETRY_AFTER=600`).

### D5. Scheduler & Worker berjalan tapi fitur tidak bekerja

- Instagram sync: cek `php artisan instagram:sync` manual — lihat output error (token expired, koneksi ke Graph API, dsb).
- Attendance sync/notify: cek config absensi di `config/attendance.php` & env `ATTENDANCE_*`.
- iClock command recovery: lihat log Laravel — setiap kali ada command yang di-recover, ada entri `iClock command queue recovery` di `storage/logs/laravel.log`.

---

## Ringkasan Cepat (Copy-Paste Checklist)

```bash
# ===== CRON =====
crontab -e
# Tambahkan:
* * * * * cd /var/www/telkom && php artisan schedule:run >> /dev/null 2>&1

# Verifikasi cron
crontab -l
systemctl status cron

# ===== SUPERVISOR =====
apt update && apt install supervisor -y
systemctl enable --now supervisor

cat > /etc/supervisor/conf.d/telkom-worker.conf << 'EOF'
[program:telkom-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/telkom/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/telkom/storage/logs/worker.log
stopwaitsecs=3600
EOF

supervisorctl reread && supervisorctl update
supervisorctl status

# ===== VERIFIKASI =====
cd /var/www/telkom && php artisan schedule:list
supervisorctl status
tail -f /var/www/telkom/storage/logs/worker.log
tail -f /var/www/telkom/storage/logs/laravel.log
```
