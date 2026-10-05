# Panduan Setup Site MAUDU di aaPanel + Diagnosa Error MySQL Connection Refused

> **Server**: aaPanel Linux (IPCloudHost, IP `103.176.78.216`)
> **Panel**: aaPanel — login `root`
> **Path site**: `/www/wwwroot/maudu-rejoso.sch.id`
> **PHP (binary CLI)**: `/www/server/php/83/bin/php` (PHP 8.3 via aaPanel)
> **User web / worker**: `www` (user default web server aaPanel)
> **Domain**: `maudu-rejoso.sch.id`
> **Framework**: Laravel 12 — multi-theme (default tema: `maudu`)
> **Driver project**: `CACHE_STORE=database`, `QUEUE_CONNECTION=database`, `SESSION_DRIVER=database`

Dokumen ini khusus untuk **deploy site theme MAUDU di server BARU aaPanel** (berbeda dari ServerSMK). Semua perintah dijalankan via **SSH sebagai root**. Panduan ini **menggantikan** instruksi umum untuk server ini.

---

## Error yang sedang dihadapi

```
SQLSTATE[HY000] [2002] Connection refused (Connection: mysql,
SQL: select * from `cache` where `key` in (maudu-rejoso-cache-illuminate:queue:restart))
```

### Arti error ini

- `[2002]` = **gagal di level koneksi** (socket/TCP), **bukan** salah password / salah nama database (itu `[1045]` atau `[1049]`).
- `Connection refused` = tidak ada proses MySQL yang menerima koneksi di host/port yang diminta — MySQL **mati**, atau PHP mencoba connect ke **alamat/port yang salah** (mis. unix socket yang tidak ada).
- Pola query `cache` + key `illuminate:queue:restart` = **queue worker** (`queue:work`) sedang polling — ini pola khas worker yang gagal cek flag restart karena MySQL tidak terjangkau.

### Mengapa SEMUA operasi ikut gagal

Project ini memakai **tiga komponen inti berbasis database** sekaligus (lihat [`config/cache.php`](../config/cache.php), [`config/queue.php`](../config/queue.php), [`config/session.php`](../config/session.php)):

| Komponen | Driver | Konsekuensi jika MySQL mati |
|----------|--------|------------------------------|
| Cache | `database` (tabel `cache`) | Worker gagal baca flag `illuminate:queue:restart`; aplikasi gagal cache |
| Queue | `database` (tabel `jobs`) | Job async tidak bisa diproses/ditulis |
| Session | `database` (tabel `sessions`) | Login user tidak bisa divalidasi |

Jadi begitu MySQL tidak terjangkau: **queue worker error terus** (log berulang 4x), **website kemungkinan ikut error 500** (session/cache gagal), dan **cron `schedule:run` juga gagal**. **MySQL harus beres dulu** sebelum setup supervisor/cron apa pun.

---

## Bagian 1 — Diagnosa & Fix Error `SQLSTATE[HY000] [2002] Connection refused`

Jalankan langkah berikut **berurutan** via SSH. Jangan lompat — berhenti di langkah yang menyelesaikan error, lalu lanjut ke Bagian 3.

### Penyebab #1 — MySQL belum jalan / crash (paling umum di server baru)

```bash
ps aux | grep -i mysql | grep -v grep
service mysql status        # atau: systemctl status mysqld
ss -tlnp | grep -E '3306|mysql'
```

**Interpretasi:**

- `ps` kosong + `service mysql status` → `inactive/dead` / `is not running` → **MySQL memang mati**.
- `ss -tlnp` tidak menampilkan port `3306` (atau port custom aaPanel) → tidak ada proses yang listening → koneksi pasti `refused`.

**Fix:**

```bash
service mysql start
service mysql status
ss -tlnp | grep -E '3306|mysql'
```

Alternatif via aaPanel: menu **Databases** → tab **MySQL** → cek status, bisa Start/Stop langsung dari panel. Jika MySQL crash terus-menerus, cek error log:

