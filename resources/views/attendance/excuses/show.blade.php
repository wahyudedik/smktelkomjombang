<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Izin / Sakit</h1>
                <p class="text-slate-600 mt-1">{{ $excuse->nama }} — {{ $excuse->date->format('d F Y') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.absensi.excuses.index') }}" class="btn btn-secondary">Kembali</a>
                @if ($excuse->status === 'pending')
                    <a href="{{ route('admin.absensi.excuses.edit', $excuse) }}" class="btn btn-primary">Edit</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Notifikasi -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Detail Card -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Informasi Izin</h2>
                    <span class="px-3 py-1 bg-{{ $excuse->status_color }}-100 text-{{ $excuse->status_color }}-800 rounded-full text-sm font-medium">
                        {{ $excuse->status_label }}
                    </span>
                </div>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Nama</dt>
                        <dd class="mt-1 text-sm text-slate-900 font-medium">{{ $excuse->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Jenis</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $excuse->type_label }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Tanggal</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $excuse->date->format('d F Y') }} ({{ $excuse->date->translatedFormat('l') }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Dibuat Oleh</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $excuse->creator?->name ?? '-' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-slate-500">Alasan</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $excuse->reason }}</dd>
                    </div>
                    @if ($excuse->attachment_path)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-slate-500">Lampiran</dt>
                            <dd class="mt-1">
                                <a href="{{ Storage::disk('public')->url($excuse->attachment_path) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    📎 Lihat Lampiran
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Info Persetujuan -->
        @if ($excuse->status !== 'pending')
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Informasi Persetujuan</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Disetujui Oleh</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $excuse->approvedBy?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-slate-500">Waktu</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $excuse->approved_at?->format('d F Y, H:i') ?? '-' }}</dd>
                        </div>
                        @if ($excuse->rejection_reason)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Alasan Penolakan</dt>
                                <dd class="mt-1 text-sm text-red-600">{{ $excuse->rejection_reason }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        @endif

        <!-- Actions -->
        @if ($excuse->status === 'pending')
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Aksi</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
                        <!-- Approve -->
                        <form method="POST" action="{{ route('admin.absensi.excuses.approve', $excuse) }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white"
                                onclick="return confirm('Setujui izin ini?')">
                                ✅ Setujui
                            </button>
                        </form>

                        <!-- Reject -->
                        <div x-data="{ showReject: false }" class="flex-1">
                            <button @click="showReject = !showReject" class="btn bg-red-600 hover:bg-red-700 text-white">
                                ❌ Tolak
                            </button>
                            <div x-show="showReject" x-cloak class="mt-4">
                                <form method="POST" action="{{ route('admin.absensi.excuses.reject', $excuse) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-slate-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                                        <textarea name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-slate-300 text-sm"
                                            placeholder="Jelaskan alasan penolakan..." required></textarea>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white text-sm"
                                            onclick="return confirm('Tolak izin ini?')">Konfirmasi Tolak</button>
                                        <button type="button" @click="showReject = false" class="btn btn-secondary text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
