<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function sounds()
    {
        return $this->belongsToMany(Sound::class, 'sound_tag');
    }
}