```bash
tail -50 /www/server/data/*.err
```

Setelah MySQL `running` + port listening → lanjut ke **Penyebab #5** (test koneksi). Jika tetap error, lanjut ke Penyebab #2.

### Penyebab #2 — Konfigurasi `.env` site salah

```bash
grep -E '^DB_|^CACHE_|^SESSION_|^QUEUE_' /www/wwwroot/maudu-rejoso.sch.id/.env
```

Pastikan nilai berikut:

| Key | Nilai yang benar | Catatan |
|-----|------------------|---------|
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | **`127.0.0.1`** | **Disarankan di aaPanel.** `localhost` di PHP = unix socket; di aaPanel path socket CLI ≠ php-fpm → `[2002]`. `127.0.0.1` memaksa **TCP** |
| `DB_PORT` | `3306` | **Cek juga di aaPanel → Databases → MySQL** — port aaPanel **bisa custom**, jangan asumsikan 3306 |
| `DB_DATABASE` | sesuai yang dibuat di aaPanel | mis. `maudu_rejoso` |
| `DB_USERNAME` | sesuai user yang dibuat di aaPanel | mis. `maudu_user` |
| `DB_PASSWORD` | sesuai yang dibuat | jangan kosong |
| `CACHE_STORE` | `database` | wajib untuk flag `illuminate:queue:restart` |
| `SESSION_DRIVER` | `database` | |
| `QUEUE_CONNECTION` | `database` | |

**Fix:** edit `.env`:

```bash
nano /www/wwwroot/maudu-rejoso.sch.id/.env
```

Lalu bersihkan cache config:

```bash
cd /www/wwwroot/maudu-rejoso.sch.id
/www/server/php/83/bin/php artisan config:clear
```

> ⚠️ **Typo path**: pastikan semua path memakai `/www/wwwroot/` (huruf `w` **tiga** kali: `www`). Config supervisor di server ini sempat salah ketik `/www/wwroot/...` (hanya dua `w`) — path salah = artisan tidak menemukan project / `.env` salah → koneksi "ditolak". Perbaiki juga di config supervisor (Bagian 2).

### Penyebab #3 — Mismatch socket / port MySQL aaPanel

```bash
grep -E 'port|socket' /etc/my.cnf
```

**Default aaPanel:**

```ini
port=3306
socket=/www/server/data/mysql.sock
```

**Masalah khas aaPanel:** jika `.env` memakai `DB_HOST=localhost`, **PHP CLI** memakai socket default PHP (`/tmp/mysql.sock`) yang di aaPanel **tidak ada** → `[2002] Connection refused`. (php-fpm bisa jalan normal karena di-set beda — makanya error muncul **hanya di artisan/supervisor**, bukan di website.)

**Fix (pilih salah satu, disarankan opsi A):**

- **Opsi A (disarankan):** paksa TCP di `.env` — ubah `DB_HOST` jadi `127.0.0.1`, lalu `php artisan config:clear`.
- **Opsi B:** arahkan PHP ke socket aaPanel. Tambahkan di `/www/server/php/83/etc/php.ini`:

  ```ini
  pdo_mysql.default_socket=/www/server/data/mysql.sock
  ```

  Lalu restart FPM:

  ```bash
  service php-fpm-83 restart
  ```

- **Opsi C:** set `DB_SOCKET=/www/server/data/mysql.sock` di `.env` (didukung [`config/database.php`](../config/database.php) → key `unix_socket`) — hanya jika tetap ingin memakai socket.

### Penyebab #4 — Database & user belum dibuat (sangat umum di server baru)

Di server baru, **database belum tentu ada**. Cek cepat:

```bash
mysql -u root -p -e "SHOW DATABASES;"
```

Atau test user spesifik:

```bash
mysql -u USERNAME_USER -p -h 127.0.0.1 -P PORT DATABASE -e "SELECT 1;"
```

**Fix — buat database + user via aaPanel (disarankan):**

