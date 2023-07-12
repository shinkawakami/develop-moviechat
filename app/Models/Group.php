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
        'creator_id',
        'capacity',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user');
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
    
    public function creator()
    {
        return $this->belongsTo(User::class);
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function viewGroups()
    {
        return $this->hasMany(ViewGroup::class);
    }
}
