<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Buat Pemilihan Baru</h1>
                <p class="text-slate-600 mt-1">Atur periode, kelas pemilih, dan visibilitas hasil pemilihan</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.osis.elections.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 p-8">
            <form method="POST" action="{{ route('admin.osis.elections.store') }}" class="space-y-6">
                @csrf

                <!-- Informasi Pemilihan -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Pemilihan</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="form-label">Judul Pemilihan</label>
                            <input type="text" id="title" name="title"
                                value="{{ old('title') }}"
                                class="form-input @error('title') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Contoh: Pemilihan OSIS 2026/2027" required>
                            @error('title')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="form-label">Deskripsi <span
                                    class="text-slate-400">(opsional)</span></label>
                            <textarea id="description" name="description" rows="3"
                                class="form-input @error('description') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="Deskripsi singkat pemilihan">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Periode & Batasan -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Periode & Batasan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="form-label">Mulai</label>
                            <input type="datetime-local" id="start_date" name="start_date"
                                value="{{ old('start_date') }}"
                                class="form-input @error('start_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                            @error('start_date')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="form-label">Berakhir</label>
                            <input type="datetime-local" id="end_date" name="end_date"
                                value="{{ old('end_date') }}"
                                class="form-input @error('end_date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                            @error('end_date')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="max_votes_per_student" class="form-label">Maksimal Suara per
                                Pemilih</label>
                            <input type="number" id="max_votes_per_student" name="max_votes_per_student"
                                value="{{ old('max_votes_per_student', 1) }}" min="1" max="10"
                                class="form-input @error('max_votes_per_student') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                            @error('max_votes_per_student')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kelas yang Diizinkan -->
                <div class="border-b border-slate-200 pb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-1">Kelas yang Diizinkan Memilih</h3>
                    <p class="text-sm text-slate-600 mb-4">Kosongkan semua centang agar <strong>semua
                            kelas</strong> boleh memilih.</p>
                    @error('allowed_classes')
                        <p class="form-error mb-2">{{ $message }}</p>
                    @enderror
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach ($availableClasses as $kelas)
                            <label class="flex items-center space-x-2 p-2 rounded-lg hover:bg-slate-50">
                                <input type="checkbox" name="allowed_classes[]"
                                    value="{{ $kelas }}"
                                    {{ in_array($kelas, old('allowed_classes', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <span class="text-sm text-slate-700">{{ $kelas }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label for="custom_classes" class="form-label">Kelas Lain (opsional, pisah
                            koma)</label>
                        <input type="text" id="custom_classes" name="custom_classes"
                            value="{{ old('custom_classes') }}"
                            class="form-input @error('custom_classes') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Contoh: X TKJ 1, XI TKJ 2">
                        @error('custom_classes')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pengaturan Hasil & Status -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Pengaturan Hasil & Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="hidden" name="show_results" value="0">
                            <input type="checkbox" id="show_results" name="show_results" value="1"
                                {{ old('show_results', true) ? 'checked' : '' }}
                                class="h-4 w-4 mt-0.5 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <div class="ml-2">
                                <label for="show_results" class="text-sm text-slate-700 font-medium">Tampilkan
                                    hasil selama pemilihan berjalan</label>
                                <p class="text-xs text-slate-500 mt-0.5">Jika dimatikan, perolehan per calon
                                    disembunyikan sampai pemilihan ditutup (hanya ringkasan partisipasi yang
                                    tampil).</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                {{ old('is_active', false) ? 'checked' : '' }}
                                class="h-4 w-4 mt-0.5 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                            <div class="ml-2">
                                <label for="is_active" class="text-sm text-slate-700 font-medium">Aktifkan
                                    segera</label>
                                <p class="text-xs text-slate-500 mt-0.5">Pemilihan aktif akan langsung menerima
                                    suara (setelah waktu mulai). Election lain otomatis dinonaktifkan — hanya
                                    boleh ada satu pemilihan aktif.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('admin.osis.elections.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Pemilihan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
