<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Izin / Sakit</h1>
                <p class="text-slate-600 mt-1">Formulir pengajuan izin, sakit, cuti, atau dinas luar</p>
            </div>
            <a href="{{ route('admin.absensi.excuses.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.absensi.excuses.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengguna -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Pengguna <span class="text-red-500">*</span></label>
                    <select name="attendance_identity_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                        <option value="">— Pilih Pengguna —</option>
                        @foreach ($identities as $identity)
                            <option value="{{ $identity['id'] }}" {{ old('attendance_identity_id') == $identity['id'] ? 'selected' : '' }}>
                                {{ $identity['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis <span class="text-red-500">*</span></label>
                    <select name="type" class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                        <option value="">— Pilih Jenis —</option>
                        <option value="izin" {{ old('type') === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old('type') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="cuti" {{ old('type') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="dinas" {{ old('type') === 'dinas' ? 'selected' : '' }}>Dinas Luar</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', now()->toDateString()) }}"
                        class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                </div>

                <!-- Alasan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Alasan <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="4" class="mt-1 block w-full rounded-md border-slate-300 text-sm"
                        placeholder="Jelaskan alasan izin/sakit..." required>{{ old('reason') }}</textarea>
                </div>

                <!-- Lampiran -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Lampiran (opsional)</label>
                    <input type="file" name="attachment"
                        class="mt-1 block w-full text-sm text-slate-700 border border-slate-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, atau PDF. Maksimal 5MB.</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.absensi.excuses.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>
