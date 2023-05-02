<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectChat extends Model
{
    use HasFactory;

    protected $fillable = ['subject','active','msg_admin','msg_student','user_id','course_id','discipline_id'];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }

    public function direct_chat_messages () {
        return $this->hasMany(DirectChatMessage::class);
    }
}
