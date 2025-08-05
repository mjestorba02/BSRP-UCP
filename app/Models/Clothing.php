<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clothing extends Model
{
    use HasFactory;

    protected $table = 'clothing';

    protected $fillable = [
        'uid',
        'slot',
        'modelid',
    ];

    /**
     * Get the user that owns the clothing item.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}