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
        'introduction',
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
    ];
    
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user');
    }
    
    public function viewApprovers()
    {
        return $this->belongsToMany(ViewGroup::class, 'view_approvers');
    }
    
    public function favoriteMovies()
    {
        return $this->belongsToMany(Movie::class, 'user_movie');
    }
    
    public function favoriteGenres()
    {
        return $this->belongsToMany(Genre::class, 'user_genre');
    }
    
    public function favoritePlatforms()
    {
        return $this->belongsToMany(Platform::class, 'user_platform');
    }
    
    public function favoriteEras()
    {
        return $this->belongsToMany(Era::class, 'user_era');
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function creators()
    {
        return $this->hasMany(Group::class);
    }
    
    public function requesters()
    {
        return $this->hasMany(ViewGroup::class);
    }
    
    
}
