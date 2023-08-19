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
        'image_url',
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
    
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'user_movie');
    }
    
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'user_genre');
    }
    
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'user_platform');
    }
    
    public function eras()
    {
        return $this->belongsToMany(Era::class, 'user_era');
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function owners()
    {
        return $this->hasMany(Group::class);
    }
    
    public function requesters()
    {
        return $this->hasMany(Viewing::class);
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    }
    
    // フォローしているかチェック
    public function isFollowing(User $user): bool
    {
        return $this->followings->contains($user->id);
    }

}
