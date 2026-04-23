<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Export Rekap Absensi</h1>
                <p class="text-slate-600 mt-1">Export ke Excel untuk berbagai format</p>
            </div>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Export Harian -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Harian</h2>
                <form method="POST" action="{{ route('admin.absensi.export.daily') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" name="date" value="{{ now()->toDateString() }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Download Excel</button>
                </form>
            </div>

            <!-- Export Periode -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Periode</h2>
                <form method="POST" action="{{ route('admin.absensi.export.period') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ now()->subDays(7)->toDateString() }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ now()->toDateString() }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Grup Berdasarkan</label>
                        <select name="group_by" class="mt-1 block w-full rounded-md border-slate-300">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Download Excel</button>
                </form>
            </div>

            <!-- Export Summary -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export Summary</h2>
                <form method="POST" action="{{ route('admin.absensi.export.summary') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ now()->subDays(30)->toDateString() }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ now()->toDateString() }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <p class="text-sm text-slate-600 mb-4">Ringkasan per user dengan statistik kehadiran</p>
                    <button type="submit" class="btn btn-primary w-full">Download Excel</button>
                </form>
            </div>

            <!-- Export User Detail -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Export User Detail</h2>
                <p class="text-sm text-slate-600 mb-4">Pilih user di halaman User Management, kemudian klik "Export Detail"</p>
                <a href="{{ route('admin.absensi.users.index') }}" class="btn btn-secondary w-full">Ke User Management</a>
            </div>
        </div>
    </div>
</x-app-layout>
