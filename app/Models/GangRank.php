<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GangRank extends Model
{
    protected $table = 'gangranks';

    // Relationship: A gang rank belongs to many users
    public function users()
    {
        return $this->hasMany(User::class, 'gangrank', 'id');
    }
}
