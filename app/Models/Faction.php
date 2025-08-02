<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    protected $table = 'factions';

    // Relationship: A gang has many users
    public function users()
    {
        return $this->hasMany(User::class, 'faction', 'id');
    }
}