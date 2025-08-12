<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntivpnEntry extends Model
{
    protected $fillable = ['start_ip', 'end_ip', 'type', 'source'];
}