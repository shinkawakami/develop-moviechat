<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viewing extends Model
{
    use HasFactory;
    
    protected $table = 'viewings';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'group_id',
        'requester_id',
        'movie_id',
        'url',
        'start_time',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    public function requester()
    {
        return $this->belongsTo(User::class);
    }
    
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
    
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function approvers()
    {
        return $this->belongsToMany(User::class, 'approvers');
    }
}
