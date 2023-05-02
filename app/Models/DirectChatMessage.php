<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['message','check_admin','check_student','user_id','direct_chat_id'];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function direct_chat () {
        return $this->hasMany(DirectChat::class);
    }
}
