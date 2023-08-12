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
    
    // 申請の受信者
    public function recipients()
    {
        return $this->belongsToMany(User::class, 'viewing_user');
    }
    
    // 承諾者
    public function approvers()
    {
        return $this->belongsToMany(User::class, 'viewing_user')
            ->wherePivot('approved', true);
    }
    
    // ユーザーが申請者であるかチェック
    public function isRequester($user)
    {
        return $this->requester_id == $user->id;
    }
    
    // ユーザーが申請の受信者であるかチェック
    public function isRecipient($user)
    {
        return $this->recipients->contains($user->id);
    }
    
    // ユーザーが承諾者であるかチェック
    public function isApprover($user)
    {
        return $this->approvers->contains($user->id);
    }
}