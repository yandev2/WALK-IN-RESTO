<?php

namespace Database\Seeders;

use App\Models\LandingTemplate;
use Illuminate\Database\Seeder;

class LandingTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'classic',
                'name' => 'Classic Elegant',
                'description' => 'Desain bernuansa hangat dan elegan, ideal untuk resto, cafe & bistro santai.',
                'badge' => 'Default',
                'thumbnail_path' => 'images/landing/templates/classic.webp',
                'view_path' => 'landing.templates.classic.show',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'foodie',
                'name' => 'Foodie Delight',
                'description' => 'Desain modern, ceria, dan berfokus pada foto menu terlaris untuk memikat selera makan.',
                'badge' => 'Populer',
                'thumbnail_path' => 'images/landing/templates/foodie.webp',
                'view_path' => 'landing.templates.foodie.show',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'glassmorphism',
                'name' => 'Glassmorphism iOS',
                'description' => 'Desain ultra-modern dengan efek frosted glass transparan, ambient gradient glow, dan komponen elegan ala iOS.',
                'badge' => 'Eksklusif',
                'thumbnail_path' => 'images/landing/templates/glassmorphism.webp',
                'view_path' => 'landing.templates.glassmorphism.show',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($templates as $template) {
            LandingTemplate::query()->updateOrCreate(
                ['slug' => $template['slug']],
                $template,
            );
        }
    }
}