1. aaPanel → menu **Databases** → **Add Database**
2. Isi: database name (mis. `maudu_rejoso`), username (mis. `maudu_user`), password (buat kuat), charset `utf8mb4`
3. aaPanel default memberi **full grant** ke user tersebut

**Import SQL dump** (jika ada dump dari server lama):

```bash
# Via CLI (sesuaikan nama DB & path dump)
mysql -u root -p maudu_rejoso < /path/ke/dump.sql

# Cek hasil import
mysql -u root -p maudu_rejoso -e "SHOW TABLES;"
```

Atau via **phpMyAdmin** bawaan aaPanel (Databases → phpMyAdmin).

> **Catatan keamanan**: jika credential DB pernah dipindahkan antar server, pastikan password di `.env` production = password yang dibuat di aaPanel, dan jangan pakai password sementara yang bocor.

### Penyebab #5 — Test koneksi dari CLI PHP 8.3 yang **sama persis** dengan supervisor

Ini tes paling penting: gunakan binary PHP yang **persis** dipakai supervisor (`/www/server/php/83/bin/php`), bukan `php` default di PATH.

```bash
cd /www/wwwroot/maudu-rejoso.sch.id

# 1. Ekstensi PDO MySQL harus ada
/www/server/php/83/bin/php -m | grep -i -E 'pdo_mysql|mysqli'

# 2. Test koneksi PDO Laravel
/www/server/php/83/bin/php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK'.PHP_EOL;"

# 3. Status migrasi
/www/server/php/83/bin/php artisan migrate:status
```

**Hasil yang diharapkan:**

- Langkah 1 → muncul `pdo_mysql` (dan/atau `mysqli`).
- Langkah 2 → muncul **`DB OK`**.
- Langkah 3 → daftar migrasi (bukan error koneksi).

Jika `DB OK` muncul → **koneksi beres**, lanjut ke **Bagian 3**. Jika `pdo_mysql` tidak ada di output langkah 1 → install ekstensi via aaPanel: **App Store → PHP 8.3 → Install extension → pdo_mysql / mysqli** lalu **Save & restart php-fpm-83**.

### Tabel Troubleshooting Ringkas

| Gejala | Penyebab | Fix (ringkas) |
|--------|----------|----------------|
| `ps aux \| grep mysql` kosong; `ss` tidak ada port 3306 | MySQL mati / belum diinstall | `service mysql start` atau start via aaPanel Databases |
| `[2002]` hanya muncul di artisan/supervisor, website jalan | `DB_HOST=localhost` → PHP CLI pakai socket `/tmp/mysql.sock` yang tidak ada | Ubah `DB_HOST=127.0.0.1` (TCP) atau set `pdo_mysql.default_socket=/www/server/data/mysql.sock` |
| `[2002]` padahal MySQL jalan di port lain | `DB_PORT` salah (aaPanel bisa custom port) | Cek port di aaPanel Databases → My.cnf, samakan dengan `DB_PORT` |
| Error `[1045] Access denied` | User/password `.env` tidak cocok | Samakan `DB_USERNAME`/`DB_PASSWORD` dengan yang dibuat di aaPanel |
| Error `[1049] Unknown database` | Database belum dibuat / nama salah | Buat DB via aaPanel Databases → Add Database, lalu import dump |
| `Call to undefined function PDO::__construct` / driver hilang | Ekstensi `pdo_mysql` belum ada di PHP 8.3 | aaPanel → PHP 8.3 → Install extension `pdo_mysql` → restart php-fpm-83 |
| `SQLSTATE[HY000] [2002] No such file or directory` | Khusus unix socket tidak ditemukan | Pastikan `DB_HOST=127.0.0.1`, atau set `DB_SOCKET=/www/server/data/mysql.sock` |
| Worker error tapi web jalan | Config cache CLI berbeda / cache config basi di server | `php artisan config:clear` lalu restart daemon supervisor |
| `supervisor` FATAL / BACKOFF | Command salah (typo path `/www/wwroot/`) atau PHP path salah | Perbaiki command sesuai Bagian 2, pastikan `/www/wwwroot/` + `/www/server/php/83/bin/php` |

