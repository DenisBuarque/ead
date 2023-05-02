<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','course_id','discipline_id'];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }

    public function users () {
        return $this->hasMany(User::class);
    }

    public function forum_comments () {
        return $this->hasMany(ForumComment::class);
    }
}
