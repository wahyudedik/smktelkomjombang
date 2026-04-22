<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('common.detail_data_guru') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.guru.edit', $guru) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('common.edit') }}
                </a>
                <a href="{{ route('admin.guru.index') }}"
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

                    <!-- Profile Header -->
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6 mb-8">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            @if ($guru->foto)
                                <img class="h-32 w-32 rounded-full object-cover" src="{{ $guru->photo_url }}"
                                    alt="{{ $guru->nama_lengkap }}">
                            @else
                                <div class="h-32 w-32 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span
                                        class="text-gray-600 text-4xl font-medium">{{ substr($guru->nama_lengkap, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Basic Info -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $guru->full_name }}</h1>
                            <p class="text-lg text-gray-600">{{ $guru->jabatan ?? 'Guru' }}</p>
                            <p class="text-sm text-gray-500">NIP: {{ $guru->nip }}</p>

                            <!-- Status Badges -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if ($guru->status_badge_color === 'green') bg-green-100 text-green-800
                                    @elseif($guru->status_badge_color === 'red') bg-red-100 text-red-800
                                    @elseif($guru->status_badge_color === 'blue') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $guru->status_aktif)) }}
                                </span>

                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if ($guru->employment_badge_color === 'green') bg-green-100 text-green-800
                                    @elseif($guru->employment_badge_color === 'blue') bg-blue-100 text-blue-800
                                    @elseif($guru->employment_badge_color === 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($guru->employment_badge_color === 'orange') bg-orange-100 text-orange-800
                                    @elseif($guru->employment_badge_color === 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $guru->status_kepegawaian }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi
                                Personal</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $guru->tanggal_lahir->format('d F Y') }}
                                        ({{ $guru->age }} tahun)</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $guru->tempat_lahir }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $guru->alamat }}</p>
                                </div>

                                @if ($guru->no_telepon)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $guru->no_telepon }}</p>
                                    </div>
                                @endif

                                @if ($guru->no_wa)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">No. WhatsApp</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $guru->no_wa }}</p>
                                    </div>
                                @endif

                                @if ($guru->email)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $guru->email }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">Informasi
                                Profesional</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $guru->tanggal_masuk->format('d F Y') }}
                                        ({{ $guru->years_of_service }} tahun)</p>
                                </div>

                                @if ($guru->tanggal_keluar)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Keluar</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ $guru->tanggal_keluar->format('d F Y') }}</p>
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $guru->pendidikan_terakhir }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Universitas</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $guru->universitas }}
                                        ({{ $guru->tahun_lulus }})</p>
                                </div>

                                @if ($guru->sertifikasi)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Sertifikasi</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $guru->sertifikasi }}</p>
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mata Pelajaran</label>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @foreach ($guru->mata_pelajaran as $subject)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $subject }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if ($guru->prestasi || $guru->catatan)
                        <div class="mt-8 space-y-6">
                            @if ($guru->prestasi)
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                        Prestasi</h3>
                                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $guru->prestasi }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($guru->catatan)
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">
                                        Catatan</h3>
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-900 whitespace-pre-line">{{ $guru->catatan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- User Account Information -->
                    @if ($guru->user)
                        <div class="mt-8">
                            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-200 pb-2">User Account
                            </h3>
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $guru->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $guru->user->email }}</p>
                                    </div>
                                    <div class="ml-auto">
                                        @if($guru->user && $guru->user->roles->count() > 0)
                                            @foreach($guru->user->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                No role
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