---

## Bagian 2 — Setup Supervisor di aaPanel (perbaikan config `sekolah-queue`)

> **Prasyarat**: Bagian 1 sudah beres — MySQL running dan test `DB OK` sukses. Karena `CACHE_STORE=database`, worker **wajib** bisa koneksi MySQL (worker polling tabel `cache` tiap detik untuk flag `illuminate:queue:restart`).

### Config supervisor yang dianjurkan

Edit via **aaPanel → Supervisor → Daemon → Edit / Config**, atau langsung edit file config yang digenerate aaPanel di `/www/server/panel/plugin/supervisor/conf/`:

```ini
[program:maudu-queue]
command=/www/server/php/83/bin/php /www/wwwroot/maudu-rejoso.sch.id/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/www/wwwroot/maudu-rejoso.sch.id
autostart=true
autorestart=true
startsecs=3
startretries=3
stdout_logfile=/www/server/panel/plugin/supervisor/log/maudu-queue.out.log
stderr_logfile=/www/server/panel/plugin/supervisor/log/maudu-queue.err.log
stdout_logfile_maxbytes=2MB
stderr_logfile_maxbytes=2MB
user=www
priority=999
numprocs=2
stopsignal=QUIT
stopwaitsecs=3600
process_name=%(program_name)s_%(process_num)02d
```

### UI aaPanel (Add/Edit Daemon)

Jika memilih lewat form UI aaPanel (Supervisor → Add Daemon), isi:

| Field | Nilai |
|-------|-------|
| Name | `maudu-queue` |
| Run User | `www` |
| Processes (numprocs) | `2` |
| Start command | `/www/server/php/83/bin/php /www/wwwroot/maudu-rejoso.sch.id/artisan queue:work --sleep=3 --tries=3 --timeout=60 --max-time=3600` |
| Process directory (directory) | `/www/wwwroot/maudu-rejoso.sch.id` |

Setelah Save: **Stop → Start** daemon (atau via Config file → Save → restart daemon).

> ⚠️ **Typo yang harus diperbaiki dari config lama**: command & directory lama memakai `/www/wwroot/...` (hanya dua `w`) — path tidak ada. Harus `/www/wwwroot/maudu-rejoso.sch.id` (**tiga** `w`).

### Perubahan vs config lama (`sekolah-queue`)

| Item | Lama | Baru | Alasan |
|------|------|------|--------|
| `command` path | `/www/wwroot/...` (typo) | `/www/wwwroot/...` | Path salah = worker tidak jalan / pakai `.env` salah |
| `--tries` | `60` | **`3`** | 60 retry = poison message mengulang 60x, membanjiri DB & log |
| `--timeout` / `--max-time` | `--timeout=60` saja | `--timeout=60 --max-time=3600` (atau minimal `--max-time=3600`) | `--max-time` me-recycle worker tiap jam (mencegah memory leak); `--timeout` = timeout **per job** |
| `numprocs` | `1` | **`2`** | Redundancy + throughput |
| `stopwaitsecs` | (tidak ada) | `3600` | Beri waktu job panjang selesai sebelum kill (pasangkan `stopwaitsecs` ≥ `--max-time`) |
| `stopsignal` | (default TERM) | `QUIT` | Laravel `queue:work` menangani SIGQUIT untuk graceful shutdown |
| nama program | `sekolah-queue` | `maudu-queue` (opsional) | Penamaan per site |
| `stdout/stderr_logfile` | (belum jelas) | log terpisah + `maxbytes=2MB` | Memudahkan diagnosa; rotate otomatis |

### Verifikasi supervisor

