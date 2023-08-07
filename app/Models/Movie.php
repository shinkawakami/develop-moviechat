<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    protected $table = 'movies';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'tmdb_id',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_movie');
    }
    
    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }
    
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'user_movie');
    }
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public static function updateOrCreateFromTMDB($movieData)
    {
        return self::updateOrCreate(
            ['tmdb_id' => $movieData['id']],
            ['title' => $movieData['title']]
        );
    }
}
