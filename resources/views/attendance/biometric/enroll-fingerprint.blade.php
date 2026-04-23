<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Enroll Fingerprint</h1>
                <p class="text-slate-600 mt-1">{{ $name }} (PIN: {{ $identity->device_pin }})</p>
            </div>
            <a href="{{ route('admin.absensi.biometric.index') }}" class="btn btn-secondary">Kembali</a>
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
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Instruksi:</strong> Pilih device dan jari yang akan di-enroll, kemudian klik "Mulai Enrollment". 
                    Setelah itu, silakan scan jari di device sampai enrollment selesai.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.absensi.biometric.fingerprint.store', $identity) }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Device</label>
                    <select name="device_id" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="">-- Pilih Device --</option>
                        @foreach ($devices as $d)
                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->serial_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Jari yang Akan Di-Enroll</label>
                    <select name="finger_index" class="mt-1 block w-full rounded-md border-slate-300" required>
                        <option value="">-- Pilih Jari --</option>
                        <option value="0">Jari 1 (Ibu jari kiri)</option>
                        <option value="1">Jari 2 (Telunjuk kiri)</option>
                        <option value="2">Jari 3 (Jari tengah kiri)</option>
                        <option value="3">Jari 4 (Jari manis kiri)</option>
                        <option value="4">Jari 5 (Kelingking kiri)</option>
                        <option value="5">Jari 6 (Ibu jari kanan)</option>
                        <option value="6">Jari 7 (Telunjuk kanan)</option>
                        <option value="7">Jari 8 (Jari tengah kanan)</option>
                        <option value="8">Jari 9 (Jari manis kanan)</option>
                        <option value="9">Jari 10 (Kelingking kanan)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="btn btn-primary">Mulai Enrollment</button>
                    <a href="{{ route('admin.absensi.biometric.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-slate-50 rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-3">Tips Enrollment Fingerprint</h3>
            <ul class="list-disc pl-5 space-y-2 text-sm text-slate-700">
                <li>Pastikan jari bersih dan kering</li>
                <li>Letakkan jari di sensor dengan tekanan yang konsisten</li>
                <li>Scan jari 3-4 kali untuk hasil yang lebih baik</li>
                <li>Disarankan enroll 2-4 jari berbeda untuk backup</li>
                <li>Pastikan pencahayaan cukup di area sensor</li>
            </ul>
        </div>
    </div>
</x-app-layout>
