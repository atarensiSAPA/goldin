<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'external_id',
        'external_auth',
        'role',
        'coins',
        'level',
        'experience',
        'connected',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getMaxExperienceAttribute()
    {
        // Calculate max_experience based on the user's level
        return pow(2, $this->level) * 100;
    }

    public function addExperience($experienceGained)
    {
        $this->experience += $experienceGained;
    
        // Check if the user has enough experience to level up
        while ($this->experience >= $this->max_experience) {
            $this->experience -= $this->max_experience;
            $this->level++;
        }
    
        $this->save();
    }

    public function weapons()
    {
        return $this->belongsToMany(weapons::class, 'user_weapon', 'user_id', 'weapon_id');
    }
}
