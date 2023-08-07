<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    protected $table = 'posts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // キーワード検索
    public static function searchByKeyword($keyword)
    {
        return self::whereHas('movie', function ($query) use ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
        })->orWhere('title', 'like', '%' . $keyword . '%')->with(['user', 'movie'])->get();
    }
}
