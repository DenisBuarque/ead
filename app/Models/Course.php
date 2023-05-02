<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','duration','description','institution','status','image','polo_id'];

    public function user() {
        return $this->hasMany(User::class);
    }
    
    public function polo () {
        return $this->belongsTo(Polo::class);
    }

    public function disciplines () {
        return $this->hasMany(Discipline::class);
    }

    public function registrations () {
        return $this->hasMany(Registration::class);
    }

    public function preregistration () {
        return $this->hasMany(Preregistration::class);
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

    public function notes () {
        return $this->hasMany(Note::class);
    }
}
