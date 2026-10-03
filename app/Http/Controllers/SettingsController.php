<?php

namespace App\Http\Controllers;

use App\Services\ContentSanitizer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Page;
use App\Providers\MenuServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\ThemeSetting;

class SettingsController extends Controller
{
    /**
     * Display system settings page
     */
    public function index()
    {
        $kelasCount = DB::table('kelas')->count();
        $jurusanCount = DB::table('jurusan')->count();
        $ekstrakurikulerCount = DB::table('ekstrakurikuler')->count();
        $usersCount = DB::table('users')->count();

        return view('settings.index', compact('kelasCount', 'jurusanCount', 'ekstrakurikulerCount', 'usersCount'));
    }

    /**
     * Display data management page
     */
    public function dataManagement()
    {
        // Redirect to DataManagementController
        return app(DataManagementController::class)->index();
    }

    /**
     * Display kelas & jurusan management page
     */
    public function kelasJurusan()
    {
        $kelas = DB::table('kelas')->orderBy('nama')->get();
        $jurusan = DB::table('jurusan')->orderBy('nama')->get();

        return view('settings.kelas-jurusan', compact('kelas', 'jurusan'));
    }

    /**
     * Display landing page settings
     */
    public function landingPage(Request $request)
    {
        $availableThemes = ThemeSetting::getRegisteredThemes();

        $theme = current_theme();

        // ⭐ Filter pages by active theme (include global null-theme pages)
        $pages = Page::where('is_menu', true)
            ->where(function ($query) use ($theme) {
                $query->where('theme', $theme)
                      ->orWhereNull('theme');
            })
            ->with('children')
            ->orderBy('menu_sort_order')
            ->get();

        $headerMenus = $pages->where('menu_position', 'header')->whereNull('parent_id');
        $footerMenus = $pages->where('menu_position', 'footer')->whereNull('parent_id');

        // ⭐ Quick Menu Manager — nama variabel dibedakan dari $headerMenus/$footerMenus karena
        // View::composer (MenuServiceProvider) menimpa key tersebut saat render (hanya data published).
        $adminHeaderMenus = $headerMenus;
        $adminFooterMenus = $footerMenus;

        // Halaman yang bisa dijadikan menu (is_menu=false, published) — maks 200, urut judul
        $menuAddablePages = Page::where('is_menu', false)
            ->where('status', 'published')
            ->orderBy('title')
            ->limit(200)
            ->get();

        // ⭐ Load settings per active theme from theme_config()
        $settings = theme_config() ?: [];

        // ⭐ Link Terkait — prefill repeater (DB → config default). Selalu array of {label, url}.
        $relatedLinks = theme_config('related_links', []);
        if (is_string($relatedLinks)) {
            $decoded = json_decode($relatedLinks, true);
            $relatedLinks = is_array($decoded) ? $decoded : [];
        }
        if (!is_array($relatedLinks)) {
            $relatedLinks = [];
        }
        $relatedLinks = array_values(array_filter($relatedLinks, 'is_array'));
        $relatedLinks = array_map(static fn (array $link): array => [
            'label' => (string) ($link['label'] ?? ''),
            'url' => (string) ($link['url'] ?? ''),
        ], $relatedLinks);

        // ⭐ Jurusan Footer — prefill repeater (DB key `jurusan_links`; fallback map dari config `jurusan` lama).
        // Struktur per item: {label, url}. Fallback mempertahankan tampilan default
        // (label = full_name/name, url = '#rs-services', urutan tampilan footer saat ini).
        $jurusanLinks = theme_config('jurusan_links', null);
        if (is_string($jurusanLinks)) {
            $jurusanLinksDecoded = json_decode($jurusanLinks, true);
            $jurusanLinks = is_array($jurusanLinksDecoded) ? $jurusanLinksDecoded : null;
        }
        if (!is_array($jurusanLinks)) {
            // Belum pernah disimpan via admin → tampilkan data default config `jurusan`
            $jurusanLinks = array_map(
                static fn (array $j): array => [
                    'label' => (string) ($j['full_name'] ?? $j['name'] ?? ''),
                    'url' => '#rs-services',
                ],
                array_reverse(theme_config('jurusan', []))
            );
        }
        $jurusanLinks = array_values(array_filter($jurusanLinks, 'is_array'));
        $jurusanLinks = array_map(static fn (array $item): array => [
            'label' => is_array($item['label'] ?? null) ? '' : (string) ($item['label'] ?? $item['full_name'] ?? $item['name'] ?? ''),
            'url' => is_array($item['url'] ?? null) ? '' : (string) ($item['url'] ?? ''),
        ], $jurusanLinks);

        return view('settings.landing-page', compact(
            'pages', 'headerMenus', 'footerMenus',
            'adminHeaderMenus', 'adminFooterMenus', 'menuAddablePages',
            'settings', 'availableThemes', 'relatedLinks', 'jurusanLinks'
        ));
    }

