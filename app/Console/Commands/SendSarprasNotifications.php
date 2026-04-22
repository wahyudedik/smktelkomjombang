<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Barang;
use App\Models\Sarana;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SendSarprasNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sarpras:send-notifications
                            {--daily : Send daily notifications}
                            {--weekly : Send weekly summary}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for damaged items and sarana that need updates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 Checking for Sarpras notifications...');

        // Get barang rusak yang perlu maintenance
        $barangRusakPerluMaintenance = Barang::where('kondisi', 'rusak')
            ->whereDoesntHave('maintenance', function ($query) {
                $query->whereIn('status', ['pending', 'dalam_proses']);
            })
            ->count();

        // Get sarana yang perlu update
        $saranaPerluUpdate = Sarana::where(function ($query) {
            $query->where('updated_at', '<', Carbon::now()->subMonths(6))
                ->orWhereHas('barang', function ($q) {
                    $q->where('sarana_barang.kondisi', 'rusak');
                });
        })->count();

        // Get barang rusak di sarana
        $barangRusakDiSarana = DB::table('sarana_barang')
            ->where('kondisi', 'rusak')
            ->count();

        // Send notifications if there are issues
        if ($barangRusakPerluMaintenance > 0 || $saranaPerluUpdate > 0 || $barangRusakDiSarana > 0) {
            $messages = [];

            if ($barangRusakPerluMaintenance > 0) {
                $messages[] = "{$barangRusakPerluMaintenance} barang rusak perlu maintenance";
            }

            if ($barangRusakDiSarana > 0) {
                $messages[] = "{$barangRusakDiSarana} barang rusak di sarana perlu perhatian";
            }

            if ($saranaPerluUpdate > 0) {
                $messages[] = "{$saranaPerluUpdate} sarana perlu update";
            }

            $title = '⚠️ Peringatan Sarpras';
            $message = implode('. ', $messages) . '. Silakan cek dashboard untuk detail lebih lanjut.';

            NotificationHelper::sendSarprasAlert(
                $title,
                $message,
                $barangRusakPerluMaintenance > 5 ? 'error' : 'warning'
            );

            $this->info("✅ Notifications sent: {$message}");
        } else {
            $this->info('✅ No notifications needed. All items are in good condition.');
        }

        return 0;
    }
}

