<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Report Absensi</h1>
                <p class="text-slate-600 mt-1">Analisis dan laporan absensi per periode</p>
            </div>
            <a href="{{ route('admin.absensi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Report Harian -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Harian</h2>
                <form method="GET" action="{{ route('admin.absensi.report.daily') }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Tanggal</label>
                        <input type="date" name="date" value="{{ now()->toDateString() }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>

            <!-- Report Mingguan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Mingguan</h2>
                <form method="GET" action="{{ route('admin.absensi.report.weekly') }}">
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
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>

            <!-- Report Bulanan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Bulanan</h2>
                <form method="GET" action="{{ route('admin.absensi.report.monthly') }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Bulan</label>
                        <input type="month" name="month" value="{{ now()->format('Y-m') }}" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>

            <!-- Report Keterlambatan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Report Keterlambatan</h2>
                <form method="GET" action="{{ route('admin.absensi.report.latecomers') }}">
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
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700">Batas Jam (Contoh: 07:30)</label>
                        <input type="time" name="threshold_time" value="07:30" 
                            class="mt-1 block w-full rounded-md border-slate-300" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Lihat Report</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
