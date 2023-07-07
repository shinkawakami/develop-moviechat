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
        'era_id',
        'year'
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
    
    public function viewGroups()
    {
        return $this->hasMany(ViewGroup::class);
    }
    
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }
    
    public function platforms()
    {
        return $this->belongsToMany(Platform::class);
    }
    
    public function era()
    {
        return $this->belongsTo(Era::class);
    }
}
