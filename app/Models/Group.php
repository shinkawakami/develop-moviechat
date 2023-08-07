<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    protected $table = 'groups';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'owner_id',
        'capacity',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user')->withTimestamps();
    }
    
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'group_movie');
    }
    
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'group_genre');
    }
    
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'group_platform');
    }
    
    public function eras()
    {
        return $this->belongsToMany(Era::class, 'group_era');
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }
    
    // オーナーであるかチェック
    public function isOwner($user)
    {
        return $this->owner_id == $user->id;
    }
    
    // メンバーであるかチェック
    public function isMember($userId)
    {
        return $this->users->contains($userId);
    }
    
    // 満員であるかチェック
    public function isFull()
    {
        return $this->users->count() == $this->capacity;
    }
    
    // キーワード検索
    public function scopeWithKeyword($query, $keyword)
    {
        return $query->whereHas('movies', function ($q) use ($keyword) {
            $q->where('title', 'like', '%' . $keyword . '%');
        })->orWhere('name', 'like', '%' . $keyword . '%');
    }
    
    // ジャンル検索
    public function scopeWithGenres($query, $genreIds)
    {
        return $query->whereHas('genres', function ($q) use ($genreIds) {
            $q->whereIn('genre_id', $genreIds);
        }, '=', count($genreIds));
    }
    
    // 年代検索
    public function scopeWithEras($query, $eraIds)
    {
        return $query->whereHas('eras', function ($q) use ($eraIds) {
            $q->whereIn('era_id', $eraIds);
        }, '=', count($eraIds));
    }
    
    // プラットフォーム検索
    public function scopeWithPlatforms($query, $platformIds)
    {
        return $query->whereHas('platforms', function ($q) use ($platformIds) {
            $q->whereIn('platform_id', $platformIds);
        }, '=', count($platformIds));
    }
    
    // 次のオーナーの指定，またはグループ削除
    public function newOwnerOrDelete($currentOwnerId)
    {
        $nextOwner = $this->users()->where('users.id', '!=', $currentOwnerId)->orderBy('pivot_created_at')->first();
        
        if ($nextOwner) {
            $this->owner_id = $nextOwner->id;
            $this->save();
        } else {
            $this->delete();
        }
    }
    
    
}
