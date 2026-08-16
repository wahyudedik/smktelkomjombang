<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Page;
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
        // Support theme switching via query parameter
        $availableThemes = ThemeSetting::getRegisteredThemes();
        if ($request->has('theme') && array_key_exists($request->theme, $availableThemes)) {
            session(['admin_theme_override' => $request->theme]);
        }

        $theme = current_theme();

        // Performance: single query with eager loading instead of 3 separate queries
        $pages = Page::where('is_menu', true)
            ->with('children')
            ->orderBy('menu_sort_order')
            ->get();

        $headerMenus = $pages->where('menu_position', 'header')->whereNull('parent_id');
        $footerMenus = $pages->where('menu_position', 'footer')->whereNull('parent_id');

        // ⭐ Load settings per active theme from theme_config()
        $settings = theme_config() ?: [];

        return view('settings.landing-page', compact(
            'pages', 'headerMenus', 'footerMenus',
            'settings', 'availableThemes'
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
            'contact_address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
            'social_whatsapp' => 'nullable|url',
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
            'contact_address' => $request->contact_address,
            'social_facebook' => $request->social_facebook,
            'social_instagram' => $request->social_instagram,
            'social_youtube' => $request->social_youtube,
            'social_whatsapp' => $request->social_whatsapp,
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
        ];

        // Handle file uploads with old file deletion
        try {
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                $oldLogo = cache('site_setting_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                $logoPath = $request->file('logo')->store('site-assets', 'public');
                $settings['logo'] = $logoPath;
            }

            if ($request->hasFile('program_section_image')) {
                // Delete old image if exists
                $oldImage = cache('site_setting_program_section_image');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $programImagePath = $request->file('program_section_image')->store('site-assets/program', 'public');
                $settings['program_section_image'] = $programImagePath;
            }

            if ($request->hasFile('favicon')) {
                // Delete old favicon if exists
                $oldFavicon = cache('site_setting_favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
                $faviconPath = $request->file('favicon')->store('site-assets', 'public');
                $settings['favicon'] = $faviconPath;
            }

            if ($request->hasFile('hero_images')) {
                // Delete old hero images if exists
                $oldHeroImages = cache('site_setting_hero_images');
                if ($oldHeroImages) {
                    $oldImagesArray = json_decode($oldHeroImages, true);
                    if (is_array($oldImagesArray)) {
                        foreach ($oldImagesArray as $oldImage) {
                            if (Storage::disk('public')->exists($oldImage)) {
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
                // Delete old thumbnail if exists
                $oldThumbnail = cache('site_setting_video_thumbnail');
                if ($oldThumbnail && Storage::disk('public')->exists($oldThumbnail)) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
                $videoThumbnailPath = $request->file('video_thumbnail')->store('site-assets/video', 'public');
                $settings['video_thumbnail'] = $videoThumbnailPath;
            }

            if ($request->hasFile('headmaster_photo')) {
                // Delete old photo if exists
                $oldPhoto = cache('site_setting_headmaster_photo');
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
                $headmasterPhotoPath = $request->file('headmaster_photo')->store('site-assets/headmaster', 'public');
                $settings['headmaster_photo'] = $headmasterPhotoPath;
            }

            if ($request->hasFile('campus_life_headmaster_photo')) {
                // Delete old photo if exists
                $oldPhoto = cache('site_setting_campus_life_headmaster_photo');
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
                $campusLifePhotoPath = $request->file('campus_life_headmaster_photo')->store('site-assets/headmaster', 'public');
                $settings['campus_life_headmaster_photo'] = $campusLifePhotoPath;
            }

            // Handle About Section Images
            if ($request->hasFile('about_image_1')) {
                $oldImage = cache('site_setting_about_image_1');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutImage1Path = $request->file('about_image_1')->store('site-assets/about', 'public');
                $settings['about_image_1'] = $aboutImage1Path;
            }

            if ($request->hasFile('about_image_2')) {
                $oldImage = cache('site_setting_about_image_2');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
                $aboutImage2Path = $request->file('about_image_2')->store('site-assets/about', 'public');
                $settings['about_image_2'] = $aboutImage2Path;
            }

            if ($request->hasFile('about_image_3')) {
                $oldImage = cache('site_setting_about_image_3');
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
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
            'contact_email', 'contact_phone', 'contact_address',
            'contact_section_subtitle', 'contact_section_title', 'contact_section_description',
            'contact_map_url', 'contact_operational_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_whatsapp',
            'video_url', 'video_thumbnail',
        ];

        foreach ($landingPageKeys as $key) {
            ThemeSetting::where('theme', $theme)->where('key', $key)->delete();
        }

        ThemeSetting::clearCache($theme);

        return redirect()->back()->with('success', "Settings tema [{$theme}] berhasil direset ke default!");
    }
}
