<?php

namespace Database\Seeders;

use App\Models\AttendanceDevice;
use App\Models\AttendanceIdentity;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        AttendanceDevice::updateOrCreate(
            ['serial_number' => 'ZK-DEMO-001'],
            [
                'name' => 'ZKTeco Demo',
                'ip_address' => '192.168.1.201',
                'port' => 4370,
                'comm_key' => null,
                'is_active' => true,
            ]
        );

        $superadmin = User::where('email', 'superadmin@sekolah.com')->first();
        if ($superadmin) {
            AttendanceIdentity::updateOrCreate(
                [
                    'kind' => 'user',
                    'user_id' => $superadmin->id,
                ],
                [
                    'device_pin' => '9001',
                    'is_active' => true,
                ]
            );
        }

        $guru = Guru::query()->orderBy('id')->first();
        if ($guru) {
            AttendanceIdentity::updateOrCreate(
                [
                    'kind' => 'guru',
                    'guru_id' => $guru->id,
                ],
                [
                    'device_pin' => '1001',
                    'is_active' => true,
                ]
            );
        }

        $siswa = Siswa::query()->orderBy('id')->first();
        if ($siswa) {
            AttendanceIdentity::updateOrCreate(
                [
                    'kind' => 'siswa',
                    'siswa_id' => $siswa->id,
                ],
                [
                    'device_pin' => '2001',
                    'is_active' => true,
                ]
            );
        }
    }
}
