<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    
    public function getByLimit(int $limit_count = 10)
    {
        return $this->orderby('updated_at', 'DESC')->limit($limit_count)->get();
    }
}
