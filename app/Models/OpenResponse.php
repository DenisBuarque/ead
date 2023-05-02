<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenResponse extends Model
{
    use HasFactory;

    protected $fillable = ['resposta','open_question_id','course_id','discipline_id','user_id','note'];

    public function open_question ()
    {
        return $this->belongsTo(OpenQuestion::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
    
    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }
}
