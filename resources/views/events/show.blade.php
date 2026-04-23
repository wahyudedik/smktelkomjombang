<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Kegiatan</h1>
                <p class="text-slate-600 mt-1">{{ $event->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @can('events.edit')
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-body">

                <!-- Gambar -->
                @if ($event->image)
                    <div class="mb-6">
                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}"
                            class="w-full max-h-80 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif

                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="font-semibold text-slate-800">
                            {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($event->date)->format('H:i') }} WIB</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Kategori</p>
                        <p class="font-semibold text-slate-800">{{ $event->category ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Status</p>
                        @if ($event->status === 'active')
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Aktif</span>
                        @elseif ($event->status === 'inactive')
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Nonaktif</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                        @endif
                    </div>
                </div>

                <!-- Judul -->
                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ $event->title }}</h2>

                <!-- Deskripsi -->
                @if ($event->description)
                    <div class="prose max-w-none text-slate-700 mb-6">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                @else
                    <p class="text-slate-400 italic mb-6">Tidak ada deskripsi.</p>
                @endif

                <!-- Meta -->
                <div class="border-t border-slate-200 pt-4 flex items-center justify-between text-xs text-slate-400">
                    <span>Dibuat: {{ $event->created_at->format('d M Y H:i') }}</span>
                    <span>Diperbarui: {{ $event->updated_at->format('d M Y H:i') }}</span>
                </div>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                ← Kembali ke Daftar
            </a>
            <div class="flex space-x-3">
                @can('events.edit')
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit Kegiatan
                    </a>
                @endcan
                @can('events.delete')
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
