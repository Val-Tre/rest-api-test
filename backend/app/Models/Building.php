<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model {

    protected $fillable = [
        'name', 'client_id'
    ];

    protected $hidden = [
        'id', 'client_id','updated_at','created_at'
    ];
}
