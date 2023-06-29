<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    protected $table = 'movies';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'title',
        'genre_id',
        'subscription_id',
        'released_at',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
    
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
