<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';

    // Relationship: A gang has many users
    public function users()
    {
        return $this->hasMany(User::class, 'gang', 'id');
    }
}