```bash
supervisorctl status | grep maudu
# Harus: RUNNING (bukan FATAL / BACKOFF / EXITED)
# Jika numprocs=2 → 2 baris: maudu-queue:maudu-queue_00 & maudu-queue:maudu-queue_01

# Monitor log worker secara live
tail -f /www/server/panel/plugin/supervisor/log/maudu-queue.out.log
```

**Tanda sehat:**

- Status `RUNNING` dan **tidak** restart terus-menerus (tidak `BACKOFF`).
- Log `out.log` tidak menampilkan `SQLSTATE[HY000] [2002]` berulang.
- Setelah `queue:restart`, worker restart sekali lalu kembali polling normal.

---

## Bagian 3 — Post-fix: Setup Aplikasi (setelah DB OK)

Jalankan berikut di server. Semua perintah memakai **PHP 8.3 aaPanel** (full path — jangan mengandalkan `php` di PATH):

```bash
cd /www/wwwroot/maudu-rejoso.sch.id
P=/www/server/php/83/bin/php

$P artisan config:clear
$P artisan view:clear
$P artisan route:clear
$P artisan migrate --force        # jika pakai DB produksi
$P artisan storage:link           # symlink storage (gambar berita, dst)
$P artisan optimize:clear

# Pastikan .env production benar
grep -E 'APP_ENV|APP_DEBUG|DEFAULT_THEME' .env
# Diharapkan: APP_ENV=production, APP_DEBUG=false, DEFAULT_THEME=maudu
```

**Detail tambahan:**

- **`migrate --force`** hanya jika ini DB produksi dan schema belum lengkap. Jika sudah diimport dump lengkap, cukup cek `$P artisan migrate:status` (semua `Ran`).
- **`storage:link`** penting untuk upload gambar berita, foto profil, export, dll (user web `www` harus punya izin tulis ke `storage/`):

  ```bash
  chown -R www:www /www/wwwroot/maudu-rejoso.sch.id/storage
  chown -R www:www /www/wwwroot/maudu-rejoso.sch.id/bootstrap/cache
  ```

- Jika `APP_DEBUG=true` masih tertinggal, error 500 akan menampilkan stack trace penuh ke publik — **wajib `false`** di production.
- `DEFAULT_THEME=maudu` memastikan landing page (`GET /`) render tema MAUDU via helper [`current_theme()`](../app/Helpers/ThemeHelper.php).

---

## Bagian 4 — Setup Cron di aaPanel (Laravel Scheduler)

aaPanel punya menu **Cron** (计划任务) di sidebar. Tambah task baru:

| Field | Nilai |
|-------|-------|
| Name | `maudu-scheduler` |
| Task type | `Shell Script` |
| Timing | `N Minutes` → **`1`** (setiap 1 menit) |
| Command / URL | lihat di bawah |
| Reminder | No reminder (atau sesuai selera) |

**Command:**

```bash
cd /www/wwwroot/maudu-rejoso.sch.id && /www/server/php/83/bin/php artisan schedule:run >> /dev/null 2>&1
```

### Verifikasi cron

- **aaPanel** → Cron → Log task → muncul eksekusi **tiap menit**.
- Atau SSH: `crontab -l` — aaPanel menulis cron ke crontab root/panel; harus muncul baris `schedule:run` untuk path site ini.

### ⚠️ JANGAN dobelkan schedule:run

**JANGAN** daftarkan `schedule:run` di dua tempat (cron panel aaPanel **+** crontab manual) — cukup **sekali**, agar task tidak dobel. Pengalaman di ServerSMK: cron dobel menyebabkan task berjalan dua kali per periode (double sync/notifikasi). Jika sebelumnya sudah ada entry cron manual untuk site ini, hapus dulu satu di antaranya:

```bash
crontab -l          # lihat isi crontab
crontab -r          # ⚠️ hati-hati: menghapus SEMUA — lebih baik edit dengan crontab -e dan hapus baris yang dobel
```

### Jadwal task yang akan jalan

Scheduler project ini didefinisikan di [`routes/console.php`](../routes/console.php). Ringkasan yang akan jalan di server MAUDU:

