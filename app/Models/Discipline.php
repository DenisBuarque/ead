<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $fillable = ['institution','title','slug','year','semester','workload','period','credits','description','quiz','status','course_id','user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function inscriptions () {
        return $this->hasMany(Inscription::class);
    }

    public function murals () {
        return $this->hasMany(Mural::class);
    }

    public function forums () {
        return $this->hasMany(Forum::class);
    }

    public function multiple_question () {
        return $this->hasMany(MultipleQuestion::class);
    }

    public function discipline_modules () 
    {
        return $this->hasMany(DisciplineModule::class);
    }

    public function open_question () {
        return $this->hasMany(OpenQuestion::class);
    }

    public function direct_chat () {
        return $this->hasMany(DirectChat::class);
    }

    public function jobs () {
        return $this->hasMany(Job::class);
    }

    public function multiple_response ()
    {
        return $this->hasMany(MultipleResponse::class);
    }

    public function open_response ()
    {
        return $this->hasMany(OpenResponse::class);
    }

    public function note () {
        return $this->belongsTo(Note::class);
    }
}
