<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Jadwal Pelajaran') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.jadwal-pelajaran.edit', $jadwalPelajaran) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mata Pelajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Mata Pelajaran</label>
                            <p class="mt-1 text-lg font-semibold">{{ $jadwalPelajaran->mataPelajaran->nama ?? '-' }}</p>
                        </div>

                        <!-- Guru -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Guru Pengajar</label>
                            <p class="mt-1 text-lg font-semibold">{{ $jadwalPelajaran->guru->full_name ?? '-' }}</p>
                            <p class="text-sm text-gray-600">NIP: {{ $jadwalPelajaran->guru->nip ?? '-' }}</p>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kelas</label>
                            <p class="mt-1 text-lg font-semibold">{{ $jadwalPelajaran->kelas->nama ?? '-' }}</p>
                        </div>

                        <!-- Hari -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Hari</label>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $jadwalPelajaran->hari_badge_color }}-100 text-{{ $jadwalPelajaran->hari_badge_color }}-800">
                                    {{ $jadwalPelajaran->hari }}
                                </span>
                            </p>
                        </div>

                        <!-- Waktu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Waktu</label>
                            <p class="mt-1 text-lg font-semibold">{{ $jadwalPelajaran->time_range }}</p>
                            <p class="text-sm text-gray-600">Durasi: {{ $jadwalPelajaran->duration }} menit</p>
                        </div>

                        <!-- Ruang -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Ruang Kelas</label>
                            <p class="mt-1 text-lg">{{ $jadwalPelajaran->ruang ?? '-' }}</p>
                        </div>

                        <!-- Tahun Ajaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tahun Ajaran</label>
                            <p class="mt-1 text-lg">{{ $jadwalPelajaran->tahun_ajaran }}</p>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Semester</label>
                            <p class="mt-1 text-lg">{{ $jadwalPelajaran->semester }}</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $jadwalPelajaran->status_badge_color }}-100 text-{{ $jadwalPelajaran->status_badge_color }}-800">
                                    {{ ucfirst($jadwalPelajaran->status) }}
                                </span>
                            </p>
                        </div>

                        <!-- Created At -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <p class="mt-1 text-sm text-gray-700">
                                {{ $jadwalPelajaran->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <!-- Catatan -->
                        @if ($jadwalPelajaran->catatan)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-500">Catatan</label>
                                <p class="mt-1 text-gray-700">{{ $jadwalPelajaran->catatan }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <form action="{{ route('admin.jadwal-pelajaran.destroy', $jadwalPelajaran) }}" method="POST"
                            data-confirm="Yakin ingin menghapus jadwal ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Hapus Jadwal
                            </button>
                        </form>

                        <a href="{{ route('admin.jadwal-pelajaran.edit', $jadwalPelajaran) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
