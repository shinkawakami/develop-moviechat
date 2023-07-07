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
        'user_id',
        'view_group_id',
        'content',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function viewGroup()
    {
        return $this->belongsTo(ViewGroup::class);
    }
}
