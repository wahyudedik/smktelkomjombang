<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Detail Surat Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Detail Surat #{{ $letter->letter_number }}</h2>
                        <div class="space-x-2">
                            <a href="{{ route('admin.letters.out.index') }}"
                                class="text-slate-500 hover:text-slate-700 px-3 py-2">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 p-4 rounded-lg">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Informasi Surat</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="block text-xs text-slate-400">Nomor Surat</span>
                                    <span class="block text-slate-800 font-medium">{{ $letter->letter_number }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Tanggal</span>
                                    <span
                                        class="block text-slate-800 font-medium">{{ $letter->letter_date->translatedFormat('d F Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Format Surat</span>
                                    <span class="block text-slate-800">{{ $letter->format->name ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Dibuat Oleh</span>
                                    <span class="block text-slate-800">{{ $letter->creator->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-lg">
                            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Tujuan & Isi</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="block text-xs text-slate-400">Kepada</span>
                                    <span class="block text-slate-800 font-medium">{{ $letter->recipient }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Perihal</span>
                                    <span class="block text-slate-800 font-medium">{{ $letter->subject }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-slate-400">Ringkasan/Isi</span>
                                    <p class="text-slate-800 text-sm whitespace-pre-line">
                                        {{ $letter->description ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase mb-4">Riwayat Aktivitas</h3>
                        <div class="space-y-4">
                            @foreach ($letter->activityLogs()->latest()->get() as $log)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-blue-500 mr-3"></div>
                                    <div>
                                        <p class="text-sm text-slate-800">{{ $log->details }}</p>
                                        <p class="text-xs text-slate-400">
                                            Oleh {{ $log->user->name ?? 'System' }} &bull;
                                            {{ $log->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
