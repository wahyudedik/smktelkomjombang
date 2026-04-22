<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kalender Jadwal Pelajaran') }}
            </h2>
            <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6 bg-white p-4 rounded-lg shadow">
                <form method="GET" action="{{ route('admin.jadwal-pelajaran.calendar') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="kelas_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="2024/2025">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Calendar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        @php
                            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $colors = ['blue', 'indigo', 'green', 'yellow', 'orange', 'red'];
                        @endphp

                        @foreach ($hariList as $index => $hari)
                            <div class="border rounded-lg">
                                <div
                                    class="bg-{{ $colors[$index] }}-500 text-white p-3 font-bold text-center rounded-t-lg">
                                    {{ $hari }}
                                </div>
                                <div class="p-2 space-y-2">
                                    @if (isset($jadwals[$hari]))
                                        @foreach ($jadwals[$hari] as $jadwal)
                                            <div class="bg-gray-50 p-2 rounded border-l-4 border-{{ $colors[$index] }}-500 hover:bg-gray-100 cursor-pointer"
                                                onclick="window.location='{{ route('admin.jadwal-pelajaran.show', $jadwal) }}'">
                                                <div class="text-xs font-semibold text-gray-700">
                                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $jadwal->mataPelajaran->nama ?? '-' }}
                                                </div>
                                                <div class="text-xs text-gray-600">
                                                    {{ $jadwal->guru->nama_lengkap ?? '-' }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $jadwal->kelas->nama ?? '-' }} • {{ $jadwal->ruang ?? '-' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-gray-400 text-sm py-4">
                                            Tidak ada jadwal
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
