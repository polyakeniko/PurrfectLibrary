<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceDetection extends Model
{
    protected $fillable = [
        'device',
        'platform',
        'browser',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'ip_info' => 'array',
    ];
}
