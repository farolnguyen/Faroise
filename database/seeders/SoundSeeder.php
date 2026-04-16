<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Sound;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sounds = [
            [
                'category' => 'rain',
                'name' => 'Heavy Rain',
                'slug' => 'heavy-rain',
                'icon' => '💧',
                'color' => '#2d6a9f',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/212/212.mp3',
                'tags' => ['sleep', 'heavy'],
            ],
            [
                'category' => 'rain',
                'name' => 'Light Rain',
                'slug' => 'light-rain',
                'icon' => '🌦️',
                'color' => '#5b9bd5',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/213/213.mp3',
                'tags' => ['sleep', 'relax', 'light'],
            ],
            [
                'category' => 'nature',
                'name' => 'Forest Birds',
                'slug' => 'forest-birds',
                'icon' => '🐦',
                'color' => '#4a7c59',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/2458/2458.mp3',
                'tags' => ['relax', 'focus'],
            ],
            [
                'category' => 'nature',
                'name' => 'Ocean Waves',
                'slug' => 'ocean-waves',
                'icon' => '🌊',
                'color' => '#0077b6',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/2515/2515.mp3',
                'tags' => ['sleep', 'relax', 'meditate'],
            ],
            [
                'category' => 'cozy',
                'name' => 'Fireplace',
                'slug' => 'fireplace',
                'icon' => '🔥',
                'color' => '#c44b1b',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/1247/1247.mp3',
                'tags' => ['sleep', 'relax', 'cozy'],
            ],
            [
                'category' => 'cozy',
                'name' => 'Coffee Shop',
                'slug' => 'coffee-shop',
                'icon' => '☕',
                'color' => '#6f4e37',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/2462/2462.mp3',
                'tags' => ['focus', 'study', 'lofi'],
            ],
            [
                'category' => 'noise',
                'name' => 'White Noise',
                'slug' => 'white-noise',
                'icon' => '📡',
                'color' => '#aaaaaa',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/2019/2019.mp3',
                'tags' => ['sleep', 'focus', 'heavy'],
            ],
            [
                'category' => 'urban',
                'name' => 'City Traffic',
                'slug' => 'city-traffic',
                'icon' => '🚗',
                'color' => '#555555',
                'source_type' => 'external',
                'external_url' => 'https://assets.mixkit.co/active_storage/sfx/1196/1196.mp3',
                'tags' => ['focus', 'study'],
            ],
        ];

        foreach ($sounds as $i => $data) {
            $category = Category::where('slug', $data['category'])->first();
            $sound = Sound::create([
                'category_id'  => $category->id,
                'name'         => $data['name'],
                'slug'         => $data['slug'],
                'icon'         => $data['icon'],
                'color'        => $data['color'],
                'source_type'  => $data['source_type'],
                'external_url' => $data['external_url'] ?? null,
                'file_path'    => $data['file_path'] ?? null,
                'is_active'    => true,
                'sort_order'   => $i,
            ]);

            $tagIds = Tag::whereIn('slug', $data['tags'])->pluck('id');
            $sound->tags()->sync($tagIds);
        }
    }
}
