# ZKTeco Setup Corrections
## Feedback dari GPT + Implementasi Fix

---

## ðŸ”¥ MASALAH YANG DITEMUKAN

### 1. âŒ Format Endpoint Kurang Tepat

**Sebelumnya (SALAH):**
```
https://smktelekomunikasidu.sch.id/iclock/cdata?token=...
```

**Masalah:**
- Device ZKTeco tidak fleksibel seperti REST API
- Device hanya ngerti 3 endpoint standar: `/iclock/getrequest`, `/iclock/cdata`, `/iclock/devicecmd`
- Request dari device format: `/iclock/cdata?SN=SERIAL&table=rtlog`

**Sekarang (BENAR):**
```
http://smktelekomunikasidu.sch.id/iclock/cdata?SN=SERIAL&token=TOKEN
```

---

### 2. âŒ Pakai HTTPS (Penyebab Utama Gagal)

**Masalah:**
- Banyak device ZKTeco lama gagal konek ke HTTPS
- Certificate validation sering error di device
- Device tidak support SSL/TLS dengan baik

**Solusi:**
- âœ… Gunakan **HTTP** untuk testing awal (port 80)
- âœ… Setelah stabil, upgrade ke HTTPS dengan certificate valid
- âœ… Pastikan certificate chain lengkap

---

### 3. âŒ Token Penempatan Salah

**Sebelumnya (SALAH):**
```
Server URL: https://domain.com/iclock/cdata?token=...
```

**Masalah:**
- Token di URL query string tidak selalu diproses device
- Device lebih suka token di header atau parameter terpisah

**Sekarang (BENAR):**
```
Server Address: domain.com
Port: 80
Token: [field terpisah]
```

Atau jika device support URL lengkap:
```
Server URL: http://domain.com/iclock/cdata?token=TOKEN&SN=SERIAL
```

---

## âœ… IMPLEMENTASI FIX

### File yang Diupdate

1. **docs/VPS-DEPLOY.md**
   - âœ… STEP 18 diperbaharui dengan protokol ZKTeco yang benar
   - âœ… Penjelasan HTTP vs HTTPS
   - âœ… Format device setting yang benar
   - âœ… Troubleshooting lebih detail

2. **docs/ZKTECO-SETUP.md** (BARU)
   - âœ… Panduan lengkap setup ZKTeco
   - âœ… Testing endpoint step-by-step
   - âœ… Device configuration yang benar
   - âœ… Troubleshooting komprehensif
   - âœ… Monitoring & maintenance

### Code yang Sudah Benar

**app/Http/Controllers/ZKTecoIClockController.php**
- âœ… Sudah handle token dari query param
- âœ… Sudah handle SN (serial number) dari query
- âœ… Sudah return format yang benar

**routes/web.php**
- âœ… Route `/iclock/getrequest` âœ…
- âœ… Route `/iclock/cdata` âœ…
- âœ… Route `/iclock/devicecmd` âœ…

---

## ðŸŽ¯ ALUR YANG BENAR (SEKARANG)

### Device Request Flow

```
1. Device startup
   â†“
2. Device GET /iclock/getrequest?SN=SERIAL&token=TOKEN
   â†“
3. Server return config + command queue
   â†“
4. Device POST /iclock/cdata?SN=SERIAL&token=TOKEN [attendance data]
   â†“
5. Server return OK
   â†“
6. Device POST /iclock/devicecmd?SN=SERIAL&ID=CMD_ID&Return=RESULT&token=TOKEN
   â†“
7. Server return OK
```

### Device Setting (Benar)

| Field | Value |
|-------|-------|
| Server Address | `smktelekomunikasidu.sch.id` |
| Port | `80` (HTTP) atau `443` (HTTPS) |
| Protocol | `HTTP` atau `HTTPS` |
| Path | `/iclock/cdata` |
| Token | `YOUR_TOKEN_HERE` |
| Push Interval | `60` |
| Enable Push | `ON` |

> ⚠️ **KEAMANAN**: Nilai token di dokumentasi ini sudah diganti placeholder `YOUR_TOKEN_HERE`. Jika token asli pernah dibagikan/publik, segera **ROTASI token** di device & `.env` (buat baru: `openssl rand -hex 32`).

---

## ðŸ“‹ TESTING CHECKLIST

### Pre-Setup Testing

- [ ] Cek `.env` ada `ATTENDANCE_ICLOCK_SECRET`
- [ ] Test endpoint HTTP: `curl http://domain.com/iclock/getrequest?SN=TEST&token=...`
- [ ] Response harus `200 OK` + config lines
- [ ] Cek log Laravel: `tail -f storage/logs/laravel.log`

### Device Setup

- [ ] Device setting: Server Address (tanpa http://)
- [ ] Device setting: Port 80 (HTTP)
- [ ] Device setting: Token sesuai `.env`
- [ ] Device setting: Push Interval 60
- [ ] Device setting: Enable Push ON
- [ ] Device restart

### Post-Setup Verification

- [ ] Device muncul di admin `/admin/absensi/devices`
- [ ] Device `last_seen_at` update setiap 1-2 menit
- [ ] User ditambahkan & sync ke device
- [ ] Biometric enrolled (fingerprint/face/card)
- [ ] Test scan â†’ data muncul di `/admin/absensi/logs`
- [ ] Database `attendance_logs` terisi

---

## ðŸš€ NEXT STEPS

### Immediate (Testing)

1. Gunakan **HTTP** dulu (port 80)
2. Test endpoint dengan curl
3. Setup device dengan setting yang benar
4. Verify device connect dalam 2 menit
5. Test scan pertama

### After Stable (Production)

1. Upgrade ke HTTPS (port 443)
2. Verify SSL certificate valid
3. Restart device
4. Monitor logs untuk SSL errors
5. Jika ada error, rollback ke HTTP

### Ongoing (Maintenance)

1. Monitor device `last_seen_at` daily
2. Check attendance logs untuk anomali
3. Backup database weekly
4. Update device firmware jika ada
5. Test scan berkala

---

## ðŸ“ž SUPPORT

Jika masih ada masalah:

1. **Cek log Laravel:**
   ```bash
   tail -f /var/www/telkom/storage/logs/laravel.log | grep -i "attendance\|iclock"
   ```

2. **Cek database:**
   ```bash
   mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
   ```

3. **Test endpoint:**
   ```bash
   curl -v "http://domain.com/iclock/getrequest?SN=TEST&token=..."
   ```

4. **Cek device setting:**
   ```
   Menu â†’ Communication â†’ ADMS / Cloud Server
   ```

5. **Restart device:**
   ```
   Menu â†’ System â†’ Restart
   ```

---

**Last Updated:** 2026-04-23
**Status:** CORRECTIONS APPLIED
