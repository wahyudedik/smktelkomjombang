<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1000, 999999),
            'content' => '<p>' . fake()->paragraph() . '</p>',
            'excerpt' => fake()->sentence(),
            'featured_image' => null,
            'category' => null,
            'template' => fake()->randomElement(['default', 'about', 'blog', 'contact', 'gallery', 'landing']),
            'seo_meta' => null,
            'custom_fields' => null,
            'status' => 'draft',
            'is_featured' => false,
            'is_menu' => false,
            'menu_title' => null,
            'menu_position' => 'header',
            'theme' => null,
            'parent_id' => null,
            'menu_icon' => null,
            'menu_url' => null,
            'menu_target_blank' => false,
            'menu_sort_order' => 0,
            'sort_order' => 0,
            'published_at' => null,
            'user_id' => User::factory(),
        ];
    }
}
