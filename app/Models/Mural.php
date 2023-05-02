<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mural extends Model
{
    use HasFactory;

    protected $fillable = ['date','title','description','course_id','discipline_id'];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }
}
