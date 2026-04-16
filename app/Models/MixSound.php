<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MixSound extends Pivot
{
    public $timestamps = false;

    protected $fillable = ['mix_id', 'sound_id', 'volume'];

    protected function casts(): array
    {
        return ['volume' => 'integer'];
    }
}
