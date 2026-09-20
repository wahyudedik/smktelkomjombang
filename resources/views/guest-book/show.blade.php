<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-dark-800 border-b border-slate-200 dark:border-dark-700">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <x-admin.breadcrumb title="Detail Tamu" :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Buku Tamu', 'url' => route('admin.buku-tamu.index')],
                        ['label' => $guest->ticket_number],
                    ]" />
                    <div class="flex flex-wrap gap-2">
                        @can('buku-tamu.update')
                            <a href="{{ route('admin.buku-tamu.edit', $guest) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 lg:px-4 rounded text-sm">
                                Edit
                            </a>
                        @endcan
                        @if ($guest->status === 'check_in')
                            @can('buku-tamu.checkout')
                                <form method="POST" action="{{ route('admin.buku-tamu.checkout', $guest) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-3 lg:px-4 rounded text-sm"
                                        onclick="return confirm('Check-out tamu ini?')">
                                        Check-Out
                                    </button>
                                </form>
                            @endcan
                        @endif
                        <a href="{{ route('admin.buku-tamu.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 lg:px-4 rounded text-sm">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 lg:p-6 text-gray-900">

                    <!-- Header: Foto + Info Utama -->
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6 sm:mb-8">
                        <!-- Foto Tamu -->
                        <div class="w-full sm:w-1/4 flex-shrink-0">
                            @if ($guest->photo_url)
                                <img src="{{ $guest->photo_url }}" alt="{{ $guest->guest_name }}"
                                    class="w-full max-w-[200px] sm:max-w-none h-40 sm:h-48 lg:h-56 object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-full max-w-[200px] sm:max-w-none h-40 sm:h-48 lg:h-56 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-gray-500 text-sm">Tidak ada foto</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Info Utama -->
                        <div class="w-full sm:w-3/4">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 mb-3 sm:mb-4">
                                <div>
                                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $guest->guest_name }}</h1>
                                    <p class="text-base sm:text-lg text-blue-600 font-semibold mt-1">{{ $guest->ticket_number }}</p>
                                </div>
                                <div>
                                    @if ($guest->status === 'check_in')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Sedang Berkunjung
                                        </span>
                                    @elseif ($guest->status === 'check_out')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Selesai
                                        </span>
                                    @elseif ($guest->status === 'dibatalkan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Kategori Kunjungan</p>
                                    <p class="font-medium">{{ $guest->visit_category ? ucfirst(str_replace('_', ' ', $guest->visit_category)) : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Instansi / Organisasi</p>
                                    <p class="font-medium">{{ $guest->organization ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jabatan</p>
                                    <p class="font-medium">{{ $guest->position ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Kunjungan -->
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Timeline Kunjungan</h3>
                        <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                                <!-- Check In -->
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Check In</p>
                                        <p class="text-sm text-gray-500">{{ $guest->check_in_at ? $guest->check_in_at->format('d M Y H:i:s') : '-' }}</p>
                                    </div>
                                </div>

                                <!-- Arrow -->
                                <div class="hidden sm:block">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>

                                <!-- Check Out -->
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 {{ $guest->check_out_at ? 'bg-orange-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ $guest->check_out_at ? 'text-orange-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Check Out</p>
                                        <p class="text-sm text-gray-500">{{ $guest->check_out_at ? $guest->check_out_at->format('d M Y H:i:s') : 'Masih berkunjung' }}</p>
                                    </div>
                                </div>

                                <!-- Durasi -->
                                @if ($guest->duration)
                                    <div class="sm:ml-auto mt-2 sm:mt-0">
                                        <div class="bg-blue-100 text-blue-800 text-xs sm:text-sm font-medium px-3 py-1 rounded-full inline-block">
                                            Durasi: {{ $guest->duration }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Data Diri -->
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Data Diri</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Nama Lengkap</p>
                                <p class="font-medium">{{ $guest->guest_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">NIK</p>
                                <p class="font-medium">{{ $guest->nik ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">No. Telepon</p>
                                <p class="font-medium">{{ $guest->phone ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $guest->email ?? '-' }}</p>
                            </div>
                            <div class="lg:col-span-2">
                                <p class="text-sm text-gray-500">Alamat</p>
                                <p class="font-medium">{{ $guest->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Kunjungan -->
                    <div class="mb-6 sm:mb-8">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Informasi Kunjungan</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Tujuan Kunjungan</p>
                                <p class="font-medium">{{ $guest->visit_target ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Keperluan</p>
                                <p class="font-medium">{{ $guest->visit_purpose ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Dicatat Oleh</p>
                                <p class="font-medium">{{ $guest->creator->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kendaraan -->
                    @if ($guest->vehicle_type || $guest->vehicle_plate)
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Kendaraan</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                @if ($guest->vehicle_type)
                                    <div>
                                        <p class="text-sm text-gray-500">Jenis Kendaraan</p>
                                        <p class="font-medium">{{ ucfirst($guest->vehicle_type) }}</p>
                                    </div>
                                @endif
                                @if ($guest->vehicle_plate)
                                    <div>
                                        <p class="text-sm text-gray-500">No. Plat</p>
                                        <p class="font-medium">{{ $guest->vehicle_plate }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Catatan -->
                    @if ($guest->notes)
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">Catatan</h3>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-800">{{ $guest->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 sm:pt-6 border-t">
                        @can('buku-tamu.update')
                            <a href="{{ route('admin.buku-tamu.edit', $guest) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                        @endcan
                        @if ($guest->status === 'check_in')
                            @can('buku-tamu.checkout')
                                <form method="POST" action="{{ route('admin.buku-tamu.checkout', $guest) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Check-out tamu ini?')">
                                        Check-Out
                                    </button>
                                </form>
                            @endcan
                        @endif
                        @can('buku-tamu.delete')
                            <form method="POST" action="{{ route('admin.buku-tamu.destroy', $guest) }}" class="inline"
                                id="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="confirmDelete()">Hapus</button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showSuccess('Berhasil!', @json(session('success')));
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showError('Error!', @json(session('error')));
            });
        </script>
    @endif
</x-app-layout>
