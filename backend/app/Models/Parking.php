<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $fillable = [
        'name', 'building_ids'
    ];

    protected $casts = ['building_ids' => 'array'];

    protected $hidden = [
        'id', 'building_ids', 'updated_at', 'created_at'
    ];
}
