<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Normalizes signature_path in guest_books table to be relative (without 'storage/' prefix),
     * matching the same convention used by photo_path.
     *
     * Before: signature_path = 'storage/guest-books/signatures/BT-20260921-0001_signature.png'
     * After:  signature_path = 'guest-books/signatures/BT-20260921-0001_signature.png'
     *
     * This migration is idempotent — safe to run multiple times.
     */
    public function up(): void
    {
        // Only update rows that still have the 'storage/' prefix
        $affected = DB::table('guest_books')
            ->where('signature_path', 'like', 'storage/%')
            ->update([
                'signature_path' => DB::raw("SUBSTRING(signature_path, 9)"),
            ]);

        // Log for reference (optional, won't fail if logging is disabled)
        if ($affected > 0) {
            Log::info("normalize_guest_book_paths: Fixed {$affected} signature_path(s) — removed 'storage/' prefix.");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not safe to reverse — original prefix state is unknown per row.
        // This is a one-way data normalization.
    }
};
