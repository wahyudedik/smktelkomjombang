<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Tambah Format Surat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow sm:rounded-lg space-y-6">
                <form method="POST" action="{{ route('admin.letters.formats.store') }}" class="space-y-6" id="format-form">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-slate-700">Jenis Surat</label>
                            <select id="type" name="type"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="out" @selected(old('type') === 'out')>Keluar</option>
                                <option value="in" @selected(old('type') === 'in')>Masuk</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>
                        <div>
                            <label for="period_mode" class="block text-sm font-medium text-slate-700">Reset
                                Counter</label>
                            <select id="period_mode" name="period_mode"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="year" @selected(old('period_mode') === 'year')>Tahunan</option>
                                <option value="month" @selected(old('period_mode') === 'month')>Bulanan</option>
                                <option value="all" @selected(old('period_mode') === 'all')>Tidak Reset</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('period_mode')" />
                        </div>
                        <div>
                            <label for="counter_scope" class="block text-sm font-medium text-slate-700">Scope
                                Counter</label>
                            <select id="counter_scope" name="counter_scope"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="global" @selected(old('counter_scope') === 'global')>Global</option>
                                <option value="unit" @selected(old('counter_scope') === 'unit')>Per Unit</option>
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Global: nomor berurutan untuk semua unit. Per Unit:
                                nomor terpisah per kode unit.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('counter_scope')" />
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Nama Format</label>
                            <input id="name" name="name" type="text"
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('name') }}" required placeholder="Contoh: Surat Keputusan" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea id="description" name="description"
                            class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-slate-700">Segmen Format</label>
                            <button type="button" id="add-segment" class="text-sm text-blue-600 hover:underline">Tambah
                                Segmen</button>
                        </div>
                        <div class="mt-3 rounded-md border border-blue-100 bg-blue-50 p-3 text-sm text-blue-900">
                            <div class="font-semibold mb-1">Tips menyusun segmen</div>
                            <ul class="space-y-1 text-xs text-blue-900 list-disc list-inside">
                                <li><span class="font-semibold">sequence</span>: nomor urut (padding = jumlah digit,
                                    contoh 3 → 001)</li>
                                <li><span class="font-semibold">text</span>: teks statis seperti <code
                                        class="px-1 bg-white rounded">/SK/</code> atau <code
                                        class="px-1 bg-white rounded">/</code></li>
                                <li><span class="font-semibold">unit_code</span>: kode unit dari profil user (wajib jika
                                    counter per unit)</li>
                                <li><span class="font-semibold">day</span>: tanggal (01-31)</li>
                                <li><span class="font-semibold">month_roman</span>: bulan Romawi (I-XII)</li>
                                <li><span class="font-semibold">month_number</span>: bulan angka (01-12)</li>
                                <li><span class="font-semibold">year</span>: tahun angka (2026)</li>
                                <li><span class="font-semibold">year_roman</span>: tahun Romawi (MMXXVI)</li>
                            </ul>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Preview: <span id="format-preview"
                                class="font-semibold text-slate-800 bg-slate-100 px-2 py-1 rounded">-</span></p>
                        <div class="mt-2 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-left text-slate-600 bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2 rounded-tl-lg">Tipe Segmen</th>
                                        <th class="px-4 py-2">Nilai (Teks)</th>
                                        <th class="px-4 py-2">Padding (Digit)</th>
                                        <th class="px-4 py-2 rounded-tr-lg text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="segments-body" class="divide-y divide-slate-200 bg-white">
                                    <!-- Segments will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Simpan Format
                        </button>
                        <a href="{{ route('admin.letters.formats.index') }}"
                            class="text-sm text-slate-600 hover:text-slate-800">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.getElementById('segments-body');
            const addButton = document.getElementById('add-segment');
            const preview = document.getElementById('format-preview');

            const monthRoman = (month) => {
                const map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                return map[month - 1] ?? '';
            };

            const toRoman = (num) => {
                const map = [
                    [1000, 'M'],
                    [900, 'CM'],
                    [500, 'D'],
                    [400, 'CD'],
                    [100, 'C'],
                    [90, 'XC'],
                    [50, 'L'],
                    [40, 'XL'],
                    [10, 'X'],
                    [9, 'IX'],
                    [5, 'V'],
                    [4, 'IV'],
                    [1, 'I'],
                ];
                let result = '';
                let value = num;
                map.forEach(([n, r]) => {
                    while (value >= n) {
                        result += r;
                        value -= n;
                    }
                });
                return result;
            };

            const updatePreview = () => {
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = String(now.getFullYear());

                const rows = Array.from(body.querySelectorAll('tr'));
                if (rows.length === 0) {
                    preview.textContent = '-';
                    return;
                }

                const parts = rows.map((row) => {
                    const kind = row.querySelector('.segment-kind')?.value;
                    const value = row.querySelector('.segment-value')?.value || '';
                    const padding = parseInt(row.querySelector('.segment-padding')?.value || '0', 10);

                    switch (kind) {
                        case 'sequence':
                            return String(1).padStart(padding || 3, '0');
                        case 'text':
                            return value;
                        case 'unit_code':
                            return 'UNIT';
                        case 'day':
                            return day;
                        case 'month_roman':
                            return monthRoman(parseInt(month, 10));
                        case 'month_number':
                            return month;
                        case 'year':
                            return year;
                        case 'year_roman':
                            return toRoman(parseInt(year, 10));
                        default:
                            return '';
                    }
                }).filter(Boolean);

                preview.textContent = parts.length ? parts.join('') : '-';
            };

            const addRow = (index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">
                        <select name="segments[${index}][kind]" class="w-full rounded-md border-slate-300 segment-kind text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="sequence">Nomor Urut (Sequence)</option>
                            <option value="text">Teks Statis (Text)</option>
                            <option value="unit_code">Kode Unit</option>
                            <option value="day">Tanggal (01-31)</option>
                            <option value="month_roman">Bulan Romawi (I-XII)</option>
                            <option value="month_number">Bulan Angka (01-12)</option>
                            <option value="year">Tahun (2025)</option>
                            <option value="year_roman">Tahun Romawi</option>
                        </select>
                    </td>
                    <td class="px-4 py-2">
                        <input name="segments[${index}][value]" type="text" class="w-full rounded-md border-slate-300 segment-value text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Hanya untuk Teks" />
                    </td>
                    <td class="px-4 py-2">
                        <input name="segments[${index}][padding]" type="number" min="0" max="10" class="w-full rounded-md border-slate-300 segment-padding text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Untuk Sequence" />
                    </td>
                    <td class="px-4 py-2 text-right">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-segment">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                body.appendChild(row);

                // Add event listeners for the new row
                row.querySelector('.remove-segment').addEventListener('click', () => {
                    row.remove();
                    updatePreview();
                });

                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.addEventListener('input', updatePreview);
                    input.addEventListener('change', updatePreview);
                });
            };

            addButton.addEventListener('click', () => {
                const index = new Date().getTime(); // Unique ID using timestamp
                addRow(index);
                updatePreview();
            });

            // Initial rows (optional, maybe 3 empty rows to start)
            // addRow(0);

            updatePreview();
        });
    </script>
</x-app-layout>
