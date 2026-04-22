<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Scan Barcode/QR Code</h1>
                <p class="text-slate-600 mt-1">Scan barcode atau QR code untuk melihat informasi barang</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.sarpras.barang.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Barang
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Scanner Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Scanner</h2>

            <!-- Camera Scanner -->
            <div class="mb-6">
                <div id="scanner-container" class="relative">
                    <video id="video" width="100%" height="300"
                        class="rounded-lg border border-gray-300"></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <div id="scanner-overlay" class="absolute inset-0 pointer-events-none">
                        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                        <div
                            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-32 border-2 border-white rounded-lg">
                            <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-blue-500"></div>
                            <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-blue-500"></div>
                            <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-blue-500"></div>
                            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-blue-500"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-center space-x-4">
                    <button id="start-scanner" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Start Scanner
                    </button>
                    <button id="stop-scanner" class="btn btn-secondary" disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                        Stop Scanner
                    </button>
                </div>
            </div>

            <!-- Manual Input -->
            <div class="border-t pt-6">
                <h3 class="text-md font-medium text-slate-900 mb-3">Manual Input</h3>
                <div class="flex space-x-4">
                    <input type="text" id="manual-code" placeholder="Masukkan kode barcode/QR code"
                        class="flex-1 form-input">
                    <button id="search-manual" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div id="results-section" class="bg-white rounded-xl border border-slate-200 p-6 hidden">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Hasil Scan</h2>
            <div id="scan-results">
                <!-- Results will be populated here -->
            </div>
        </div>

        <!-- Loading Section -->
        <div id="loading-section" class="bg-white rounded-xl border border-slate-200 p-6 hidden">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-slate-600">Mencari data barang...</span>
            </div>
        </div>

        <!-- Error Section -->
        <div id="error-section" class="bg-red-50 border border-red-200 rounded-xl p-6 hidden">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="text-lg font-medium text-red-800">Barang Tidak Ditemukan</h3>
                    <p class="text-red-600 mt-1" id="error-message">Kode yang di-scan tidak ditemukan dalam database.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Barcode Scanner -->
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <script>
        let scannerActive = false;
        let stream = null;

        // Scanner controls
        document.getElementById('start-scanner').addEventListener('click', startScanner);
        document.getElementById('stop-scanner').addEventListener('click', stopScanner);
        document.getElementById('search-manual').addEventListener('click', searchManual);
        document.getElementById('manual-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchManual();
            }
        });

        function startScanner() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment' // Use back camera
                        }
                    })
                    .then(function(mediaStream) {
                        stream = mediaStream;
                        const video = document.getElementById('video');
                        video.srcObject = mediaStream;
                        video.play();

                        // Initialize QuaggaJS for barcode scanning
                        Quagga.init({
                            inputStream: {
                                name: "Live",
                                type: "LiveStream",
                                target: video,
                                constraints: {
                                    width: 640,
                                    height: 480,
                                    facingMode: "environment"
                                }
                            },
                            decoder: {
                                readers: [
                                    "code_128_reader",
                                    "ean_reader",
                                    "ean_8_reader",
                                    "code_39_reader",
                                    "code_39_vin_reader",
                                    "codabar_reader",
                                    "upc_reader",
                                    "upc_e_reader",
                                    "i2of5_reader"
                                ]
                            },
                            locate: true
                        }, function(err) {
                            if (err) {
                                console.error('Error initializing Quagga:', err);
                                showError('Error initializing scanner: ' + err.message);
                                return;
                            }
                            Quagga.start();
                            scannerActive = true;

                            // Update UI
                            document.getElementById('start-scanner').disabled = true;
                            document.getElementById('stop-scanner').disabled = false;
                        });

                        // Listen for barcode detection
                        Quagga.onDetected(function(result) {
                            if (scannerActive) {
                                const code = result.codeResult.code;
                                console.log('Barcode detected:', code);
                                searchBarcode(code);
                            }
                        });

                    })
                    .catch(function(err) {
                        console.error('Error accessing camera:', err);
                        showError('Error accessing camera: ' + err.message);
                    });
            } else {
                showError('Camera not supported on this device');
            }
        }

        function stopScanner() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            if (scannerActive) {
                Quagga.stop();
                scannerActive = false;
            }

            const video = document.getElementById('video');
            video.srcObject = null;

            // Update UI
            document.getElementById('start-scanner').disabled = false;
            document.getElementById('stop-scanner').disabled = true;
        }

        function searchManual() {
            const code = document.getElementById('manual-code').value.trim();
            if (code) {
                searchBarcode(code);
            } else {
                showError('Masukkan kode barcode/QR code');
            }
        }

        function searchBarcode(code) {
            // Hide all sections
            hideAllSections();

            // Show loading
            document.getElementById('loading-section').classList.remove('hidden');

            // Make API call
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                showError('CSRF token tidak ditemukan. Silakan refresh halaman.');
                hideAllSections();
                return;
            }

            fetch('{{ route('admin.sarpras.barcode.scan.process') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        code: code
                    })
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Unexpected response format. Status: ${response.status}`);
                    }
                    return {
                        ok: response.ok,
                        status: response.status,
                        data: await response.json()
                    };
                })
                .then(result => {
                    hideAllSections();

                    if (!result.ok) {
                        if (result.status === 404) {
                            showError('Barang tidak ditemukan');
                        } else if (result.status === 422) {
                            const errors = result.data.errors || {};
                            const errorMsg = Object.values(errors).flat().join(', ') || result.data.message ||
                                'Validation error';
                            showError(errorMsg);
                        } else {
                            showError(result.data.message || 'Terjadi kesalahan saat mencari data');
                        }
                        return;
                    }

                    const data = result.data;
                    if (data.success) {
                        displayResults(data.data);
                    } else {
                        showError(data.message || 'Barang tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideAllSections();
                    showError('Terjadi kesalahan saat mencari data: ' + error.message);
                });
        }

        function displayResults(data) {
            // Build URLs using route base
            const detailUrl = '{{ route('admin.sarpras.barang.index') }}/' + data.id;

            const resultsHtml = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-green-800">${data.nama_barang}</h3>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-green-600"><strong>Kode Barang:</strong> ${data.kode_barang}</p>
                                    <p class="text-sm text-green-600"><strong>Kategori:</strong> ${data.kategori || 'N/A'}</p>
                                    <p class="text-sm text-green-600"><strong>Lokasi:</strong> ${data.lokasi || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-green-600"><strong>Kondisi:</strong> ${data.kondisi}</p>
                                    <p class="text-sm text-green-600"><strong>Status:</strong> ${data.status}</p>
                                    <p class="text-sm text-green-600"><strong>Serial Number:</strong> ${data.serial_number || 'N/A'}</p>
                                </div>
                            </div>
                            <div class="mt-4 flex space-x-3">
                                <a href="${detailUrl}" class="btn btn-primary btn-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Detail
                                </a>
                                <button onclick="printBarcode(${data.id})" class="btn btn-secondary btn-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print Barcode
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('scan-results').innerHTML = resultsHtml;
            document.getElementById('results-section').classList.remove('hidden');
        }

        function showError(message) {
            document.getElementById('error-message').textContent = message;
            document.getElementById('error-section').classList.remove('hidden');
        }

        function hideAllSections() {
            document.getElementById('results-section').classList.add('hidden');
            document.getElementById('loading-section').classList.add('hidden');
            document.getElementById('error-section').classList.add('hidden');
        }

        // Print barcode function
        function printBarcode(id) {
            // Build print URL using route base
            const printBase = '{{ route('admin.sarpras.barcode.generate-all') }}';
            const printUrl = printBase.replace('/barcode/generate-all', '/barcode/print/' + id);
            window.open(printUrl, '_blank');
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            stopScanner();
        });
    </script>
</x-app-layout>
