<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'partner_id',
        'ip_address',
        'user_agent',
        'endpoint',
        'method',
        'request_data',
        'response_status',
    ];
}
