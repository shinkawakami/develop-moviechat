<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Era extends Model
{
    use HasFactory;
    
    protected $table = 'eras';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $fillable = [
        'era',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_era');
    }
}