<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relations extends Model {

    protected $fillable = [
        'building_id', 'parking_id'
    ];

    protected $hidden = [
        'updated_at','created_at'
    ];
}
