<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fixes photo_path values that were stored with an incorrect 'storage/' prefix.
     *
     * Bug: GuestBookController::publicStore() stored photo_path as
     * 'storage/guest-photos/...' but the model accessor getPhotoUrlAttribute()
     * already prepends 'storage/' via asset('storage/' . $this->photo_path),
     * resulting in double-prefixed URLs like 'storage/storage/guest-photos/...'.
     *
     * Admin store() was unaffected because it stores paths without the prefix.
     */
    public function up(): void
    {
        // Fix photo_path: strip leading 'storage/' if present
        DB::table('guest_books')
            ->where('photo_path', 'like', 'storage/%')
            ->update([
                'photo_path' => DB::raw("SUBSTRING(photo_path, 9)"),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not safe to reverse — original prefix state is unknown per row.
        // This is a one-way data fix.
    }
};