| Waktu | Task |
|-------|------|
| Setiap 1 menit | `schedule:run` (cron entry — pengecekan scheduler) |
| Setiap 5 menit | `instagram:sync`, `attendance:sync` |
| Setiap 10 menit | *closure* `iclock-command-queue-recovery` |
| Setiap jam (jam kerja) | `attendance:notify --excuse` |
| Jam 02:00 | `attendance:cleanup` |
| Jam 03:00 | *closure* `cleanup-old-jobs` |
| Jam 08:00 | `sarpras:send-notifications --daily` |
| Jam 16:00 | `attendance:notify --summary` |
| Jam 23:00 | `attendance:mark-alpha` |
| Tanggal 1 tiap bulan | `instagram:refresh-token` |

> Semua task memakai `withoutOverlapping()` — cukup `schedule:run` 1x per menit.

Verifikasi manual:

```bash
cd /www/wwwroot/maudu-rejoso.sch.id
/www/server/php/83/bin/php artisan schedule:list
# Harus menampilkan ~10 task terjadwal beserta Next Run
```

---

## Bagian 5 — Checklist Verifikasi Akhir

Jalankan / cek satu per satu setelah Bagian 1–4 selesai:

- [ ] Website `https://maudu-rejoso.sch.id` dibuka **tanpa error 500**
- [ ] `/www/server/php/83/bin/php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';"` → output **`OK`**
- [ ] Supervisor daemon `maudu-queue` → **RUNNING** (2 proses), bukan FATAL/BACKOFF
- [ ] Log supervisor `maudu-queue.err.log` tidak menampilkan `SQLSTATE[HY000] [2002]` berulang
- [ ] `php artisan queue:failed` → tidak ada error koneksi (boleh kosong/failed job lama saja)
- [ ] `php artisan queue:work --once` (test sekali jalan) → tidak error koneksi
- [ ] Cron `maudu-scheduler` tercatat di aaPanel Cron, **log muncul tiap menit**
- [ ] `crontab -l` tidak menampilkan `schedule:run` dobel untuk site ini
- [ ] `php artisan schedule:list` → ±10 task terjadwal dengan Next Run valid
- [ ] `DEFAULT_THEME=maudu` di `.env`, landing page tampil **tema MAUDU**
- [ ] Login admin (`/login`) → favicon/logo sesuai tema (upload via **Admin → Theme Settings** jika perlu)
- [ ] `APP_ENV=production`, `APP_DEBUG=false` di `.env`
- [ ] `storage/` & `bootstrap/cache/` dimiliki user `www` (upload gambar & cache jalan)

---

## Ringkasan Path aaPanel (referensi cepat)

| Item | Nilai |
|------|-------|
| Path site | `/www/wwwroot/maudu-rejoso.sch.id` |
| PHP CLI (8.3) | `/www/server/php/83/bin/php` |
| php.ini (8.3) | `/www/server/php/83/etc/php.ini` |
| User web & worker | `www` |
| MySQL socket aaPanel | `/www/server/data/mysql.sock` |
| MySQL port (default) | `3306` (cek di aaPanel Databases — bisa custom) |
| My.cnf | `/etc/my.cnf` |
| Supervisor log | `/www/server/panel/plugin/supervisor/log/` |
| Supervisor config | `/www/server/panel/plugin/supervisor/conf/` |

---

## Dokumen terkait

- [`docs/VPS-DEPLOY.md`](VPS-DEPLOY.md) — panduan deploy umum (VPS non-aaPanel)
- [`docs/CRON-SUPERVISOR-SETUP.md`](CRON-SUPERVISOR-SETUP.md) — panduan cron & supervisor di ServerSMK (referensi struktur)
- [`plans/theme-system-refactoring.md`](../plans/theme-system-refactoring.md) — sistem theme switching + panduan menambah tema
- [`AGENTS.md`](../AGENTS.md) — konvensi project Laravel 12 multi-theme ini
