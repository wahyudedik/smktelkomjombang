#!/bin/bash

echo "🔍 ZKTeco Debugging Script"
echo "=========================="
echo ""

# 1. Cek .env
echo "1️⃣ Checking .env..."
ICLOCK_SECRET=$(grep ATTENDANCE_ICLOCK_SECRET .env | cut -d'=' -f2)
echo "   ATTENDANCE_ICLOCK_SECRET: $ICLOCK_SECRET"
echo ""

# 2. Cek config
echo "2️⃣ Checking config..."
php artisan config:show attendance.iclock_secret
echo ""

# 3. Test endpoint
echo "3️⃣ Testing endpoint..."
echo "   GET /iclock/getrequest?SN=TEST&token=$ICLOCK_SECRET"
curl -v "http://telkom.test/iclock/getrequest?SN=TEST&token=$ICLOCK_SECRET" 2>&1 | head -20
echo ""

# 4. Cek database
echo "4️⃣ Checking database..."
echo "   Attendance devices:"
php artisan tinker --execute="DB::table('attendance_devices')->get();"
echo ""

# 5. Cek log
echo "5️⃣ Checking logs..."
echo "   Last 20 lines from laravel.log:"
tail -20 storage/logs/laravel.log
echo ""

# 6. Cek route
echo "6️⃣ Checking routes..."
php artisan route:list | grep iclock
echo ""

echo "✅ Debug selesai"
