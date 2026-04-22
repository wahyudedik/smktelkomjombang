<x-app-layout>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('common.landing_page_settings') }}</h1>
                <p class="text-gray-600 mt-2">{{ __('common.manage_landing_page_settings_description') }}</p>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <h4 class="font-bold mb-2">Terjadi kesalahan:</h4>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.settings.landing-page.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <!-- Site Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Site Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name
                                *</label>
                            <input type="text" id="site_name" name="site_name"
                                value="{{ cache('site_setting_site_name', 'MAUDU REJOSO') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>
                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">Site
                                Description</label>
                            <textarea id="site_description" name="site_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ cache('site_setting_site_description') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label for="site_keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords
                                (comma
                                separated)</label>
                            <input type="text" id="site_keywords" name="site_keywords"
                                value="{{ cache('site_setting_site_keywords') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="sekolah, pendidikan, madrasah">
                        </div>
                    </div>
                </div>

                <!-- Logo & Favicon -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Logo & Favicon</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                            <input type="file" id="logo" name="logo" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @if (cache('site_setting_logo'))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current logo:</p>
                                    <img src="{{ Storage::url(cache('site_setting_logo')) }}" alt="Current Logo"
                                        class="h-16 w-auto mt-1">
                                </div>
                            @endif
                        </div>
                        <div>
                            <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            <input type="file" id="favicon" name="favicon" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @if (cache('site_setting_favicon'))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current favicon:</p>
                                    <img src="{{ Storage::url(cache('site_setting_favicon')) }}" alt="Current Favicon"
                                        class="h-8 w-8 mt-1">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Hero Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Hero Section</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="hero_title" class="block text-sm font-medium text-gray-700 mb-2">Hero
                                Title</label>
                            <input type="text" id="hero_title" name="hero_title"
                                value="{{ cache('site_setting_hero_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Selamat Datang di MAUDU REJOSO">
                        </div>
                        <div>
                            <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Hero
                                Subtitle</label>
                            <textarea id="hero_subtitle" name="hero_subtitle" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Membangun generasi yang berakhlak mulia dan berprestasi">{{ cache('site_setting_hero_subtitle') }}</textarea>
                        </div>

                        <!-- Hero Slide 1 Settings -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hero Slide 1 - Library</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hero_slide1_subtitle"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 1 Subtitle</label>
                                    <input type="text" id="hero_slide1_subtitle" name="hero_slide1_subtitle"
                                        value="{{ cache('site_setting_hero_slide1_subtitle', 'Welcome To MAUDU Library') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Welcome To MAUDU Library">
                                </div>
                                <div>
                                    <label for="hero_slide1_title"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 1 Title</label>
                                    <input type="text" id="hero_slide1_title" name="hero_slide1_title"
                                        value="{{ cache('site_setting_hero_slide1_title', 'Grand Opening MAUDU Library') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Grand Opening MAUDU Library">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="hero_slide1_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 1
                                        Description</label>
                                    <textarea id="hero_slide1_description" name="hero_slide1_description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Acara Grandopening Dihadiri oleh Majelis Pimpinan Pondok Pesantren Darul Ulum Rejoso Peterongan Jombang">{{ cache('site_setting_hero_slide1_description', 'Acara Grandopening Dihadiri oleh Majelis Pimpinan Pondok Pesantren Darul Ulum Rejoso Peterongan Jombang') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Slide 2 Settings -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hero Slide 2 - DPRD</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hero_slide2_subtitle"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 2 Subtitle</label>
                                    <input type="text" id="hero_slide2_subtitle" name="hero_slide2_subtitle"
                                        value="{{ cache('site_setting_hero_slide2_subtitle', 'Studi Edukasi Sosial') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Studi Edukasi Sosial">
                                </div>
                                <div>
                                    <label for="hero_slide2_title"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 2 Title</label>
                                    <input type="text" id="hero_slide2_title" name="hero_slide2_title"
                                        value="{{ cache('site_setting_hero_slide2_title', 'Gedung DPRD Kabupaten Jombang') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Gedung DPRD Kabupaten Jombang">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="hero_slide2_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 2
                                        Description</label>
                                    <textarea id="hero_slide2_description" name="hero_slide2_description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Deskripsi untuk slide 2">{{ cache('site_setting_hero_slide2_description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Slide 3 Settings -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Hero Slide 3 - KOMPASS</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="hero_slide3_subtitle"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 3 Subtitle</label>
                                    <input type="text" id="hero_slide3_subtitle" name="hero_slide3_subtitle"
                                        value="{{ cache('site_setting_hero_slide3_subtitle', 'Event KOMPASS') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Event KOMPASS">
                                </div>
                                <div>
                                    <label for="hero_slide3_title"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 3 Title</label>
                                    <input type="text" id="hero_slide3_title" name="hero_slide3_title"
                                        value="{{ cache('site_setting_hero_slide3_title', 'Kompetisi Agama, Sains, dan Seni 2024') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Kompetisi Agama, Sains, dan Seni 2024">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="hero_slide3_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Slide 3
                                        Description</label>
                                    <textarea id="hero_slide3_description" name="hero_slide3_description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Deskripsi untuk slide 3">{{ cache('site_setting_hero_slide3_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="hero_images" class="block text-sm font-medium text-gray-700 mb-2">Hero Images
                                (Multiple)</label>
                            <input type="file" id="hero_images" name="hero_images[]" accept="image/*" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Pilih multiple gambar untuk hero carousel (max 5
                                gambar)</p>

                            @php
                                $heroImages = cache('site_setting_hero_images');
                                if ($heroImages) {
                                    $heroImages = json_decode($heroImages, true);
                                }
                            @endphp

                            @if ($heroImages && count($heroImages) > 0)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 mb-2">Current hero images:</p>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach ($heroImages as $index => $image)
                                            <div class="relative">
                                                <img src="{{ Storage::url($image) }}"
                                                    alt="Hero Image {{ $index + 1 }}"
                                                    class="h-24 w-full object-cover rounded">
                                                <div
                                                    class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 rounded">
                                                    {{ $index + 1 }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Feature Cards Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Feature Cards Section</h2>
                    <div class="space-y-6">
                        <!-- Feature Card 1 -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Feature Card 1 - E-Library</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="feature1_title"
                                        class="block text-sm font-medium text-gray-700 mb-2">Feature 1 Title</label>
                                    <input type="text" id="feature1_title" name="feature1_title"
                                        value="{{ cache('site_setting_feature1_title', 'E-LIBRARY') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="E-LIBRARY">
                                </div>
                                <div>
                                    <label for="feature1_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Feature 1
                                        Description</label>
                                    <textarea id="feature1_description" name="feature1_description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Perpustakaan digital berisi Koleksi materi dalam format elektronik">{{ cache('site_setting_feature1_description', 'Perpustakaan digital berisi Koleksi materi dalam format elektronik') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Feature Card 2 -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Feature Card 2 - Sertifikasi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="feature2_title"
                                        class="block text-sm font-medium text-gray-700 mb-2">Feature 2 Title</label>
                                    <input type="text" id="feature2_title" name="feature2_title"
                                        value="{{ cache('site_setting_feature2_title', 'SERTIFIKASI KOMPETENSI') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="SERTIFIKASI KOMPETENSI">
                                </div>
                                <div>
                                    <label for="feature2_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Feature 2
                                        Description</label>
                                    <textarea id="feature2_description" name="feature2_description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Uji kompetensi yang sistematis dan objektif">{{ cache('site_setting_feature2_description', 'Uji kompetensi yang sistematis dan objektif') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Feature Card 3 -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Feature Card 3 - Karya Literasi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="feature3_title"
                                        class="block text-sm font-medium text-gray-700 mb-2">Feature 3 Title</label>
                                    <input type="text" id="feature3_title" name="feature3_title"
                                        value="{{ cache('site_setting_feature3_title', 'KARYA LITERASI') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="KARYA LITERASI">
                                </div>
                                <div>
                                    <label for="feature3_description"
                                        class="block text-sm font-medium text-gray-700 mb-2">Feature 3
                                        Description</label>
                                    <textarea id="feature3_description" name="feature3_description" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Penelitian di Bidang Keislaman, Sains, Teknologi, dan Sosial.">{{ cache('site_setting_feature3_description', 'Penelitian di Bidang Keislaman, Sains, Teknologi, dan Sosial.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Counter Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Counter Section (Statistik)</h2>
                    <div class="space-y-6">
                        <!-- Counter 1 -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Counter 1 - Mata Pelajaran</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="counter1_number"
                                        class="block text-sm font-medium text-gray-700 mb-2">Counter 1 Number</label>
                                    <input type="number" id="counter1_number" name="counter1_number"
                                        value="{{ cache('site_setting_counter1_number', '24') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="24">
                                </div>
                                <div>
                                    <label for="counter1_label"
                                        class="block text-sm font-medium text-gray-700 mb-2">Counter 1 Label</label>
                                    <input type="text" id="counter1_label" name="counter1_label"
                                        value="{{ cache('site_setting_counter1_label', 'Mata Pelajaran') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Mata Pelajaran">
                                </div>
                            </div>
                        </div>

                        <!-- Counter 2 -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Counter 2 - Peserta Didik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="counter2_number"
                                        class="block text-sm font-medium text-gray-700 mb-2">Counter 2 Number</label>
                                    <input type="number" id="counter2_number" name="counter2_number"
                                        value="{{ cache('site_setting_counter2_number', '800') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="800">
                                </div>
                                <div>
                                    <label for="counter2_label"
                                        class="block text-sm font-medium text-gray-700 mb-2">Counter 2 Label</label>
                                    <input type="text" id="counter2_label" name="counter2_label"
                                        value="{{ cache('site_setting_counter2_label', '+ Peserta Didik') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="+ Peserta Didik">
                                </div>
                            </div>
                        </div>

                        <!-- Counter 3 -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Counter 3 - Tenaga Pendidik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="counter3_number"
                                        class="block text-sm font-medium text-gray-700 mb-2">Counter 3 Number</label>
                                    <input type="number" id="counter3_number" name="counter3_number"
                                        value="{{ cache('site_setting_counter3_number', '98') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="98">
                                </div>
                                <div>
                                    <label for="counter3_label"
                                        class="block text-sm font-medium text-gray-700 mb-2">Counter 3 Label</label>
                                    <input type="text" id="counter3_label" name="counter3_label"
                                        value="{{ cache('site_setting_counter3_label', '+ Tenaga Pendidik & KEPENDIDIKAN') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="+ Tenaga Pendidik & KEPENDIDIKAN">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Gallery Section</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="gallery_title" class="block text-sm font-medium text-gray-700 mb-2">Gallery
                                Title</label>
                            <input type="text" id="gallery_title" name="gallery_title"
                                value="{{ cache('site_setting_gallery_title', 'Kegiatan Madrasah') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Kegiatan Madrasah">
                        </div>
                        <div>
                            <label for="gallery_subtitle" class="block text-sm font-medium text-gray-700 mb-2">Gallery
                                Subtitle</label>
                            <input type="text" id="gallery_subtitle" name="gallery_subtitle"
                                value="{{ cache('site_setting_gallery_subtitle', 'Ket// programmer : ambil data dari dari IG') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ket// programmer : ambil data dari dari IG">
                        </div>
                    </div>
                </div>

                <!-- Header Top Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Header Top Section (Bagian Hijau)</h2>
                    <div class="space-y-6">
                        <!-- Social Media Links -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Social Media Links</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="social_facebook"
                                        class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                                    <input type="url" id="social_facebook" name="social_facebook"
                                        value="{{ cache('site_setting_social_facebook') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://facebook.com/sekolah">
                                </div>
                                <div>
                                    <label for="social_instagram"
                                        class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                                    <input type="url" id="social_instagram" name="social_instagram"
                                        value="{{ cache('site_setting_social_instagram') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://instagram.com/sekolah">
                                </div>
                                <div>
                                    <label for="social_youtube"
                                        class="block text-sm font-medium text-gray-700 mb-2">YouTube URL</label>
                                    <input type="url" id="social_youtube" name="social_youtube"
                                        value="{{ cache('site_setting_social_youtube') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://youtube.com/sekolah">
                                </div>
                                <div>
                                    <label for="social_whatsapp"
                                        class="block text-sm font-medium text-gray-700 mb-2">WhatsApp URL</label>
                                    <input type="url" id="social_whatsapp" name="social_whatsapp"
                                        value="{{ cache('site_setting_social_whatsapp') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="https://wa.me/628123456789">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_email"
                                        class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" id="contact_email" name="contact_email"
                                        value="{{ cache('site_setting_contact_email') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="contact_phone"
                                        class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="text" id="contact_phone" name="contact_phone"
                                        value="{{ cache('site_setting_contact_phone') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="contact_address"
                                        class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                    <textarea id="contact_address" name="contact_address" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Jl. Contoh No. 123, Kota, Provinsi">{{ cache('site_setting_contact_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Video Section</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL
                                (YouTube)</label>
                            <input type="url" id="video_url" name="video_url"
                                value="{{ cache('site_setting_video_url') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="https://www.youtube.com/watch?v=example">
                            <p class="text-sm text-gray-500 mt-1">Masukkan URL YouTube video yang akan ditampilkan di
                                landing page</p>
                        </div>
                        <div>
                            <label for="video_thumbnail" class="block text-sm font-medium text-gray-700 mb-2">Video
                                Thumbnail (Optional)</label>
                            <input type="file" id="video_thumbnail" name="video_thumbnail" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Upload thumbnail custom untuk video (jika tidak
                                diisi, akan menggunakan thumbnail YouTube)</p>

                            @if (cache('site_setting_video_thumbnail'))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current thumbnail:</p>
                                    <img src="{{ Storage::url(cache('site_setting_video_thumbnail')) }}"
                                        alt="Current Video Thumbnail" class="h-24 w-auto mt-1 rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Headmaster Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Kepala Sekolah</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="headmaster_name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                Kepala Sekolah</label>
                            <input type="text" id="headmaster_name" name="headmaster_name"
                                value="{{ cache('site_setting_headmaster_name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Khoiruddinul Qoyyum, S.S., M.Pd">
                        </div>
                        <div>
                            <label for="headmaster_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kepala Sekolah</label>
                            <textarea id="headmaster_description" name="headmaster_description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Sebagai kepala madrasah yang berpengalaman, kami berkomitmen untuk memberikan pendidikan terbaik...">{{ cache('site_setting_headmaster_description') }}</textarea>
                        </div>
                        <div>
                            <label for="headmaster_vision" class="block text-sm font-medium text-gray-700 mb-2">Visi
                                Kepala Sekolah</label>
                            <textarea id="headmaster_vision" name="headmaster_vision" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Visi kami adalah menciptakan generasi yang unggul dalam akademik...">{{ cache('site_setting_headmaster_vision') }}</textarea>
                        </div>
                        <div>
                            <label for="headmaster_photo" class="block text-sm font-medium text-gray-700 mb-2">Foto
                                Kepala Sekolah</label>
                            <input type="file" id="headmaster_photo" name="headmaster_photo" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Upload foto kepala sekolah (format: JPG, PNG,
                                maksimal 2MB)</p>

                            @if (cache('site_setting_headmaster_photo'))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current photo:</p>
                                    <img src="{{ Storage::url(cache('site_setting_headmaster_photo')) }}"
                                        alt="Current Headmaster Photo" class="h-24 w-auto mt-1 rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Program Peminatan Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Program Peminatan</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="program_section_title"
                                class="block text-sm font-medium text-gray-700 mb-2">Judul Section Program
                                Peminatan</label>
                            <input type="text" id="program_section_title" name="program_section_title"
                                value="{{ cache('site_setting_program_section_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="3 Program Peminatan">
                        </div>
                        <div>
                            <label for="program_ipa_title" class="block text-sm font-medium text-gray-700 mb-2">Judul
                                Program IPA</label>
                            <input type="text" id="program_ipa_title" name="program_ipa_title"
                                value="{{ cache('site_setting_program_ipa_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="PEMINATAN ILMU PENGETAHUAN ALAM (IPA)">
                        </div>
                        <div>
                            <label for="program_ipa_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Program IPA</label>
                            <textarea id="program_ipa_description" name="program_ipa_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Menyiapkan peserta didik yang handal dalam kajian ilmiah dan alamiah...">{{ cache('site_setting_program_ipa_description') }}</textarea>
                        </div>
                        <div>
                            <label for="program_ips_title" class="block text-sm font-medium text-gray-700 mb-2">Judul
                                Program IPS</label>
                            <input type="text" id="program_ips_title" name="program_ips_title"
                                value="{{ cache('site_setting_program_ips_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="PEMINATAN ILMU PENGETAHUAN SOSIAL (IPS)">
                        </div>
                        <div>
                            <label for="program_ips_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Program IPS</label>
                            <textarea id="program_ips_description" name="program_ips_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Menyiapkan peserta didik yang dapat menguasai ilmu-ilmu sosial...">{{ cache('site_setting_program_ips_description') }}</textarea>
                        </div>
                        <div>
                            <label for="program_religion_title"
                                class="block text-sm font-medium text-gray-700 mb-2">Judul Program Keagamaan</label>
                            <input type="text" id="program_religion_title" name="program_religion_title"
                                value="{{ cache('site_setting_program_religion_title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="PEMINATAN KEAGAMAAN">
                        </div>
                        <div>
                            <label for="program_religion_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Program
                                Keagamaan</label>
                            <textarea id="program_religion_description" name="program_religion_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Menyiapkan peserta didik yang lebih mampu menguasai ilmu-ilmu agama...">{{ cache('site_setting_program_religion_description') }}</textarea>
                        </div>
                        <div>
                            <label for="program_section_image"
                                class="block text-sm font-medium text-gray-700 mb-2">Gambar Section Program
                                Peminatan</label>
                            <input type="file" id="program_section_image" name="program_section_image"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @if (cache('site_setting_program_section_image'))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-1">Gambar saat ini:</p>
                                    <img src="{{ Storage::url(cache('site_setting_program_section_image')) }}"
                                        alt="Current Program Section Image" class="h-24 w-auto rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">About Section</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="about_section_title"
                                class="block text-sm font-medium text-gray-700 mb-2">Judul Section About</label>
                            <input type="text" id="about_section_title" name="about_section_title"
                                value="{{ cache('site_setting_about_section_title', 'Portal Digital Pendidikan Terintegrasi') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Portal Digital Pendidikan Terintegrasi">
                        </div>

                        <div>
                            <label for="about_section_subtitle"
                                class="block text-sm font-medium text-gray-700 mb-2">Subtitle About</label>
                            <input type="text" id="about_section_subtitle" name="about_section_subtitle"
                                value="{{ cache('site_setting_about_section_subtitle', 'TENTANG KAMI') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="TENTANG KAMI">
                        </div>

                        <div>
                            <label for="about_section_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi About</label>
                            <textarea id="about_section_description" name="about_section_description" rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Website sekolah yang mengintegrasikan semua layanan pendidikan dalam satu platform digital yang modern dan efisien. Memudahkan akses informasi dan layanan untuk seluruh civitas akademika.">{{ cache('site_setting_about_section_description', 'Website sekolah yang mengintegrasikan semua layanan pendidikan dalam satu platform digital yang modern dan efisien. Memudahkan akses informasi dan layanan untuk seluruh civitas akademika.') }}</textarea>
                        </div>

                        <!-- About Images -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar About Section</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="about_image_1"
                                        class="block text-sm font-medium text-gray-600 mb-1">Gambar 1 (Kiri
                                        Atas)</label>
                                    <input type="file" id="about_image_1" name="about_image_1" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @if (cache('site_setting_about_image_1'))
                                        <div class="mt-2">
                                            <img src="{{ Storage::url(cache('site_setting_about_image_1')) }}"
                                                alt="About Image 1" class="h-16 w-auto rounded">
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label for="about_image_2"
                                        class="block text-sm font-medium text-gray-600 mb-1">Gambar 2 (Kanan
                                        Atas)</label>
                                    <input type="file" id="about_image_2" name="about_image_2" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @if (cache('site_setting_about_image_2'))
                                        <div class="mt-2">
                                            <img src="{{ Storage::url(cache('site_setting_about_image_2')) }}"
                                                alt="About Image 2" class="h-16 w-auto rounded">
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label for="about_image_3"
                                        class="block text-sm font-medium text-gray-600 mb-1">Gambar 3 (Kanan
                                        Bawah)</label>
                                    <input type="file" id="about_image_3" name="about_image_3" accept="image/*"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @if (cache('site_setting_about_image_3'))
                                        <div class="mt-2">
                                            <img src="{{ Storage::url(cache('site_setting_about_image_3')) }}"
                                                alt="About Image 3" class="h-16 w-auto rounded">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- About Features -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Fitur About (4 fitur
                                utama)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div>
                                        <label for="about_feature_1_title"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 1 -
                                            Judul</label>
                                        <input type="text" id="about_feature_1_title" name="about_feature_1_title"
                                            value="{{ cache('site_setting_about_feature_1_title', 'SISTEM E-OSIS') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="SISTEM E-OSIS">
                                    </div>
                                    <div>
                                        <label for="about_feature_1_description"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 1 -
                                            Deskripsi</label>
                                        <textarea id="about_feature_1_description" name="about_feature_1_description" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Pemilihan OSIS digital dengan monitoring real-time dan sistem voting yang aman">{{ cache('site_setting_about_feature_1_description', 'Pemilihan OSIS digital dengan monitoring real-time dan sistem voting yang aman') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="about_feature_2_title"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 2 -
                                            Judul</label>
                                        <input type="text" id="about_feature_2_title" name="about_feature_2_title"
                                            value="{{ cache('site_setting_about_feature_2_title', 'SISTEM E-LULUS') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="SISTEM E-LULUS">
                                    </div>
                                    <div>
                                        <label for="about_feature_2_description"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 2 -
                                            Deskripsi</label>
                                        <textarea id="about_feature_2_description" name="about_feature_2_description" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Pengumuman kelulusan dengan verifikasi NISN/NIS yang akurat dan real-time">{{ cache('site_setting_about_feature_2_description', 'Pengumuman kelulusan dengan verifikasi NISN/NIS yang akurat dan real-time') }}</textarea>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label for="about_feature_3_title"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 3 -
                                            Judul</label>
                                        <input type="text" id="about_feature_3_title" name="about_feature_3_title"
                                            value="{{ cache('site_setting_about_feature_3_title', 'MANAJEMEN SARPRAS') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="MANAJEMEN SARPRAS">
                                    </div>
                                    <div>
                                        <label for="about_feature_3_description"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 3 -
                                            Deskripsi</label>
                                        <textarea id="about_feature_3_description" name="about_feature_3_description" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Sistem inventaris sarana dan prasarana sekolah dengan barcode tracking">{{ cache('site_setting_about_feature_3_description', 'Sistem inventaris sarana dan prasarana sekolah dengan barcode tracking') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="about_feature_4_title"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 4 -
                                            Judul</label>
                                        <input type="text" id="about_feature_4_title" name="about_feature_4_title"
                                            value="{{ cache('site_setting_about_feature_4_title', 'INTEGRASI INSTAGRAM') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="INTEGRASI INSTAGRAM">
                                    </div>
                                    <div>
                                        <label for="about_feature_4_description"
                                            class="block text-sm font-medium text-gray-600 mb-1">Fitur 4 -
                                            Deskripsi</label>
                                        <textarea id="about_feature_4_description" name="about_feature_4_description" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            placeholder="Sinkronisasi otomatis dengan Instagram sekolah untuk galeri kegiatan terbaru">{{ cache('site_setting_about_feature_4_description', 'Sinkronisasi otomatis dengan Instagram sekolah untuk galeri kegiatan terbaru') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- About Button -->
                        <div>
                            <label for="about_button_text" class="block text-sm font-medium text-gray-700 mb-2">Teks
                                Button About</label>
                            <input type="text" id="about_button_text" name="about_button_text"
                                value="{{ cache('site_setting_about_button_text', 'JELAJAHI FITUR') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="JELAJAHI FITUR">
                        </div>

                        <!-- Hubungi Kami Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Hubungi Kami Section</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="about_contact_text"
                                        class="block text-sm font-medium text-gray-600 mb-1">Teks "Hubungi
                                        Kami"</label>
                                    <input type="text" id="about_contact_text" name="about_contact_text"
                                        value="{{ cache('site_setting_about_contact_text', 'HUBUNGI KAMI') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="HUBUNGI KAMI">
                                </div>
                                <div>
                                    <label for="about_contact_phone"
                                        class="block text-sm font-medium text-gray-600 mb-1">Nomor Telepon</label>
                                    <input type="text" id="about_contact_phone" name="about_contact_phone"
                                        value="{{ cache('site_setting_about_contact_phone', '+62 123 456 789') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="+62 123 456 789">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campus Life Section (Headmaster) -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Campus Life Section (Kepala Madrasah)</h2>
                    <p class="text-sm text-gray-600 mb-4">Section ini khusus untuk menampilkan informasi kepala sekolah di bagian Campus Life di landing page. Jika dikosongkan, akan menggunakan data dari "Informasi Kepala Sekolah" di atas.</p>
                    <div class="space-y-6">
                        <div>
                            <label for="campus_life_headmaster_name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                Kepala Madrasah (Campus Life)</label>
                            <input type="text" id="campus_life_headmaster_name" name="campus_life_headmaster_name"
                                value="{{ cache('site_setting_campus_life_headmaster_name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Kosongkan untuk menggunakan data dari Informasi Kepala Sekolah">
                        </div>

                        <div>
                            <label for="campus_life_headmaster_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kepala Madrasah (Campus Life)</label>
                            <textarea id="campus_life_headmaster_description" name="campus_life_headmaster_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Kosongkan untuk menggunakan data dari Informasi Kepala Sekolah">{{ cache('site_setting_campus_life_headmaster_description') }}</textarea>
                        </div>

                        <div>
                            <label for="campus_life_headmaster_vision" class="block text-sm font-medium text-gray-700 mb-2">Visi
                                Kepala Madrasah (Campus Life)</label>
                            <textarea id="campus_life_headmaster_vision" name="campus_life_headmaster_vision" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Kosongkan untuk menggunakan data dari Informasi Kepala Sekolah">{{ cache('site_setting_campus_life_headmaster_vision') }}</textarea>
                        </div>

                        <div>
                            <label for="campus_life_headmaster_photo" class="block text-sm font-medium text-gray-700 mb-2">Foto
                                Kepala Madrasah (Campus Life)</label>
                            <input type="file" id="campus_life_headmaster_photo" name="campus_life_headmaster_photo" accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-sm text-gray-500 mt-1">Kosongkan untuk menggunakan foto dari Informasi Kepala Sekolah</p>
                            @if (cache('site_setting_campus_life_headmaster_photo'))
                                <div class="mt-2">
                                    <img src="{{ Storage::url(cache('site_setting_campus_life_headmaster_photo')) }}"
                                        alt="Foto Kepala Madrasah (Campus Life)" class="h-32 w-auto rounded-lg border">
                                    <p class="text-sm text-gray-500 mt-1">Foto saat ini (Campus Life)</p>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-1">Belum ada foto khusus. Akan menggunakan foto dari Informasi Kepala Sekolah.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Footer</h2>
                    <div>
                        <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-2">Footer
                            Text</label>
                        <textarea id="footer_text" name="footer_text" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="© 2024 MAUDU REJOSO. All rights reserved.">{{ cache('site_setting_footer_text') }}</textarea>
                    </div>
                </div>

                <!-- Menu Management -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Menu Management</h2>

                    <!-- Header Menus -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Header Menus</h3>
                        @if ($headerMenus->count() > 0)
                            <div class="space-y-2">
                                @foreach ($headerMenus as $menu)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium">{{ $menu->title }}</span>
                                            <span class="text-sm text-gray-500 ml-2">({{ $menu->slug }})</span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.pages.edit', $menu->id) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-sm text-gray-500">Order:
                                                {{ $menu->menu_sort_order }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No header menus found. <a
                                    href="{{ route('admin.pages.create') }}"
                                    class="text-blue-600 hover:text-blue-800">Create a new page</a></p>
                        @endif
                    </div>

                    <!-- Footer Menus -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-3">Footer Menus</h3>
                        @if ($footerMenus->count() > 0)
                            <div class="space-y-2">
                                @foreach ($footerMenus as $menu)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <span class="font-medium">{{ $menu->title }}</span>
                                            <span class="text-sm text-gray-500 ml-2">({{ $menu->slug }})</span>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.pages.edit', $menu->id) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-sm text-gray-500">Order:
                                                {{ $menu->menu_sort_order }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No footer menus found. <a
                                    href="{{ route('admin.pages.create') }}"
                                    class="text-blue-600 hover:text-blue-800">Create a new page</a></p>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.pages.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Create New Page/Menu
                        </a>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between">
                    <form action="{{ route('admin.settings.landing-page.reset') }}" method="POST" class="inline"
                        data-confirm="Apakah Anda yakin ingin mengembalikan semua setting ke default? Tindakan ini tidak dapat dibatalkan.">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            {{ __('common.reset_to_default') }}
                        </button>
                    </form>

                    <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        {{ __('common.save_settings') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Wait for Sweet Alert functions to be available
        function initLandingPageMessages() {
            // Check if Sweet Alert functions are available
            if (typeof showSuccess === 'undefined' || typeof showError === 'undefined') {
                // Retry after a short delay
                setTimeout(initLandingPageMessages, 100);
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
            document.addEventListener('DOMContentLoaded', initLandingPageMessages);
        } else {
            // DOM is already ready
            initLandingPageMessages();
        }

        // Limit hero images to maximum 5
        const heroImagesInput = document.getElementById('hero_images');
        if (heroImagesInput) {
            heroImagesInput.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files.length > 5) {
                    // Use SweetAlert if available, otherwise use alert
                    if (typeof showError !== 'undefined') {
                        showError('Error!', 'Maksimal 5 gambar untuk hero carousel');
                    } else {
                        alert('Maksimal 5 gambar untuk hero carousel');
                    }
                    e.target.value = '';
                    return;
                }

                // Show preview of selected images
                const previewContainer = document.createElement('div');
                previewContainer.className = 'mt-4';
                previewContainer.innerHTML =
                    '<p class="text-sm text-gray-600 mb-2">Preview gambar yang dipilih:</p><div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="preview-images"></div>';

                // Remove existing preview
                const existingPreview = document.getElementById('preview-images');
                if (existingPreview) {
                    existingPreview.parentElement.remove();
                }

                // Add new preview
                this.parentElement.appendChild(previewContainer);

                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('div');
                            img.className = 'relative';
                            img.innerHTML = `
                                <img src="${e.target.result}" alt="Preview ${index + 1}" class="h-24 w-full object-cover rounded">
                                <div class="absolute top-1 right-1 bg-blue-500 bg-opacity-75 text-white text-xs px-1 rounded">
                                    ${index + 1}
                                </div>
                            `;
                            document.getElementById('preview-images').appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        }

        // Handle form reset confirmation with SweetAlert
        const resetForm = document.querySelector('form[action*="reset"]');
        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const confirmMessage = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin mengembalikan semua setting ke default? Tindakan ini tidak dapat dibatalkan.';
                
                if (typeof showConfirm !== 'undefined') {
                    showConfirm('Konfirmasi Reset', confirmMessage, 'Ya, Reset', 'Batal').then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm(confirmMessage)) {
                        form.submit();
                    }
                }
            });
        }
    </script>
</x-app-layout>
