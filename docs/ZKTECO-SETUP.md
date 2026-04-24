# ZKTeco iClock Setup Guide
## SMK Telekomunikasi Darul Ulum

---

## 🔥 KESIMPULAN PENTING (BACA INI DULU!)

Banyak yang gagal setup ZKTeco karena 3 kesalahan umum:

| ❌ Salah | ✅ Benar |
|---------|---------|
| Pakai HTTPS | Pakai HTTP dulu (testing) |
| Token di URL: `?token=...` | Token di query param atau header |
| Endpoint: `/iclock/cdata?token=...&SN=...` | Endpoint: `/iclock/cdata?SN=...&token=...` |
| Device setting: URL lengkap | Device setting: Server + Port terpisah |

---

## Protokol ZKTeco (Wajib Tahu)

Device ZKTeco **TIDAK fleksibel**. Dia hanya ngerti 3 endpoint:

### 1. `/iclock/getrequest` — Device minta perintah
```
GET /iclock/getrequest?SN=SERIAL_NUMBER&token=TOKEN
```
Server balas dengan config + command queue.

### 2. `/iclock/cdata` — Device kirim data absensi
```
POST /iclock/cdata?SN=SERIAL_NUMBER&token=TOKEN
[binary attendance data]
```
Server balas: `OK`

### 3. `/iclock/devicecmd` — Device kirim hasil command
```
POST /iclock/devicecmd?SN=SERIAL_NUMBER&ID=CMD_ID&Return=RESULT&token=TOKEN
```
Server balas: `OK`

---

## Setup Step-by-Step

### Step 1: Verifikasi .env

```bash
grep ATTENDANCE_ICLOCK_SECRET /var/www/telkom/.env
```

Harus ada:
```env
ATTENDANCE_ICLOCK_SECRET=iloveSMKkuYangIndahTelkomJayaAbadinusantara
```

Jika belum ada, tambahkan ke `.env`:
```bash
echo "ATTENDANCE_ICLOCK_SECRET=iloveSMKkuYangIndahTelkomJayaAbadinusantara" >> /var/www/telkom/.env
```

### Step 2: Test Endpoint (HTTP)

**Test getrequest:**
```bash
curl -v "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST123&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara"
```

Expected response:
```
HTTP/1.1 200 OK
Content-Type: text/plain; charset=UTF-8

GET OPTION FROM:TEST123
Stamp=9999
OpStamp=0
ErrorDelay=30
Delay=10
TransTimes=00:00;23:59
TransInterval=1
TransFlag=1111111111
Realtime=1
```

**Test cdata:**
```bash
curl -X POST "http://smktelekomunikasidu.sch.id/iclock/cdata?SN=TEST123&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara" \
  -d "test"
```

Expected response:
```
HTTP/1.1 200 OK
OK
```

### Step 3: Setup Device MB20-VL

**Masuk menu:**
```
Menu → Communication → ADMS / Cloud Server
```

**Isi setting:**

| Field | Value |
|-------|-------|
| Server Address | `smktelekomunikasidu.sch.id` |
| Port | `80` |
| Protocol | `HTTP` |
| Path | `/iclock/cdata` |
| Token | `iloveSMKkuYangIndahTelkomJayaAbadinusantara` |
| Push Interval | `60` |
| Enable Push | `ON` |

**Atau jika device support URL lengkap:**
```
Server URL: http://smktelekomunikasidu.sch.id/iclock/cdata?token=iloveSMKkuYangIndahTelkomJayaAbadinusantara
```

**Simpan & Restart:**
```
Save → Restart
```

### Step 4: Verifikasi Device Terhubung

**Tunggu 2 menit, lalu cek:**

```bash
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
```

Harus ada device dengan:
- `serial_number` = serial device
- `ip_address` = IP device
- `last_seen_at` = waktu terbaru

**Atau cek di admin:**
```
https://smktelekomunikasidu.sch.id/admin/absensi/devices
```

### Step 5: Tambah User & Sync

1. Buka: `https://smktelekomunikasidu.sch.id/admin/absensi/users`
2. Klik **Tambah User**
3. Isi:
   - Nama: `Budi Santoso`
   - PIN: `1001` (unik, 1-5 digit)
   - Kelas: `XII RPL 1`
4. Klik **Simpan**
5. Klik **Sync Semua User ke Device**
6. Tunggu status: **done**

**Cek log:**
```bash
tail -f /var/www/telkom/storage/logs/laravel.log | grep -i "sync\|user"
```

### Step 6: Enroll Biometric

**Di device:**
```
Menu → Users → Pilih user (PIN 1001) → Fingerprint
```

Ikuti instruksi:
1. Letakkan jari telunjuk
2. Angkat & letakkan lagi (3-4 kali)
3. Sistem akan confirm: "Fingerprint enrolled"

**Atau gunakan Face:**
```
Menu → Users → Pilih user → Face
```

