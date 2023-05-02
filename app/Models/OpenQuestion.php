<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_id',
        'discipline_id'
    ];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }

    public function open_response ()
    {
        return $this->hasMany(OpenResponse::class);
    }
}
