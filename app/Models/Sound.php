<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sound extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'file_path', 'external_url', 'source_type',
        'icon', 'icon_path', 'color', 'is_active', 'sort_order', 'loop_start',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'sound_tag');
    }

    public function mixes()
    {
        return $this->belongsToMany(Mix::class, 'mix_sounds')->withPivot('volume');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getIconHtmlAttribute(): string
    {
        if ($this->icon_path) {
            $url = asset('storage/' . $this->icon_path);
            return '<img src="' . e($url) . '" class="w-10 h-10 object-contain" alt="' . e($this->name) . '">';
        }
        return '<span class="text-4xl">' . e($this->icon) . '</span>';
    }

    public function getAudioUrlAttribute(): string
    {
        return $this->source_type === 'local'
            ? asset('storage/' . $this->file_path)
            : $this->external_url;
    }
}
