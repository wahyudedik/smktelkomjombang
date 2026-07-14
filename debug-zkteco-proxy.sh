#!/bin/bash
# ============================================
# ZKTeco iClock Proxy Simulation Test Script
# ============================================
# Script ini mensimulasikan request dari iClock Proxy
# untuk testing sebelum deploy ke VPS.
#
# Usage:
#   chmod +x debug-zkteco-proxy.sh
#   ./debug-zkteco-proxy.sh
#
# Atau test manual dengan curl:
#   curl -v -X POST "http://127.0.0.1/iclock/cdata?SN=SPK7254300208&table=ATTLOG&Stamp=9999" \
#     -H "User-Agent: iClock Proxy/1.09" \
#     -H "Content-Type: text/plain" \
#     -d "1$(printf '\t')2026-07-14 07:00:00$(printf '\t')0$(printf '\t')1"
# ============================================

SERVER="http://127.0.0.1"
SN="SPK7254300208"

echo "=== ZKTeco iClock Proxy Simulation Test ==="
echo "Server: $SERVER"
echo "Serial: $SN"
echo ""

# Test 1: GET /iclock/getrequest (seperti device/proxy minta commands)
echo "--- Test 1: GET /iclock/getrequest ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  "$SERVER/iclock/getrequest?SN=$SN"
echo ""

# Test 2: POST /iclock/cdata dengan table=ATTLOG (iClock Proxy announce)
echo "--- Test 2: POST /iclock/cdata?table=ATTLOG (empty body) ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  -X POST "$SERVER/iclock/cdata?SN=$SN&table=ATTLOG&Stamp=9999" \
  -H "User-Agent: iClock Proxy/1.09" \
  -H "Content-Type: text/plain" \
  -d ""
echo ""

# Test 3: POST /iclock/cdata dengan ATTLOG data (tab-separated)
echo "--- Test 3: POST /iclock/cdata?table=ATTLOG (tab-separated data) ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  -X POST "$SERVER/iclock/cdata?SN=$SN&table=ATTLOG&Stamp=9999" \
  -H "User-Agent: iClock Proxy/1.09" \
  -H "Content-Type: text/plain" \
  -d "$(printf '1\t2026-07-14 07:00:00\t0\t1')
$(printf '1\t2026-07-14 16:00:00\t0\t1')"
echo ""

# Test 4: POST /iclock/cdata dengan ATTLOG data (PIN= prefix format)
echo "--- Test 4: POST /iclock/cdata?table=ATTLOG (PIN= prefix format) ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  -X POST "$SERVER/iclock/cdata?SN=$SN&table=ATTLOG&Stamp=9999" \
  -H "User-Agent: iClock Proxy/1.09" \
  -H "Content-Type: text/plain" \
  -d "PIN=1$(printf '\t')DateTime=2026-07-14 07:00:00$(printf '\t')Verified=1$(printf '\t')Status=0
PIN=1$(printf '\t')DateTime=2026-07-14 16:00:00$(printf '\t')Verified=1$(printf '\t')Status=1"
echo ""

# Test 5: POST /iclock/cdata dengan table=OPERLOG (non-ATTLOG)
echo "--- Test 5: POST /iclock/cdata?table=OPERLOG ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  -X POST "$SERVER/iclock/cdata?SN=$SN&table=OPERLOG&OpStamp=9999" \
  -H "User-Agent: iClock Proxy/1.09" \
  -H "Content-Type: text/plain" \
  -d "test operlog data"
echo ""

# Test 6: POST /iclock/cdata dengan table=options
echo "--- Test 6: POST /iclock/cdata?table=options ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  -X POST "$SERVER/iclock/cdata?SN=$SN&table=options" \
  -H "User-Agent: iClock Proxy/1.09" \
  -H "Content-Type: text/plain" \
  -d "test options data"
echo ""

# Test 7: POST /iclock/devicecmd (command result)
echo "--- Test 7: POST /iclock/devicecmd ---"
curl -s -w "\nHTTP Status: %{http_code}\n" \
  -X POST "$SERVER/iclock/devicecmd?SN=$SN&ID=1&Return=0" \
  -H "User-Agent: iClock Proxy/1.09" \
  -H "Content-Type: text/plain" \
  -d ""
echo ""

echo "=== Test Selesai ==="
echo ""
echo "Cek log Laravel:"
echo "  tail -f storage/logs/laravel.log | grep ZKTeco"
echo ""
echo "Cek raw payload files:"
echo "  ls -la storage/app/zkteco-raw/"
echo ""
echo "Cek attendance_logs:"
echo "  php artisan tinker --execute=\"echo App\Models\AttendanceLog::count();\""
echo ""
echo "Cek attendance_devices:"
echo "  php artisan tinker --execute=\"echo App\Models\AttendanceDevice::all()->toArray();\""
