<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'governor_id',
        'vice_governor_id',
        'device_cookie'
    ];
}
