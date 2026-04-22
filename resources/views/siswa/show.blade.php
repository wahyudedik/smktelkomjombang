<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.detail_data_siswa') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.siswa.edit', $siswa) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.edit') }}
                </a>
                <a href="{{ route('admin.siswa.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Student Photo and Basic Info -->
                    <div class="flex flex-col lg:flex-row gap-6 mb-8">
                        <div class="lg:w-1/3">
                            @if ($siswa->foto)
                                <img src="{{ $siswa->photo_url }}" alt="{{ $siswa->nama_lengkap }}"
                                    class="w-full h-64 object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 text-lg">No Photo</span>
                                </div>
                            @endif
                        </div>
                        <div class="lg:w-2/3">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $siswa->nama_lengkap }}</h1>
                            <div class="flex items-center space-x-4 mb-4">
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium {{ $siswa->status_badge_color }}">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                                <span class="text-gray-600">{{ $siswa->gender_display }}</span>
                                <span class="text-gray-600">{{ $siswa->age }} tahun</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">NIS</p>
                                    <p class="font-medium">{{ $siswa->nis }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">NISN</p>
                                    <p class="font-medium">{{ $siswa->nisn }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Kelas</p>
                                    <p class="font-medium">{{ $siswa->kelas ?? 'Belum ditentukan' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Jurusan</p>
                                    <p class="font-medium">{{ $siswa->jurusan ?? 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Personal</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Lahir</p>
                                <p class="font-medium">{{ $siswa->tanggal_lahir->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tempat Lahir</p>
                                <p class="font-medium">{{ $siswa->tempat_lahir }}</p>
                            </div>
                            <div class="lg:col-span-2">
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="font-medium">{{ $siswa->alamat }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">No. Telepon</p>
                                <p class="font-medium">{{ $siswa->no_telepon ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">No. WhatsApp</p>
                                <p class="font-medium">{{ $siswa->no_wa ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $siswa->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Akademik</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600">Tahun Masuk</p>
                                <p class="font-medium">{{ $siswa->tahun_masuk }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tahun Lulus</p>
                                <p class="font-medium">{{ $siswa->tahun_lulus ?? 'Belum lulus' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lama Belajar</p>
                                <p class="font-medium">{{ $siswa->years_of_study }} tahun</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tahun Akademik</p>
                                <p class="font-medium">{{ $siswa->academic_year }}</p>
                            </div>
                            @if ($siswa->ekstrakurikuler && count($siswa->ekstrakurikuler) > 0)
                                <div class="lg:col-span-2">
                                    <p class="text-sm text-gray-600">Ekstrakurikuler</p>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @foreach ($siswa->ekstrakurikuler as $eks)
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 text-sm rounded">{{ $eks }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Parent Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Orang Tua</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Ayah -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-800 mb-3">Ayah</h4>
                                <div class="space-y-2">
                                    <div>
                                        <p class="text-sm text-gray-600">Nama</p>
                                        <p class="font-medium">{{ $siswa->nama_ayah ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Pekerjaan</p>
                                        <p class="font-medium">{{ $siswa->pekerjaan_ayah ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Ibu -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-800 mb-3">Ibu</h4>
                                <div class="space-y-2">
                                    <div>
                                        <p class="text-sm text-gray-600">Nama</p>
                                        <p class="font-medium">{{ $siswa->nama_ibu ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Pekerjaan</p>
                                        <p class="font-medium">{{ $siswa->pekerjaan_ibu ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak Orang Tua -->
                            <div class="lg:col-span-2">
                                <h4 class="text-lg font-medium text-gray-800 mb-3">Kontak Orang Tua</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">No. Telepon</p>
                                        <p class="font-medium">{{ $siswa->no_telepon_ortu ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Alamat</p>
                                        <p class="font-medium">{{ $siswa->alamat_ortu ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @if ($siswa->prestasi)
                                <div>
                                    <p class="text-sm text-gray-600">Prestasi</p>
                                    <div class="mt-1 p-3 bg-yellow-50 rounded-md">
                                        <p class="text-sm text-gray-800">{{ $siswa->prestasi }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($siswa->catatan)
                                <div>
                                    <p class="text-sm text-gray-600">Catatan</p>
                                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                        <p class="text-sm text-gray-800">{{ $siswa->catatan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Account Information -->
                    @if ($siswa->user)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">User Account</h3>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Nama User</p>
                                        <p class="font-medium">{{ $siswa->user->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Email User</p>
                                        <p class="font-medium">{{ $siswa->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Roles</p>
                                        <p class="font-medium">
                                            @if($siswa->user && $siswa->user->roles->count() > 0)
                                                @foreach($siswa->user->roles as $role)
                                                    <span class="badge badge-success">{{ $role->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-gray-500">No roles</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Status User</p>
                                        <p class="font-medium">
                                            @if ($siswa->user->email_verified_at)
                                                <span class="text-green-600">Verified</span>
                                            @else
                                                <span class="text-red-600">Unverified</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Academic Performance Summary -->
                    @if ($siswa->nilai_akademik && count($siswa->nilai_akademik) > 0)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Prestasi Akademik</h3>
                            <div class="bg-green-50 p-4 rounded-lg">
                                @php
                                    $summary = $siswa->academic_summary;
                                @endphp
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-green-600">{{ $summary['average'] }}</p>
                                        <p class="text-sm text-gray-600">Rata-rata Nilai</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-blue-600">{{ $summary['highest'] }}</p>
                                        <p class="text-sm text-gray-600">Nilai Tertinggi</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-purple-600">{{ $summary['subjects'] }}</p>
                                        <p class="text-sm text-gray-600">Mata Pelajaran</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <a href="{{ route('admin.siswa.edit', $siswa) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Data
                        </a>
                        <form method="POST" action="{{ route('admin.siswa.destroy', $siswa) }}" class="inline"
                            data-confirm="Apakah Anda yakin ingin menghapus data siswa ini?">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
