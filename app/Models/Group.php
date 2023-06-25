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
        'created_id',
        'movie_id',
        'capacity',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }
    
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class, 'group_id');
    }
}
