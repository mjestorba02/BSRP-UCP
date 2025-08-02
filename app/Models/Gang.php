<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gang extends Model
{
    protected $table = 'gangs';

    // Relationship: A gang has many users
    public function users()
    {
        return $this->hasMany(User::class, 'gang', 'id');
    }
}

