<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Izin / Sakit</h1>
                <p class="text-slate-600 mt-1">Ubah data izin untuk {{ $excuse->nama }}</p>
            </div>
            <a href="{{ route('admin.absensi.excuses.show', $excuse) }}" class="btn btn-secondary">Kembali</a>
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

        <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg text-sm">
            ⚠️ Mengubah data akan mereset status persetujuan ke "Menunggu".
        </div>

        <form method="POST" action="{{ route('admin.absensi.excuses.update', $excuse) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-slate-200 p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pengguna -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Pengguna <span class="text-red-500">*</span></label>
                    <select name="attendance_identity_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                        <option value="">— Pilih Pengguna —</option>
                        @foreach ($identities as $identity)
                            <option value="{{ $identity['id'] }}" {{ old('attendance_identity_id', $excuse->attendance_identity_id) == $identity['id'] ? 'selected' : '' }}>
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
                        <option value="izin" {{ old('type', $excuse->type) === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old('type', $excuse->type) === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="cuti" {{ old('type', $excuse->type) === 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="dinas" {{ old('type', $excuse->type) === 'dinas' ? 'selected' : '' }}>Dinas Luar</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', $excuse->date->toDateString()) }}"
                        class="mt-1 block w-full rounded-md border-slate-300 text-sm" required>
                </div>

                <!-- Alasan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Alasan <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="4" class="mt-1 block w-full rounded-md border-slate-300 text-sm"
                        placeholder="Jelaskan alasan izin/sakit..." required>{{ old('reason', $excuse->reason) }}</textarea>
                </div>

                <!-- Lampiran -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Lampiran Baru (opsional)</label>
                    @if ($excuse->attachment_path)
                        <div class="mt-1 mb-2 text-sm text-slate-600">
                            File saat ini: <a href="{{ Storage::disk('public')->url($excuse->attachment_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat lampiran</a>
                        </div>
                    @endif
                    <input type="file" name="attachment"
                        class="mt-1 block w-full text-sm text-slate-700 border border-slate-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, atau PDF. Maksimal 5MB. Kosongkan jika tidak ingin mengubah.</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.absensi.excuses.show', $excuse) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
