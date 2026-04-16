<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Mix extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'slug', 'description',
        'is_public', 'is_featured', 'likes_count',
    ];

    protected function casts(): array
    {
        return [
            'is_public'   => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Mix $mix) {
            $mix->slug = $mix->slug ?? Str::random(8);
        });
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sounds()
    {
        return $this->belongsToMany(Sound::class, 'mix_sounds')->withPivot('volume');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }
}
