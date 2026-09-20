<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-dark-800 border-b border-slate-200 dark:border-dark-700">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <x-admin.breadcrumb title="Edit Data Tamu" :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Buku Tamu', 'url' => route('admin.buku-tamu.index')],
                        ['label' => $guest->ticket_number, 'url' => route('admin.buku-tamu.show', $guest)],
                        ['label' => 'Edit'],
                    ]" />
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.buku-tamu.show', $guest) }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 lg:px-4 rounded text-sm">
                            Lihat Detail
                        </a>
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
                    <form method="POST" action="{{ route('admin.buku-tamu.update', $guest) }}" enctype="multipart/form-data" x-data="guestForm()">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Data Diri Tamu -->
                            <div class="space-y-6">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Data Diri Tamu</h3>

                                <!-- Nomor Tiket (Readonly) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Tiket</label>
                                    <input type="text" value="{{ $guest->ticket_number }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                                </div>

                                <!-- Nama Tamu -->
                                <div>
                                    <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Tamu *</label>
                                    <input type="text" name="guest_name" id="guest_name"
                                        value="{{ old('guest_name', $guest->guest_name) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('guest_name') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('guest_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIK -->
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                    <input type="text" name="nik" id="nik"
                                        value="{{ old('nik', $guest->nik) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nik') border-red-500 @else border-gray-300 @enderror">
                                    @error('nik')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No. Telepon -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ old('phone', $guest->phone) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @else border-gray-300 @enderror">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $guest->email) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @else border-gray-300 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="address" id="address" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @else border-gray-300 @enderror">{{ old('address', $guest->address) }}</textarea>
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informasi Kunjungan -->
                            <div class="space-y-6">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Informasi Kunjungan</h3>

                                <!-- Instansi / Organisasi -->
                                <div>
                                    <label for="organization" class="block text-sm font-medium text-gray-700 mb-1">Instansi / Organisasi</label>
                                    <input type="text" name="organization" id="organization"
                                        value="{{ old('organization', $guest->organization) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('organization') border-red-500 @else border-gray-300 @enderror">
                                    @error('organization')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jabatan -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                    <input type="text" name="position" id="position"
                                        value="{{ old('position', $guest->position) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('position') border-red-500 @else border-gray-300 @enderror">
                                    @error('position')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kategori Kunjungan -->
                                <div>
                                    <label for="visit_category" class="block text-sm font-medium text-gray-700 mb-1">Kategori Kunjungan *</label>
                                    <select name="visit_category" id="visit_category"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_category') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($visitCategories as $key => $label)
                                            <option value="{{ $key }}" {{ old('visit_category', $guest->visit_category) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('visit_category')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tujuan Kunjungan -->
                                <div>
                                    <label for="visit_target" class="block text-sm font-medium text-gray-700 mb-1">Tujuan Kunjungan (Yang Dituju)</label>
                                    <input type="text" name="visit_target" id="visit_target"
                                        value="{{ old('visit_target', $guest->visit_target) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_target') border-red-500 @else border-gray-300 @enderror">
                                    @error('visit_target')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Keperluan -->
                                <div>
                                    <label for="visit_purpose" class="block text-sm font-medium text-gray-700 mb-1">Keperluan</label>
                                    <textarea name="visit_purpose" id="visit_purpose" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_purpose') border-red-500 @else border-gray-300 @enderror">{{ old('visit_purpose', $guest->visit_purpose) }}</textarea>
                                    @error('visit_purpose')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" id="status"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @else border-gray-300 @enderror">
                                        <option value="check_in" {{ old('status', $guest->status) == 'check_in' ? 'selected' : '' }}>Check In</option>
                                        <option value="check_out" {{ old('status', $guest->status) == 'check_out' ? 'selected' : '' }}>Check Out</option>
                                        <option value="dibatalkan" {{ old('status', $guest->status) == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    @error('status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Foto Tamu -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Tamu</label>
                                    @if ($guest->photo_url)
                                        <div class="mb-2">
                                            <img src="{{ $guest->photo_url }}" alt="{{ $guest->guest_name }}"
                                                class="h-24 w-24 sm:h-32 sm:w-32 object-cover rounded-lg border border-gray-200">
                                        </div>
                                    @endif
                                    <input type="file" name="photo" id="photo" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @change="previewImage($event)">
                                    <p class="text-xs text-gray-500 mt-1">Format: 4×6, Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                                    @error('photo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <!-- Preview foto baru -->
                                    <div x-show="imageUrl" class="mt-2">
                                        <p class="text-xs text-gray-500 mb-1">Preview foto baru:</p>
                                        <img :src="imageUrl" class="h-24 w-24 sm:h-32 sm:w-32 object-cover rounded-lg border border-gray-200">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kendaraan -->
                        <div class="mt-4 sm:mt-6">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Kendaraan</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kendaraan</label>
                                    <select name="vehicle_type" id="vehicle_type"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_type') border-red-500 @else border-gray-300 @enderror">
                                        <option value="">Pilih Jenis Kendaraan</option>
                                        <option value="mobil" {{ old('vehicle_type', $guest->vehicle_type) == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                        <option value="motor" {{ old('vehicle_type', $guest->vehicle_type) == 'motor' ? 'selected' : '' }}>Motor</option>
                                        <option value="sepeda" {{ old('vehicle_type', $guest->vehicle_type) == 'sepeda' ? 'selected' : '' }}>Sepeda</option>
                                        <option value="lainnya" {{ old('vehicle_type', $guest->vehicle_type) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('vehicle_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="vehicle_plate" class="block text-sm font-medium text-gray-700 mb-1">No. Plat Kendaraan</label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate"
                                        value="{{ old('vehicle_plate', $guest->vehicle_plate) }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('vehicle_plate') border-red-500 @else border-gray-300 @enderror">
                                    @error('vehicle_plate')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mt-4 sm:mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @else border-gray-300 @enderror">{{ old('notes', $guest->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('admin.buku-tamu.show', $guest) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded text-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function guestForm() {
            return {
                imageUrl: '',
                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imageUrl = URL.createObjectURL(file);
                    }
                }
            };
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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showError('Validasi Gagal!', @json(implode("\n", $errors->all())));
            });
        </script>
    @endif
</x-app-layout>
