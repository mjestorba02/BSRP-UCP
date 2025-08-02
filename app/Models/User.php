<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Gang;
use App\Models\GangRank;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    public $timestamps = false;

    protected $primaryKey = 'uid';

    protected $fillable = [
        'username',
        'password',
        'adminlevel',
    ];

    public function gang()
    {
        return $this->belongsTo(Gang::class, 'gang', 'id');
    }

    public function gangrank()
    {
        return $this->belongsTo(GangRank::class, 'gangrank', 'id');
    }

    public function card()
    {
        return $this->hasOne(Card::class, 'owner', 'uid'); // link by uid
    }

    /**
     * Laravel uses "email" by default as the username field.
     * If you're using "username", override the method:
     */

    public function getDonatorRankAttribute()
    {
        return match ($this->vippackage) {
            1 => 'Bronze',
            2 => 'Silver',
            3 => 'Gold',
            4 => 'Platinum',
            default => 'None',
        };
    }

    public function getAdminRankAttribute()
    {
        return match($this->adminlevel) {
            1 => 'Trial Admin',
            2 => 'Junior Admin',
            3 => 'General Admin',
            4 => 'Senior Admin',
            5 => 'Head Admin',
            6 => 'Executive Admin',
            7 => 'Management',
            default => 'None',
        };
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * The attributes that are mass assignable.
     */

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_status' => 'boolean',
    ];
}
