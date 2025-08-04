<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Updates extends Model
{
    protected $table = 'updates';

    protected $fillable = ['updates', 'image_url'];
}
