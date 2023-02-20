<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model {

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'id','updated_at','created_at'
    ];
}
