<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Jadwal Pelajaran') }}
            </h2>
            <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.jadwal-pelajaran.update', $jadwalPelajaran) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mata Pelajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran *</label>
                                <select name="mata_pelajaran_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('mata_pelajaran_id') border-red-500 @enderror">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mataPelajaranList as $mapel)
                                        <option value="{{ $mapel->id }}"
                                            {{ old('mata_pelajaran_id', $jadwalPelajaran->mata_pelajaran_id) == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mata_pelajaran_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guru -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guru Pengajar *</label>
                                <select name="guru_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('guru_id') border-red-500 @enderror">
                                    <option value="">Pilih Guru</option>
                                    @foreach ($guruList as $guru)
                                        <option value="{{ $guru->id }}"
                                            {{ old('guru_id', $jadwalPelajaran->guru_id) == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->full_name }} ({{ $guru->nip }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('guru_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas *</label>
                                <select name="kelas_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kelas_id') border-red-500 @enderror">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ old('kelas_id', $jadwalPelajaran->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                            {{ $kelas->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hari -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hari *</label>
                                <select name="hari" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('hari') border-red-500 @enderror">
                                    <option value="">Pilih Hari</option>
                                    @foreach ($hariList as $hari)
                                        <option value="{{ $hari }}"
                                            {{ old('hari', $jadwalPelajaran->hari) == $hari ? 'selected' : '' }}>
                                            {{ $hari }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hari')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai *</label>
                                <input type="time" name="jam_mulai"
                                    value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwalPelajaran->jam_mulai)->format('H:i')) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_mulai') border-red-500 @enderror">
                                @error('jam_mulai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Selesai -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai *</label>
                                <input type="time" name="jam_selesai"
                                    value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwalPelajaran->jam_selesai)->format('H:i')) }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_selesai') border-red-500 @enderror">
                                @error('jam_selesai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ruang -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ruang Kelas</label>
                                <input type="text" name="ruang"
                                    value="{{ old('ruang', $jadwalPelajaran->ruang) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ruang') border-red-500 @enderror"
                                    placeholder="Contoh: Lab Komputer">
                                @error('ruang')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tahun Ajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran *</label>
                                <input type="text" name="tahun_ajaran"
                                    value="{{ old('tahun_ajaran', $jadwalPelajaran->tahun_ajaran) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tahun_ajaran') border-red-500 @enderror"
                                    placeholder="2024/2025">
                                @error('tahun_ajaran')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semester -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Semester *</label>
                                <select name="semester" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('semester') border-red-500 @enderror">
                                    @foreach ($semesterList as $semester)
                                        <option value="{{ $semester }}"
                                            {{ old('semester', $jadwalPelajaran->semester) == $semester ? 'selected' : '' }}>
                                            {{ $semester }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('semester')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                                    <option value="aktif"
                                        {{ old('status', $jadwalPelajaran->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="nonaktif"
                                        {{ old('status', $jadwalPelajaran->status) == 'nonaktif' ? 'selected' : '' }}>
                                        Non-Aktif</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Catatan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea name="catatan" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('catatan') border-red-500 @enderror"
                                    placeholder="Catatan tambahan...">{{ old('catatan', $jadwalPelajaran->catatan) }}</textarea>
                                @error('catatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-2">
                            <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