### Step 7: Test Scan

1. Scan fingerprint di device
2. Cek di admin: `https://smktelekomunikasidu.sch.id/admin/absensi/logs`
3. Data harus muncul dalam 1-2 menit

---

## Troubleshooting

### ❌ Device tidak muncul di admin

**Checklist:**

1. **Cek token di .env**
   ```bash
   grep ATTENDANCE_ICLOCK_SECRET /var/www/telkom/.env
   ```

2. **Cek endpoint bisa diakses**
   ```bash
   curl -v "http://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST&token=iloveSMKkuYangIndahTelkomJayaAbadinusantara"
   ```
   Harus return `200 OK`.

3. **Cek device setting**
   - Server Address: `smktelekomunikasidu.sch.id` (tanpa `http://`)
   - Port: `80`
   - Token: sama dengan `.env`

4. **Cek log Laravel**
   ```bash
   tail -f /var/www/telkom/storage/logs/laravel.log
   ```
   Cari error atau warning.

5. **Restart device**
   ```
   Menu → System → Restart
   ```

6. **Cek database**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
   ```

### ❌ Device connect tapi data tidak masuk

**Checklist:**

1. **User sudah di-sync?**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_identities LIMIT 5;"
   ```

2. **Biometric sudah enrolled?**
   ```
   Menu → Users → Pilih user → Cek fingerprint/face
   ```

3. **Cek log saat scan**
   ```bash
   tail -f /var/www/telkom/storage/logs/laravel.log
   # Lalu scan di device
   ```

4. **Cek database attendance_logs**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_logs ORDER BY created_at DESC LIMIT 10;"
   ```

5. **Queue worker jalan?**
   ```bash
   supervisorctl status telkom-worker:*
   ```

### ❌ Error 403 Forbidden

**Penyebab:** Token salah atau tidak dikirim.

**Fix:**
1. Cek token di device sama dengan `.env`
2. Cek device setting: Token field terisi
3. Restart device

### ❌ Error 500 Internal Server Error

**Cek log:**
```bash
tail -f /var/www/telkom/storage/logs/laravel.log
```

Cari error message, biasanya:
- Database connection error
- Missing column di table
- Service error

### ❌ Device connect tapi "Last Seen" tidak update

**Penyebab:** Device tidak push data secara berkala.

**Fix:**
1. Cek Push Interval: `60` detik
2. Cek Enable Push: `ON`
3. Restart device
4. Cek koneksi internet device

---

## Advanced: Upgrade ke HTTPS

Setelah setup stabil dengan HTTP, bisa upgrade ke HTTPS:

### Step 1: Update device setting

```
Menu → Communication → ADMS / Cloud Server
```

Ubah:
- Port: `443`
- Protocol: `HTTPS`

### Step 2: Verifikasi SSL certificate

```bash
curl -v "https://smktelekomunikasidu.sch.id/iclock/getrequest?SN=TEST&token=..."
```

Harus return `200 OK` (bukan SSL error).

### Step 3: Restart device

```
Menu → System → Restart
```

### Step 4: Monitor

```bash
tail -f /var/www/telkom/storage/logs/laravel.log
```

---

## Monitoring & Maintenance

### Daily Check

```bash
# Device status
mysql -u telkom_user -p telkom_db -e "SELECT serial_number, ip_address, last_seen_at FROM attendance_devices;"

# Today's attendance
mysql -u telkom_user -p telkom_db -e "SELECT COUNT(*) as total_scans FROM attendance_logs WHERE DATE(created_at) = CURDATE();"

# Check errors
tail -100 /var/www/telkom/storage/logs/laravel.log | grep -i "error\|exception"
```

### Weekly Check

```bash
# Device uptime
mysql -u telkom_user -p telkom_db -e "SELECT serial_number, last_seen_at, TIMESTAMPDIFF(HOUR, last_seen_at, NOW()) as hours_since_seen FROM attendance_devices;"

# Sync status
mysql -u telkom_user -p telkom_db -e "SELECT COUNT(*) as total_users FROM attendance_identities;"

# Queue status
supervisorctl status telkom-worker:*
```

---

## Quick Reference

### Useful Commands

```bash
# Restart device via API
curl -X POST "http://smktelekomunikasidu.sch.id/admin/api/attendance/device/SERIAL/restart?token=..."

# Clear attendance logs
mysql -u telkom_user -p telkom_db -e "DELETE FROM attendance_logs WHERE DATE(created_at) < DATE_SUB(CURDATE(), INTERVAL 30 DAY);"

# Export attendance report
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_logs WHERE DATE(created_at) = CURDATE();" > attendance_$(date +%Y%m%d).csv

# Check device connection
ping -c 4 DEVICE_IP_ADDRESS
```

### Device Serial Number

Biasanya tercetak di:
- Belakang device
- Menu → System → Device Info

---

**Last Updated:** 2026-04-23
**Status:** PRODUCTION READY
