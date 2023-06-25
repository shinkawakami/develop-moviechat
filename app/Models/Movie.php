<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    protected $table = 'movies';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'genre_id',
        'subscription_id'
        'released_at'
    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'movie_id');
    }
    
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }
    
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
