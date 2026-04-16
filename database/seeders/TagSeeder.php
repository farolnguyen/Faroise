<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['sleep', 'focus', 'relax', 'meditate', 'study', 'heavy', 'light', 'lofi'];

        foreach ($tags as $tag) {
            Tag::create(['name' => ucfirst($tag), 'slug' => $tag]);
        }
    }
}
