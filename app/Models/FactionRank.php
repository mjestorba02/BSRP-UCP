<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FactionRank extends Model
{
    protected $table = 'factionranks';

    // Relationship: A gang rank belongs to many users
    public function users()
    {
        return $this->hasMany(User::class, 'factionrank', 'id');
    }
}