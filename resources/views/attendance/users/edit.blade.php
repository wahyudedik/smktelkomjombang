<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit User Absensi</h1>
                <p class="text-slate-600 mt-1">PIN: {{ $identity->device_pin }}</p>
            </div>
            <a href="{{ route('admin.absensi.users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <form method="POST" action="{{ route('admin.absensi.users.update', $identity) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis</label>
                    <input type="text" value="{{ $identity->kind }}" disabled class="mt-1 block w-full rounded-md border-slate-300 bg-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama</label>
                    @php
                        $nama = $identity->user?->name ?? ($identity->guru?->nama_lengkap ?? ($identity->siswa?->nama_lengkap ?? '-'));
                    @endphp
                    <input type="text" value="{{ $nama }}" disabled class="mt-1 block w-full rounded-md border-slate-300 bg-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">PIN Device</label>
                    <input type="text" name="device_pin" value="{{ $identity->device_pin }}" 
                        class="mt-1 block w-full rounded-md border-slate-300" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="is_active" class="mt-1 block w-full rounded-md border-slate-300">
                        <option value="1" @selected($identity->is_active)>Aktif</option>
                        <option value="0" @selected(!$identity->is_active)>Nonaktif</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.absensi.users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
