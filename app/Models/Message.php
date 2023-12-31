<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $table = 'messages';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'group_id',
        'viewing_id',
        'user_id',
        'content',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    public function viewing()
    {
        return $this->belongsTo(Viewing::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
