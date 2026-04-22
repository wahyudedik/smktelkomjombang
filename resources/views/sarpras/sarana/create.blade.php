<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Sarana</h1>
                <p class="text-slate-600 mt-1">Tambah inventaris sarana baru</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.sarana.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="saranaForm()">

        <form method="POST" action="{{ route('admin.sarpras.sarana.store') }}" id="saranaForm" class="space-y-6">
            @csrf

            <!-- Ruang Selection -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Pilih Ruang</h3>
                <div>
                    <label for="ruang_id" class="form-label">Ruang</label>
                    <select id="ruang_id" name="ruang_id" x-model="ruangId" required
                        class="form-input @error('ruang_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="">Pilih Ruang</option>
                        @foreach ($ruangs as $ruang)
                            <option value="{{ $ruang->id }}" {{ (isset($prefilledRuangId) && $prefilledRuangId == $ruang->id) ? 'selected' : '' }}>
                                {{ $ruang->nama_ruang }} ({{ $ruang->kode_ruang }})
                            </option>
                        @endforeach
                    </select>
                    @error('ruang_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Barang Selection -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Pilih Barang</h3>
                    <button type="button" @click="addBarangRow()" class="btn btn-sm btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Barang
                    </button>
                </div>

                <div class="space-y-4" id="barang-container">
                    <template x-for="(barang, index) in barangs" :key="index">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 p-4 bg-slate-50 rounded-lg">
                            <div>
                                <label class="form-label">Barang</label>
                                <select x-bind:name="'barang_ids[' + index + ']'" x-model="barang.barang_id" required
                                    class="form-input" :disabled="loading"
                                    @change="updateBarangHarga(index)">
                                    <option value="">Pilih Barang</option>
                                    <template x-for="barangOption in getBarangOptions()" :key="barangOption.id">
                                        <option x-bind:value="barangOption.id" 
                                            x-bind:data-harga="barangOption.harga_beli || 0"
                                            x-text="barangOption.nama_barang + ' (' + barangOption.kode_barang + ')' + (barangOption.ruang_id ? '' : ' - Belum ada ruang')"></option>
                                    </template>
                                </select>
                                <p x-show="loading" class="text-xs text-slate-500 mt-1">Memuat barang...</p>
                                <p x-show="!loading && ruangId && filteredBarangs.length === 0" class="text-xs text-yellow-600 mt-1">
                                    Tidak ada barang di ruang ini. Anda masih bisa menambahkan barang secara manual.
                                </p>
                            </div>
                            <div>
                                <label class="form-label">Jumlah</label>
                                <input type="number" x-bind:name="'jumlah[' + index + ']'" x-model.number="barang.jumlah" min="1" required
                                    class="form-input" @input="updateTotal(index)">
                            </div>
                            <div>
                                <label class="form-label">Kondisi</label>
                                <select x-bind:name="'kondisi[' + index + ']'" x-model="barang.kondisi" required
                                    class="form-input">
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" x-model="formatCurrency(barang.harga_beli || 0)" readonly
                                    class="form-input bg-slate-100" style="cursor: not-allowed;">
                                <input type="hidden" x-bind:name="'harga[' + index + ']'" x-model="barang.harga_beli">
                            </div>
                            <div>
                                <label class="form-label">Total</label>
                                <input type="text" x-model="formatCurrency(getTotalBarang(index))" readonly
                                    class="form-input bg-slate-100 font-semibold" style="cursor: not-allowed;">
                            </div>
                            <div class="flex items-end">
                                <button type="button" @click="removeBarangRow(index)" class="btn btn-sm btn-danger w-full"
                                    :disabled="barangs.length <= 1">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Grand Total -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-slate-900">Grand Total:</span>
                            <span class="text-xl font-bold text-blue-600" x-text="formatCurrency(getGrandTotal())"></span>
                        </div>
                    </div>
                </div>

                @error('barang_ids')
                    <p class="form-error mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Informasi Tambahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                            required
                            class="form-input @error('tanggal') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('tanggal')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea id="catatan" name="catatan" rows="3"
                            class="form-input @error('catatan') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Masukkan catatan (opsional)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sumber Dana (Hidden inputs, will be filled by popup) -->
            <input type="hidden" name="sumber_dana" x-model="sumberDana">
            <input type="hidden" name="kode_sumber_dana" x-model="kodeSumberDana">

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.sarpras.sarana.index') }}" class="btn btn-secondary">Batal</a>
                <button type="button" @click="openSumberDanaModal()" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Simpan Sarana
                </button>
            </div>
        </form>

        <!-- Sumber Dana Modal -->
        <div x-show="showModal" @click.away="showModal = false" x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" @click="showModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Input Sumber Dana</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="modal_sumber_dana" class="form-label">Sumber Dana</label>
                            <input type="text" id="modal_sumber_dana" x-model="sumberDana"
                                class="form-input" placeholder="Contoh: BOS, APBD, dll">
                        </div>
                        <div>
                            <label for="modal_kode_sumber_dana" class="form-label">Kode Sumber Dana <span class="text-red-500">*</span></label>
                            <input type="text" id="modal_kode_sumber_dana" x-model="kodeSumberDana" required
                                class="form-input" placeholder="Contoh: MAUDU/2025">
                            <p class="text-xs text-slate-500 mt-1">Format: MAUDU/YYYY</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button type="button" @click="showModal = false" class="btn btn-secondary">Batal</button>
                        <button type="button" @click="submitForm()" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function saranaForm() {
                return {
                    ruangId: @json($prefilledRuangId ?? ''),
                    barangs: @if(isset($prefilledBarangId))
                        [{
                            barang_id: '{{ $prefilledBarangId }}',
                            jumlah: 1,
                            kondisi: 'baik',
                            harga_beli: 0
                        }]
                    @else
                        [{
                            barang_id: '',
                            jumlah: 1,
                            kondisi: 'baik',
                            harga_beli: 0
                        }]
                    @endif,
                    allBarangs: @json($barangsJson),
                    filteredBarangs: [],
                    sumberDana: '',
                    kodeSumberDana: '',
                    showModal: false,
                    loading: false,

                    init() {
                        // Pre-load barang if ruang_id is pre-filled
                        if (this.ruangId) {
                            this.loadBarangByRuang(this.ruangId);
                        }
                        
                        // Pre-select barang if barang_id is pre-filled
                        @if(isset($prefilledBarangId))
                            this.$nextTick(() => {
                                const barangId = '{{ $prefilledBarangId }}';
                                const allOptions = this.getBarangOptions();
                                const selectedBarang = allOptions.find(b => String(b.id) == String(barangId));
                                if (selectedBarang && this.barangs.length > 0) {
                                    this.barangs[0].harga_beli = selectedBarang.harga_beli || 0;
                                    this.barangs[0].kondisi = selectedBarang.kondisi || 'baik';
                                }
                            });
                        @endif
                        
                        // Watch for ruang_id changes
                        this.$watch('ruangId', (value) => {
                            if (value) {
                                this.loadBarangByRuang(value);
                            } else {
                                this.filteredBarangs = [];
                            }
                        });
                        
                        // Add form submit listener to prevent any interference
                        this.$nextTick(() => {
                            const form = document.getElementById('saranaForm');
                            if (form) {
                                form.addEventListener('submit', function(e) {
                                    console.log('Form submit event triggered');
                                    // Don't prevent default - let form submit normally
                                    // Just log for debugging
                                });
                            }
                        });
                    },

                    async loadBarangByRuang(ruangId) {
                        this.loading = true;
                        try {
                            const response = await fetch(`{{ route('admin.sarpras.sarana.getBarangByRuang') }}?ruang_id=${ruangId}`);
                            const data = await response.json();
                            
                            if (data.success && data.barangs.length > 0) {
                                // Auto-fill barang yang ada di ruang tersebut
                                this.barangs = data.barangs.map(barang => ({
                                    barang_id: barang.id,
                                    jumlah: 1,
                                    kondisi: barang.kondisi || 'baik', // Gunakan kondisi dari master data
                                    harga_beli: barang.harga_beli || 0
                                }));
                                this.filteredBarangs = data.barangs;
                            } else {
                                // Jika tidak ada barang, reset ke satu row kosong
                                this.barangs = [{
                                    barang_id: '',
                                    jumlah: 1,
                                    kondisi: 'baik',
                                    harga_beli: 0
                                }];
                                this.filteredBarangs = [];
                            }
                        } catch (error) {
                            console.error('Error loading barang:', error);
                            this.filteredBarangs = [];
                        } finally {
                            this.loading = false;
                        }
                    },

                    getBarangOptions() {
                        if (this.filteredBarangs.length > 0) {
                            return this.filteredBarangs;
                        }
                        return this.allBarangs;
                    },

                    addBarangRow() {
                        this.barangs.push({
                            barang_id: '',
                            jumlah: 1,
                            kondisi: 'baik',
                            harga_beli: 0
                        });
                    },

                    updateBarangHarga(index) {
                        const selectedBarangId = this.barangs[index].barang_id;
                        if (!selectedBarangId) {
                            this.barangs[index].harga_beli = 0;
                            this.barangs[index].kondisi = 'baik';
                            return;
                        }
                        
                        // Find harga and kondisi from allBarangs or filteredBarangs
                        const allOptions = this.getBarangOptions();
                        const selectedBarang = allOptions.find(b => b.id == selectedBarangId);
                        if (selectedBarang) {
                            this.barangs[index].harga_beli = selectedBarang.harga_beli || 0;
                            // Set kondisi dari master data barang
                            this.barangs[index].kondisi = selectedBarang.kondisi || 'baik';
                        }
                    },

                    getTotalBarang(index) {
                        const barang = this.barangs[index];
                        const harga = barang.harga_beli || 0;
                        const jumlah = barang.jumlah || 1;
                        return harga * jumlah;
                    },

                    getGrandTotal() {
                        return this.barangs.reduce((total, barang) => {
                            const harga = barang.harga_beli || 0;
                            const jumlah = barang.jumlah || 1;
                            return total + (harga * jumlah);
                        }, 0);
                    },

                    formatCurrency(amount) {
                        if (!amount || amount === 0) return 'Rp 0';
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                    },

                    removeBarangRow(index) {
                        if (this.barangs.length > 1) {
                            this.barangs.splice(index, 1);
                        }
                    },

                    openSumberDanaModal() {
                        // Validate barang first
                        const hasBarang = this.barangs.some(b => b.barang_id && b.barang_id !== '');
                        if (!hasBarang) {
                            if (typeof showError !== 'undefined') {
                                showError('Validasi Gagal', 'Minimal satu barang harus dipilih!');
                            } else {
                                alert('Minimal satu barang harus dipilih!');
                            }
                            return;
                        }
                        
                        if (!this.kodeSumberDana) {
                            this.showModal = true;
                        } else {
                            this.submitForm();
                        }
                    },

                    submitForm() {
                        console.log('submitForm called', {
                            kodeSumberDana: this.kodeSumberDana,
                            sumberDana: this.sumberDana,
                            barangs: this.barangs,
                            ruangId: this.ruangId
                        });
                        
                        // Check if Sweet Alert functions are available
                        if (typeof showError === 'undefined' || typeof showLoading === 'undefined') {
                            console.warn('Sweet Alert functions not available, using fallback');
                            // Fallback to alert if Sweet Alert not available
                            if (!this.kodeSumberDana) {
                                alert('Kode sumber dana harus diisi!');
                                return;
                            }
                            const hasBarang = this.barangs.some(b => b.barang_id && b.barang_id !== '');
                            if (!hasBarang) {
                                alert('Minimal satu barang harus dipilih!');
                                return;
                            }
                        } else {
                            if (!this.kodeSumberDana) {
                                showError('Validasi Gagal', 'Kode sumber dana harus diisi!');
                                return;
                            }
                            
                            // Validate that at least one barang is selected
                            const hasBarang = this.barangs.some(b => b.barang_id && b.barang_id !== '');
                            if (!hasBarang) {
                                showError('Validasi Gagal', 'Minimal satu barang harus dipilih!');
                                return;
                            }
                        }
                        
                        this.showModal = false;
                        
                        // Small delay to ensure modal is closed
                        setTimeout(() => {
                            const form = document.getElementById('saranaForm');
                            if (form) {
                                console.log('Submitting form...', {
                                    action: form.action,
                                    method: form.method,
                                    formData: new FormData(form)
                                });
                                
                                // Show loading if available
                                if (typeof showLoading !== 'undefined') {
                                    showLoading('Menyimpan Data...', 'Mohon tunggu, data sedang disimpan');
                                }
                                
                                // Ensure form is visible and enabled
                                form.style.display = 'block';
                                form.submit();
                            } else {
                                console.error('Form not found!');
                                if (typeof showError !== 'undefined') {
                                    showError('Error', 'Form tidak ditemukan. Silakan refresh halaman.');
                                } else {
                                    alert('Form tidak ditemukan. Silakan refresh halaman.');
                                }
                            }
                        }, 100);
                    }
                }
            }
        </script>

        <script>
            // Wait for Sweet Alert functions to be available
            function initSaranaMessages() {
                // Check if Sweet Alert functions are available
                if (typeof showSuccess === 'undefined' || typeof showError === 'undefined') {
                    // Retry after a short delay
                    setTimeout(initSaranaMessages, 100);
                    return;
                }

                // Show success/error messages with Sweet Alert
                @if (session('success'))
                    showSuccess('Berhasil!', '{{ session('success') }}');
                @endif

                @if (session('error'))
                    showError('Error!', '{{ session('error') }}');
                @endif

                @if ($errors->any())
                    showError('Terjadi Kesalahan!', '{!! implode('<br>', $errors->all()) !!}');
                @endif
            }

            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSaranaMessages);
            } else {
                // DOM is already ready
                initSaranaMessages();
            }
        </script>
    @endpush
</x-app-layout>

