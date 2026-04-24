# Dokumentasi SMK Telekomunikasi Darul Ulum

---

## 📚 Panduan Deployment & Setup

### 🚀 Deployment

**[VPS-DEPLOY.md](./VPS-DEPLOY.md)** — Panduan lengkap deployment ke production
- Setup server Ubuntu 20.04/22.04
- Install PHP 8.3, MySQL 8, Nginx, Node.js
- SSL dengan Let's Encrypt
- Database setup
- Nginx configuration
- Queue worker dengan Supervisor
- Scheduler setup
- **STEP 18: ZKTeco Setup** (updated dengan koreksi)
- Troubleshooting & maintenance
- Update code procedures

---

## 🔐 ZKTeco Absensi Setup

### 📖 Panduan Lengkap

**[ZKTECO-SETUP.md](./ZKTECO-SETUP.md)** — Panduan komprehensif setup ZKTeco iClock
- Protokol ZKTeco (3 endpoint standar)
- Verifikasi .env
- Testing endpoint (HTTP)
- Setup device MB20-VL
- Verifikasi device terhubung
- Tambah user & sync
- Enroll biometric
- Test scan
- Troubleshooting detail
- Advanced: Upgrade ke HTTPS
- Monitoring & maintenance
- Quick reference commands

### 🔧 Koreksi & Penjelasan

**[ZKTECO-CORRECTIONS.md](./ZKTECO-CORRECTIONS.md)** — Koreksi penting dari feedback GPT
- 3 masalah utama yang ditemukan
- Implementasi fix
- Alur yang benar (device request flow)
- Device setting yang benar
- Testing checklist
- Next steps (immediate, after stable, ongoing)

### ⚡ Quick Reference

**[ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md)** — Quick diagnosis & fix (5 menit)
- Quick diagnosis checklist
- Common issues & fixes
- Quick commands
- Escalation path
- Database queries

### 📋 Summary

**[SUMMARY-ZKTECO-FIX.md](./SUMMARY-ZKTECO-FIX.md)** — Ringkasan perubahan & koreksi
- Perubahan yang dilakukan
- 3 masalah utama & solusinya
- Alur setup yang benar
- Testing checklist
- Key takeaways

---

## 🎯 Mulai Dari Mana?

### Jika Baru Setup Server
1. Baca: **[VPS-DEPLOY.md](./VPS-DEPLOY.md)** (lengkap dari awal)
2. Ikuti step-by-step sampai STEP 18
3. Lanjut ke panduan ZKTeco

### Jika Setup ZKTeco
1. Baca: **[ZKTECO-SETUP.md](./ZKTECO-SETUP.md)** (panduan lengkap)
2. Ikuti step-by-step dengan testing di setiap tahap
3. Jika ada masalah, lihat **[ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md)**

### Jika Device Tidak Connect
1. Baca: **[ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md)** (quick diagnosis)
2. Ikuti checklist 5 menit
3. Jika masih tidak bisa, baca **[ZKTECO-CORRECTIONS.md](./ZKTECO-CORRECTIONS.md)**

### Jika Ingin Tahu Apa yang Berubah
1. Baca: **[SUMMARY-ZKTECO-FIX.md](./SUMMARY-ZKTECO-FIX.md)** (ringkasan)
2. Baca: **[ZKTECO-CORRECTIONS.md](./ZKTECO-CORRECTIONS.md)** (detail koreksi)

---

## 🔥 Key Points (Jangan Lupa!)

### HTTP vs HTTPS
- ✅ Gunakan **HTTP** untuk testing awal (port 80)
- ✅ Setelah stabil, upgrade ke **HTTPS** (port 443)
- ❌ Jangan langsung HTTPS (banyak device lama gagal)

### Device Setting
- ✅ Server Address: `domain.com` (tanpa http://)
- ✅ Port: `80` (HTTP) atau `443` (HTTPS)
- ✅ Token: sesuai dengan `.env` ATTENDANCE_ICLOCK_SECRET
- ✅ Push Interval: `60` detik
- ✅ Enable Push: `ON`

### Token Handling
- ✅ Token di query parameter: `?token=...&SN=...`
- ✅ Token di `.env`: `ATTENDANCE_ICLOCK_SECRET=...`
- ❌ Jangan: Token di URL path

### Testing Checklist
- [ ] Endpoint HTTP return 200 OK
- [ ] Device muncul di admin dalam 2 menit
- [ ] User di-sync ke device
- [ ] Biometric enrolled
- [ ] Test scan → data muncul di logs

---

## 📞 Troubleshooting Quick Links

| Masalah | Solusi |
|---------|--------|
| Device tidak muncul | [ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md#issue-1-device-tidak-muncul-di-admin) |
| Data tidak masuk | [ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md#issue-2-device-connect-tapi-data-tidak-masuk) |
| Error 403 | [ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md#issue-3-error-403-forbidden) |
| Error 500 | [ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md#issue-4-error-500-internal-server-error) |
| Last Seen tidak update | [ZKTECO-QUICK-FIX.md](./ZKTECO-QUICK-FIX.md#issue-5-device-connect-tapi-last-seen-tidak-update) |

---

## 🚀 Quick Commands

### Test Endpoint
```bash
curl -v "http://domain.com/iclock/getrequest?SN=TEST&token=YOUR_TOKEN"
```

### Check Device
```bash
mysql -u telkom_user -p telkom_db -e "SELECT * FROM attendance_devices;"
```

### View Logs
```bash
tail -f /var/www/telkom/storage/logs/laravel.log | grep -i attendance
```

### Restart Services
```bash
systemctl restart php8.3-fpm
systemctl restart nginx
supervisorctl restart telkom-worker:*
```

---

## 📊 File Structure

```
docs/
├── README.md                      ← Anda di sini
├── VPS-DEPLOY.md                  ← Panduan deployment lengkap
├── ZKTECO-SETUP.md                ← Panduan setup ZKTeco
├── ZKTECO-CORRECTIONS.md          ← Koreksi & penjelasan
├── ZKTECO-QUICK-FIX.md            ← Quick reference
└── SUMMARY-ZKTECO-FIX.md          ← Ringkasan perubahan
```

---

## ✅ Status

- ✅ VPS Deployment Guide: PRODUCTION READY
- ✅ ZKTeco Setup Guide: PRODUCTION READY
- ✅ Corrections Applied: VERIFIED
- ✅ Quick Fix Guide: READY
- ✅ Documentation: COMPLETE

---

**Last Updated:** 2026-04-23
**Version:** 1.0
**Status:** PRODUCTION READY