    /**
     * Update landing page settings
     */
    public function updateLandingPage(Request $request)
    {
        $theme = current_theme(); // ⭐ Per-theme settings

        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_keywords' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg|max:512',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'hero_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_text' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_phone_secondary' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'social_whatsapp' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'pinterest_url' => 'nullable|url',
            'google_maps_url' => 'nullable|url',
            'video_url' => 'nullable|url',
            'video_thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'headmaster_name' => 'nullable|string|max:255',
            'headmaster_description' => 'nullable|string',
            'headmaster_vision' => 'nullable|string',
            'headmaster_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Campus Life Section (can override headmaster info for campus life section)
            'campus_life_headmaster_name' => 'nullable|string|max:255',
            'campus_life_headmaster_description' => 'nullable|string',
            'campus_life_headmaster_vision' => 'nullable|string',
            'campus_life_headmaster_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'program_section_title' => 'nullable|string|max:255',
            'program_ipa_title' => 'nullable|string|max:255',
            'program_ipa_description' => 'nullable|string',
            'program_ips_title' => 'nullable|string|max:255',
            'program_ips_description' => 'nullable|string',
            'program_religion_title' => 'nullable|string|max:255',
            'program_religion_description' => 'nullable|string',
            'program_section_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // About Section
            'about_section_title' => 'nullable|string|max:255',
            'about_section_subtitle' => 'nullable|string|max:255',
            'about_section_description' => 'nullable|string',
            'about_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about_image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'about_feature_1_title' => 'nullable|string|max:255',
            'about_feature_1_description' => 'nullable|string',
            'about_feature_2_title' => 'nullable|string|max:255',
            'about_feature_2_description' => 'nullable|string',
            'about_feature_3_title' => 'nullable|string|max:255',
            'about_feature_3_description' => 'nullable|string',
            'about_feature_4_title' => 'nullable|string|max:255',
            'about_feature_4_description' => 'nullable|string',
            'about_button_text' => 'nullable|string|max:255',
            'about_contact_text' => 'nullable|string|max:255',
            'about_contact_phone' => 'nullable|string|max:255',
            // Hero Slides
            'hero_slide1_subtitle' => 'nullable|string|max:255',
            'hero_slide1_title' => 'nullable|string|max:255',
            'hero_slide1_description' => 'nullable|string',
            'hero_slide2_subtitle' => 'nullable|string|max:255',
            'hero_slide2_title' => 'nullable|string|max:255',
            'hero_slide2_description' => 'nullable|string',
            'hero_slide3_subtitle' => 'nullable|string|max:255',
            'hero_slide3_title' => 'nullable|string|max:255',
            'hero_slide3_description' => 'nullable|string',
            // Feature Cards
            'feature1_title' => 'nullable|string|max:255',
            'feature1_description' => 'nullable|string',
            'feature2_title' => 'nullable|string|max:255',
            'feature2_description' => 'nullable|string',
            'feature3_title' => 'nullable|string|max:255',
            'feature3_description' => 'nullable|string',
            // Counter Section
            'counter1_number' => 'nullable|integer',
            'counter1_label' => 'nullable|string|max:255',
            'counter2_number' => 'nullable|integer',
            'counter2_label' => 'nullable|string|max:255',
            'counter3_number' => 'nullable|integer',
            'counter3_label' => 'nullable|string|max:255',
            // Gallery Section
            'gallery_title' => 'nullable|string|max:255',
            'gallery_subtitle' => 'nullable|string|max:255',
            // CTA Section
            'cta_title' => 'nullable|string|max:255',
            'cta_description' => 'nullable|string',
            'cta_button_text' => 'nullable|string|max:255',
            'cta_button_url' => 'nullable|url',
            'cta_video_title' => 'nullable|string|max:255',
            // Program Subtitle
            'program_section_subtitle' => 'nullable|string|max:255',
            // Contact Map & Hours
            'contact_map_url' => 'nullable|string',
            'contact_operational_hours' => 'nullable|string|max:255',
            // Contact Section Titles
            'contact_section_subtitle' => 'nullable|string|max:255',
            'contact_section_title' => 'nullable|string|max:255',
            'contact_section_description' => 'nullable|string|max:500',
            // ⭐ Link Terkait (repeater) — tanpa rule 'url' ketat agar sintaks route:/#anchor//path diterima
            'related_links' => ['nullable', 'array'],
            'related_links.*.label' => ['required_with:related_links', 'string', 'max:255'],
            'related_links.*.url' => ['required_with:related_links', 'string', 'max:500'],
            // ⭐ Jurusan Footer (repeater) — pola sama dengan Link Terkait
            'jurusan_links' => ['nullable', 'array'],
            'jurusan_links.*.label' => ['required_with:jurusan_links', 'string', 'max:255'],
            'jurusan_links.*.url' => ['required_with:jurusan_links', 'string', 'max:500'],
        ]);

        // Update site settings (you can create a settings table or use config)
        $settings = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'site_keywords' => $request->site_keywords,
            'hero_title' => $request->hero_title,
            'hero_subtitle' => $request->hero_subtitle,
            'footer_text' => $request->footer_text,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_phone_secondary' => $request->contact_phone_secondary,
            'contact_address' => $request->contact_address,
            // ⭐ Sinkron: simpan dengan key yang dibaca header/footer (facebook_url, dll)
            // Backward compat: tetap simpan key lama (social_*) juga
            'facebook_url' => $request->social_facebook,
            'instagram_url' => $request->social_instagram,
            'youtube_url' => $request->social_youtube,
            'whatsapp_url' => $request->social_whatsapp,
            'social_facebook' => $request->social_facebook,
            'social_instagram' => $request->social_instagram,
            'social_youtube' => $request->social_youtube,
            'social_whatsapp' => $request->social_whatsapp,
            // Social media tambahan
            'twitter_url' => $request->twitter_url,
            'tiktok_url' => $request->tiktok_url,
            'pinterest_url' => $request->pinterest_url,
            // Contact extras
            'google_maps_url' => $request->google_maps_url,
            'video_url' => $request->video_url,
            'headmaster_name' => $request->headmaster_name,
            'headmaster_description' => $request->headmaster_description,
            'headmaster_vision' => $request->headmaster_vision,
            // Campus Life Section
            'campus_life_headmaster_name' => $request->campus_life_headmaster_name,
            'campus_life_headmaster_description' => $request->campus_life_headmaster_description,
            'campus_life_headmaster_vision' => $request->campus_life_headmaster_vision,
            'program_section_title' => $request->program_section_title,
            'program_ipa_title' => $request->program_ipa_title,
            'program_ipa_description' => $request->program_ipa_description,
            'program_ips_title' => $request->program_ips_title,
            'program_ips_description' => $request->program_ips_description,
            'program_religion_title' => $request->program_religion_title,
            'program_religion_description' => $request->program_religion_description,
            // About Section
            'about_section_title' => $request->about_section_title,
            'about_section_subtitle' => $request->about_section_subtitle,
            'about_section_description' => $request->about_section_description,
            'about_feature_1_title' => $request->about_feature_1_title,
            'about_feature_1_description' => $request->about_feature_1_description,
            'about_feature_2_title' => $request->about_feature_2_title,
            'about_feature_2_description' => $request->about_feature_2_description,
            'about_feature_3_title' => $request->about_feature_3_title,
            'about_feature_3_description' => $request->about_feature_3_description,
            'about_feature_4_title' => $request->about_feature_4_title,
            'about_feature_4_description' => $request->about_feature_4_description,
            'about_button_text' => $request->about_button_text,
            'about_contact_text' => $request->about_contact_text,
            'about_contact_phone' => $request->about_contact_phone,
            // Hero Slides
            'hero_slide1_subtitle' => $request->hero_slide1_subtitle,
            'hero_slide1_title' => $request->hero_slide1_title,
            'hero_slide1_description' => $request->hero_slide1_description,
            'hero_slide2_subtitle' => $request->hero_slide2_subtitle,
            'hero_slide2_title' => $request->hero_slide2_title,
            'hero_slide2_description' => $request->hero_slide2_description,
            'hero_slide3_subtitle' => $request->hero_slide3_subtitle,
            'hero_slide3_title' => $request->hero_slide3_title,
            'hero_slide3_description' => $request->hero_slide3_description,
            // Feature Cards
            'feature1_title' => $request->feature1_title,
            'feature1_description' => $request->feature1_description,
            'feature2_title' => $request->feature2_title,
            'feature2_description' => $request->feature2_description,
            'feature3_title' => $request->feature3_title,
            'feature3_description' => $request->feature3_description,
            // Counter Section
            'counter1_number' => $request->counter1_number,
            'counter1_label' => $request->counter1_label,
            'counter2_number' => $request->counter2_number,
            'counter2_label' => $request->counter2_label,
            'counter3_number' => $request->counter3_number,
            'counter3_label' => $request->counter3_label,
            // Gallery Section
            'gallery_title' => $request->gallery_title,
            'gallery_subtitle' => $request->gallery_subtitle,
            // CTA Section
            'cta_title' => $request->cta_title,
            'cta_description' => $request->cta_description,
            'cta_button_text' => $request->cta_button_text,
            'cta_button_url' => $request->cta_button_url,
            'cta_video_title' => $request->cta_video_title,
            // Program Subtitle
            'program_section_subtitle' => $request->program_section_subtitle,
            // Contact Map & Hours
            'contact_map_url' => $request->contact_map_url,
            'contact_operational_hours' => $request->contact_operational_hours,
            // Contact Section Titles
            'contact_section_subtitle' => $request->contact_section_subtitle,
            'contact_section_title' => $request->contact_section_title,
            'contact_section_description' => $request->contact_section_description,
            // ⭐ Link Terkait (repeater label+URL) — key SELALU terkirim; 0 baris = simpan [] (kosongkan semua link)
            'related_links' => array_values(array_filter(array_map(
                static fn ($item): array => [
                    'label' => trim((string) data_get($item, 'label', '')),
                    'url' => trim((string) data_get($item, 'url', '')),
                ],
                is_array($request->input('related_links')) ? $request->input('related_links') : []
            ), static fn (array $item): bool => $item['label'] !== '' || $item['url'] !== '')),
            // ⭐ Jurusan Footer (repeater label+URL) — key SELALU terkirim; 0 baris = simpan [] (kosongkan widget)
            'jurusan_links' => array_values(array_filter(array_map(
                static fn ($item): array => [
                    'label' => trim((string) data_get($item, 'label', '')),
                    'url' => trim((string) data_get($item, 'url', '')),
                ],
                is_array($request->input('jurusan_links')) ? $request->input('jurusan_links') : []
            ), static fn (array $item): bool => $item['label'] !== '' || $item['url'] !== '')),
        ];

        // Sanitize HTML fields yang ditampilkan dengan {!! !!} di Blade templates
        // Mencegah XSS dari admin input yang mengandung tag berbahaya
        $sanitizer = new ContentSanitizer();
        $htmlFields = [
            'headmaster_description', 'headmaster_vision',
            'campus_life_headmaster_description', 'campus_life_headmaster_vision',
            'cta_description', 'footer_text',
            'hero_slide1_title', 'hero_slide2_title', 'hero_slide3_title',
            'about_section_description',
            'about_feature_1_description', 'about_feature_2_description',
            'about_feature_3_description', 'about_feature_4_description',
            'program_ipa_description', 'program_ips_description', 'program_religion_description',
            'contact_section_description',
        ];
        foreach ($htmlFields as $field) {
            if (!empty($settings[$field])) {
                $settings[$field] = $sanitizer->sanitize($settings[$field]);
            }
        }

        // Handle file uploads with old file deletion
        // ⭐ Old-file path resolution via theme_config() — cache('site_setting_*') sudah tidak diisi
        //    (data pindah ke tabel theme_settings), sehingga pembacaan cache menyebabkan file lama bocor.
        try {
            if ($request->hasFile('logo')) {
                $oldLogo = theme_config('logo');
                if (is_string($oldLogo) && $oldLogo !== '' && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                $logoPath = $request->file('logo')->store('site-assets', 'public');
                $settings['logo'] = $logoPath;
            }

            if ($request->hasFile('program_section_image')) {
                $oldImage = theme_config('program_section_image');
                if (is_string($oldImage) && $oldImage !== '' && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $programImagePath = $request->file('program_section_image')->store('site-assets/program', 'public');
                $settings['program_section_image'] = $programImagePath;
            }

            if ($request->hasFile('favicon')) {
                $oldFavicon = theme_config('favicon');
                if (is_string($oldFavicon) && $oldFavicon !== '' && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
                $faviconPath = $request->file('favicon')->store('site-assets', 'public');
                $settings['favicon'] = $faviconPath;
            }

            if ($request->hasFile('hero_images')) {
                $oldHeroImages = theme_config('hero_images');
                if ($oldHeroImages !== null && $oldHeroImages !== '') {
                    $oldImagesArray = is_array($oldHeroImages)
                        ? $oldHeroImages
                        : json_decode((string) $oldHeroImages, true);
                    if (is_array($oldImagesArray)) {
                        foreach ($oldImagesArray as $oldImage) {
                            if (is_string($oldImage) && Storage::disk('public')->exists($oldImage)) {
                                Storage::disk('public')->delete($oldImage);
                            }
                        }
                    }
                }

                $heroImagePaths = [];
                $uploadedImages = $request->file('hero_images');

                // Limit to maximum 5 images
                $maxImages = min(5, count($uploadedImages));

                for ($i = 0; $i < $maxImages; $i++) {
                    $image = $uploadedImages[$i];
                    if ($image && $image->isValid()) {
                        $heroImagePaths[] = $image->store('site-assets/hero', 'public');
                    }
                }

                if (!empty($heroImagePaths)) {
                    $settings['hero_images'] = json_encode($heroImagePaths);
                }
            }

            if ($request->hasFile('video_thumbnail')) {
                $oldThumbnail = theme_config('video_thumbnail');
                if (is_string($oldThumbnail) && $oldThumbnail !== '' && Storage::disk('public')->exists($oldThumbnail)) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
                $videoThumbnailPath = $request->file('video_thumbnail')->store('site-assets/video', 'public');
                $settings['video_thumbnail'] = $videoThumbnailPath;
            }

            if ($request->hasFile('headmaster_photo')) {
                $oldPhoto = theme_config('headmaster_photo');
                if (is_string($oldPhoto) && $oldPhoto !== '' && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
                $headmasterPhotoPath = $request->file('headmaster_photo')->store('site-assets/headmaster', 'public');
                $settings['headmaster_photo'] = $headmasterPhotoPath;
            }

            if ($request->hasFile('campus_life_headmaster_photo')) {
                $oldPhoto = theme_config('campus_life_headmaster_photo');
                if (is_string($oldPhoto) && $oldPhoto !== '' && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
                $campusLifePhotoPath = $request->file('campus_life_headmaster_photo')->store('site-assets/headmaster', 'public');
                $settings['campus_life_headmaster_photo'] = $campusLifePhotoPath;
            }

            // Handle About Section Images
            if ($request->hasFile('about_image_1')) {
                $oldImage = theme_config('about_image_1');
                if (is_string($oldImage) && $oldImage !== '' && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutImage1Path = $request->file('about_image_1')->store('site-assets/about', 'public');
                $settings['about_image_1'] = $aboutImage1Path;
            }

            if ($request->hasFile('about_image_2')) {
                $oldImage = theme_config('about_image_2');
                if (is_string($oldImage) && $oldImage !== '' && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutImage2Path = $request->file('about_image_2')->store('site-assets/about', 'public');
                $settings['about_image_2'] = $aboutImage2Path;
            }

            if ($request->hasFile('about_image_3')) {
                $oldImage = theme_config('about_image_3');
                if (is_string($oldImage) && $oldImage !== '' && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutImage3Path = $request->file('about_image_3')->store('site-assets/about', 'public');
                $settings['about_image_3'] = $aboutImage3Path;
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupload file: ' . $e->getMessage());
        }

        try {
            // ⭐ Save to theme_settings table (per-theme, not global cache)
            // Filter out null and empty values, trim strings
            $cleanedSettings = [];
            foreach ($settings as $key => $value) {
                if ($value !== null) {
                    if (is_string($value)) {
                        $trimmed = trim($value);
                        if ($trimmed !== '') {
                            $cleanedSettings[$key] = $trimmed;
                        }
                    } else {
                        $cleanedSettings[$key] = $value;
                    }
                }
            }

            if (!empty($cleanedSettings)) {
                ThemeSetting::saveThemeConfig($theme, $cleanedSettings);
            }

            // Clear theme cache so changes are reflected immediately
            ThemeSetting::clearCache($theme);
            Artisan::call('view:clear');

            return redirect()->back()->with('success', "Landing page settings untuk tema [{$theme}] berhasil diupdate!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan settings: ' . $e->getMessage());
        }
    }

    /**
     * Display SEO settings
     */
    public function seoSettings()
    {
        // Performance: cache pages for SEO settings (rarely changes)
        $pages = cache()->remember('all_pages_for_seo', 3600, fn() => Page::all());
        return view('settings.seo', compact('pages'));
    }

    /**
     * Update SEO settings
     */
    public function updateSeoSettings(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $page = Page::findOrFail($request->page_id);

        $page->update([
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'og_title' => $request->og_title,
            'og_description' => $request->og_description,
        ]);

        if ($request->hasFile('og_image')) {
            $ogImagePath = $request->file('og_image')->store('og-images', 'public');
            $page->update(['og_image' => $ogImagePath]);
        }

        return redirect()->back()->with('success', 'SEO settings updated successfully!');
    }

    /**
     * Reset landing page settings to default
     */
    public function resetLandingPage()
    {
        $theme = current_theme();

        // ⭐ Delete landing page settings for this theme only (from theme_settings table)
        $landingPageKeys = [
            'site_name', 'site_description', 'site_keywords', 'footer_text',
            'logo', 'favicon',
            'hero_title', 'hero_subtitle', 'hero_images',
            'hero_slide1_subtitle', 'hero_slide1_title', 'hero_slide1_description',
            'hero_slide2_subtitle', 'hero_slide2_title', 'hero_slide2_description',
            'hero_slide3_subtitle', 'hero_slide3_title', 'hero_slide3_description',
            'feature1_title', 'feature1_description',
            'feature2_title', 'feature2_description',
            'feature3_title', 'feature3_description',
            'about_section_title', 'about_section_subtitle', 'about_section_description',
            'about_image_1', 'about_image_2', 'about_image_3',
            'about_feature_1_title', 'about_feature_1_description',
            'about_feature_2_title', 'about_feature_2_description',
            'about_feature_3_title', 'about_feature_3_description',
            'about_feature_4_title', 'about_feature_4_description',
            'about_button_text', 'about_contact_text', 'about_contact_phone',
            'headmaster_name', 'headmaster_description', 'headmaster_vision', 'headmaster_photo',
            'campus_life_headmaster_name', 'campus_life_headmaster_description',
            'campus_life_headmaster_vision', 'campus_life_headmaster_photo',
            'program_section_title', 'program_section_subtitle',
            'program_ipa_title', 'program_ipa_description',
            'program_ips_title', 'program_ips_description',
            'program_religion_title', 'program_religion_description',
            'program_section_image',
            'counter1_number', 'counter1_label',
            'counter2_number', 'counter2_label',
            'counter3_number', 'counter3_label',
            'gallery_title', 'gallery_subtitle',
            'cta_title', 'cta_description', 'cta_button_text', 'cta_button_url', 'cta_video_title',
            'contact_email', 'contact_phone', 'contact_phone_secondary', 'contact_address',
            'contact_section_subtitle', 'contact_section_title', 'contact_section_description',
            'contact_map_url', 'contact_operational_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_whatsapp',
            'facebook_url', 'instagram_url', 'youtube_url', 'whatsapp_url',
            'twitter_url', 'tiktok_url', 'pinterest_url', 'google_maps_url',
            'video_url', 'video_thumbnail',
            'related_links',
            'jurusan_links',
        ];

        foreach ($landingPageKeys as $key) {
            ThemeSetting::where('theme', $theme)->where('key', $key)->delete();
        }

        ThemeSetting::clearCache($theme);

        return redirect()->back()->with('success', "Settings tema [{$theme}] berhasil direset ke default!");
    }

    // ========================================
    // Quick Menu Manager — CRUD menu dari landing-page settings
    // ========================================

    /**
     * Update judul menu (inline rename). menu_title kosong = fallback ke title halaman.
     */
    public function updateMenuTitle(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'menu_title' => 'nullable|string|max:255',
        ]);

        $menuTitle = $request->input('menu_title');
        $page->update([
            'menu_title' => ($menuTitle === null || trim((string) $menuTitle) === '') ? null : $menuTitle,
        ]);

        MenuServiceProvider::clearMenuCache(current_theme());

        return redirect()->back()->with('success', 'Judul menu berhasil diperbarui.');
    }

    /**
     * Toggle visibilitas menu (show/hide).
     * REVERSIBLE: saat menyembunyikan, field menu lain TIDAK direset
     * (berbeda dari PageController::update) agar mudah di-enable lagi.
     */
    public function toggleMenuVisibility(Page $page): RedirectResponse
    {
        $theme = current_theme();

        if ($page->is_menu) {
            // Sembunyikan — pertahankan menu_position, menu_sort_order, parent_id, dll
            $page->update(['is_menu' => false]);
            MenuServiceProvider::clearMenuCache($theme);

            return redirect()->back()->with('success', "Menu \"{$page->menu_title}\" disembunyikan.");
        }

        // Tampilkan — lengkapi field menu yang belum terisi
        $updates = ['is_menu' => true];

        if ($page->theme === null || $page->theme === '') {
            $updates['theme'] = $theme;
        }

        $position = $page->menu_position ?: 'header';
        if ($page->menu_position === null || $page->menu_position === '') {
            $updates['menu_position'] = $position;
        }

        if ($page->menu_sort_order === null) {
            $updates['menu_sort_order'] = $this->nextMenuSortOrder($page, $position);
        }

        $page->update($updates);
        MenuServiceProvider::clearMenuCache($theme);

        if ($page->status !== 'published') {
            return redirect()->back()->with('info', 'Halaman belum dipublish — menu tidak tampil sampai dipublish.');
        }

        return redirect()->back()->with('success', "Menu \"{$page->menu_title}\" ditampilkan.");
    }

    /**
     * Geser posisi menu (up/down) dengan menu saudara
     * (menu_position sama + parent_id sama, lintas theme).
     */
    public function moveMenu(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        $theme = current_theme();
        $direction = $request->input('direction');
        $mutated = false;

        // Scope saudara: menu_position sama + parent_id sama (NULL untuk main menu), lintas theme
        $siblings = Page::where('menu_position', $page->menu_position)
            ->where(function ($query) use ($page) {
                if ($page->parent_id === null) {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $page->parent_id);
                }
            })
            ->orderBy('menu_sort_order')
            ->orderBy('id') // tiebreaker
            ->get();

        // Normalisasi: beri urutan inkremental 1..n ke semua sibling jika ada nilai null/0 campur
        $needsNormalization = $siblings->contains(
            fn ($sibling) => $sibling->menu_sort_order === null || (int) $sibling->menu_sort_order === 0
        );

        if ($needsNormalization) {
            foreach ($siblings->values() as $index => $sibling) {
                $newOrder = $index + 1;
                if ((int) $sibling->menu_sort_order !== $newOrder) {
                    Page::whereKey($sibling->id)->update(['menu_sort_order' => $newOrder]);
                    $sibling->menu_sort_order = $newOrder;
                    $mutated = true;
                }
            }
        }

        $currentIndex = $siblings->search(fn ($sibling) => $sibling->id === $page->id);

        if ($currentIndex === false) {
            if ($mutated) {
                MenuServiceProvider::clearMenuCache($theme);
            }

            return redirect()->back()->with('error', 'Menu tidak ditemukan dalam daftar saudara.');
        }

        $neighborIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;

        if ($neighborIndex < 0 || $neighborIndex >= $siblings->count()) {
            if ($mutated) {
                MenuServiceProvider::clearMenuCache($theme);
            }

            return redirect()->back()->with(
                'info',
                $direction === 'up' ? 'Sudah di posisi teratas.' : 'Sudah di posisi terbawah.'
            );
        }

        // Tukar posisi dengan tetangga
        $current = $siblings[$currentIndex];
        $neighbor = $siblings[$neighborIndex];

        $currentOrder = $current->menu_sort_order;
        $neighborOrder = $neighbor->menu_sort_order;

        $current->update(['menu_sort_order' => $neighborOrder]);
        $neighbor->update(['menu_sort_order' => $currentOrder]);

        MenuServiceProvider::clearMenuCache($theme);

        return redirect()->back()->with('success', 'Urutan menu diperbarui.');
    }

    /**
     * Jadikan halaman yang belum jadi menu (is_menu=false) sebagai menu.
     */
    public function addPageToMenu(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'menu_position' => 'nullable|in:header,footer',
        ]);

        $theme = current_theme();

        if ($page->is_menu) {
            return redirect()->back()->with('info', 'Halaman sudah menjadi menu.');
        }

        $position = $request->input('menu_position') ?: 'header';

        $page->update([
            'is_menu' => true,
            'menu_position' => $position,
            'theme' => $page->theme ?: $theme,
            'menu_sort_order' => $this->nextMenuSortOrder($page, $position),
            // menu_title biarkan null → fallback ke title halaman
        ]);

        MenuServiceProvider::clearMenuCache($theme);

        if ($page->status !== 'published') {
            return redirect()->back()->with('info', 'Halaman belum dipublish — menu tidak tampil sampai dipublish.');
        }

        return redirect()->back()->with('success', "Halaman \"{$page->title}\" dijadikan menu {$position}.");
    }

    /**
     * Hitung menu_sort_order berikutnya untuk sibling
     * (menu_position + parent_id sama, boleh lintas theme).
     */
    private function nextMenuSortOrder(Page $page, string $menuPosition): int
    {
        $maxOrder = Page::where('menu_position', $menuPosition)
            ->where(function ($query) use ($page) {
                if ($page->parent_id === null) {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $page->parent_id);
                }
            })
            ->max('menu_sort_order');

        return ((int) $maxOrder) + 1;
    }
}
