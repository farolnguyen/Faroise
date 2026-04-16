<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Nature',      'slug' => 'nature',      'icon' => '🌿', 'description' => 'Sounds from the natural world'],
            ['name' => 'Rain',        'slug' => 'rain',        'icon' => '🌧️', 'description' => 'All kinds of rain and water sounds'],
            ['name' => 'Urban',       'slug' => 'urban',       'icon' => '🏙️', 'description' => 'City and urban ambient sounds'],
            ['name' => 'Cozy',        'slug' => 'cozy',        'icon' => '🔥', 'description' => 'Warm and cozy indoor sounds'],
            ['name' => 'Noise',       'slug' => 'noise',       'icon' => '📻', 'description' => 'White, pink and brown noise'],
            ['name' => 'Binaural',    'slug' => 'binaural',    'icon' => '🎧', 'description' => 'Binaural beats for focus and sleep'],
            ['name' => 'Instrumental','slug' => 'instrumental','icon' => '🎵', 'description' => 'Soft instrumental background music'],
        ];

        foreach ($categories as $i => $category) {
            Category::create(array_merge($category, ['sort_order' => $i]));
        }
    }
}
