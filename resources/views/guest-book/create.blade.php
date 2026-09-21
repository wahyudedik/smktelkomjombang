<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-dark-800 border-b border-slate-200 dark:border-dark-700">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <x-admin.breadcrumb title="Check-In Tamu" :items="[
                        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                        ['label' => 'Buku Tamu', 'url' => route('admin.buku-tamu.index')],
                        ['label' => 'Check-In'],
                    ]" />
                    <a href="{{ route('admin.buku-tamu.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 lg:px-4 rounded text-sm">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 lg:p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.buku-tamu.store') }}" enctype="multipart/form-data" x-data="guestForm()">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Data Diri Tamu -->
                            <div class="space-y-6">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Data Diri Tamu</h3>

                                <!-- Nama Tamu -->
                                <div>
                                    <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Tamu *</label>
                                    <input type="text" name="guest_name" id="guest_name" value="{{ old('guest_name') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('guest_name') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('guest_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIK -->
                                <div>
                                    <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nik') border-red-500 @else border-gray-300 @enderror">
                                    @error('nik')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- No. WhatsApp -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @else border-gray-300 @enderror">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @else border-gray-300 @enderror">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea name="address" id="address" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @else border-gray-300 @enderror">{{ old('address') }}</textarea>
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
                                    <label for="organization" class="block text-sm font-medium text-gray-700 mb-1">Instansi / Asal *</label>
                                    <input type="text" name="organization" id="organization" value="{{ old('organization') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('organization') border-red-500 @else border-gray-300 @enderror"
                                        required>
                                    @error('organization')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jabatan -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}"
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
                                            <option value="{{ $key }}" {{ old('visit_category') == $key ? 'selected' : '' }}>
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
                                    <input type="text" name="visit_target" id="visit_target" value="{{ old('visit_target') }}"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_target') border-red-500 @else border-gray-300 @enderror">
                                    @error('visit_target')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Keperluan Kunjungan -->
                                <div>
                                    <label for="visit_purpose" class="block text-sm font-medium text-gray-700 mb-1">Keperluan Kunjungan *</label>
                                    <textarea name="visit_purpose" id="visit_purpose" rows="3"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('visit_purpose') border-red-500 @else border-gray-300 @enderror"
                                        required>{{ old('visit_purpose') }}</textarea>
                                    @error('visit_purpose')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Foto Tamu -->
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Foto Tamu</label>
                                    <input type="file" name="photo" id="photo" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @change="previewImage($event)">
                                    <p class="text-xs text-gray-500 mt-1">Format: 4×6, Maksimal 2MB</p>
                                    @error('photo')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <!-- Preview -->
                                    <div x-show="imageUrl" class="mt-2">
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
                                        <option value="mobil" {{ old('vehicle_type') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                        <option value="motor" {{ old('vehicle_type') == 'motor' ? 'selected' : '' }}>Motor</option>
                                        <option value="sepeda" {{ old('vehicle_type') == 'sepeda' ? 'selected' : '' }}>Sepeda</option>
                                        <option value="lainnya" {{ old('vehicle_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('vehicle_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="vehicle_plate" class="block text-sm font-medium text-gray-700 mb-1">No. Plat Kendaraan</label>
                                    <input type="text" name="vehicle_plate" id="vehicle_plate" value="{{ old('vehicle_plate') }}"
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
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @else border-gray-300 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Signature Pad --}}
                        <div class="mt-4 sm:mt-6">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3 sm:mb-4">Tanda Tangan Digital</h3>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800">
                                <canvas id="signature-pad" width="400" height="200" class="w-full cursor-crosshair border border-gray-200 rounded" style="touch-action: none;"></canvas>
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="button" id="clear-signature" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-eraser mr-1"></i> Hapus
                                    </button>
                                    <span class="text-xs text-gray-500">Tanda tangan di area di atas</span>
                                </div>
                                <input type="hidden" name="signature" id="signature-data">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                            <a href="{{ route('admin.buku-tamu.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded text-sm">
                                Check-In Tamu
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        ctx.fillStyle = '#fff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;
            const clientY = e.touches ? e.touches[0].clientY : e.clientY;
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function startDraw(e) {
            isDrawing = true;
            const pos = getPos(e);
            lastX = pos.x;
            lastY = pos.y;
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            lastX = pos.x;
            lastY = pos.y;
            e.preventDefault();
        }

        function stopDraw() {
            if (isDrawing) {
                isDrawing = false;
                document.getElementById('signature-data').value = canvas.toDataURL('image/png');
            }
        }

        canvas.addEventListener('mousedown', startDraw);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDraw);
        canvas.addEventListener('mouseleave', stopDraw);
        canvas.addEventListener('touchstart', startDraw);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDraw);

        document.getElementById('clear-signature').addEventListener('click', function() {
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            document.getElementById('signature-data').value = '';
        });
    });
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
