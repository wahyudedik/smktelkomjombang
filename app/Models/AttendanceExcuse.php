<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AttendanceExcuse extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_identity_id',
        'type',
        'date',
        'reason',
        'attachment_path',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    // ─── Relasi ────────────────────────────────────────────

    public function identity(): BelongsTo
    {
        return $this->belongsTo(AttendanceIdentity::class, 'attendance_identity_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ────────────────────────────────────────────

    /**
     * Filter izin/sakit yang masih menunggu persetujuan
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filter izin/sakit yang sudah disetujui
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    /**
     * Filter izin/sakit yang ditolak
     */
    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Filter berdasarkan tanggal
     */
    public function scopeForDate(Builder $query, $date): Builder
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Filter berdasarkan rentang tanggal
     */
    public function scopeDateRange(Builder $query, $start, $end): Builder
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    /**
     * Filter berdasarkan jenis izin
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // ─── Accessors ─────────────────────────────────────────

    /**
     * Label untuk jenis izin
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'izin'   => 'Izin',
            'sakit'  => 'Sakit',
            'cuti'   => 'Cuti',
            'dinas'  => 'Dinas Luar',
            'alpha'  => 'Alpha',
            default  => ucfirst($this->type),
        };
    }

    /**
     * Label untuk status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    /**
     * Badge color untuk status
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }

    /**
     * Nama pengguna (guru/siswa/user)
     */
    public function getNamaAttribute(): string
    {
        $identity = $this->identity;
        return $identity?->user?->name
            ?? $identity?->guru?->nama_lengkap
            ?? $identity?->siswa?->nama_lengkap
            ?? '-';
    }

    // ─── Methods ───────────────────────────────────────────

    /**
     * Approve izin/sakit
     */
    public function approve(int $userId): bool
    {
        return $this->update([
            'status'      => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject izin/sakit
     */
    public function reject(int $userId, ?string $reason = null): bool
    {
        return $this->update([
            'status'           => 'rejected',
            'approved_by'      => $userId,
            'approved_at'      => now(),
            'rejection_reason' => $reason,
        ]);
    }
}
