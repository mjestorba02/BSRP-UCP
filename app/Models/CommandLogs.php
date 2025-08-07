<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandLogs extends Model
{
    protected $table = 'log_cmd';

    protected $fillable = ['description'];
}
