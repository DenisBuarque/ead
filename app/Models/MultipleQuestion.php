<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'response_one',
        'response_two',
        'response_tree',
        'response_four',
        'gabarito',
        'punctuation',
        'course_id',
        'discipline_id'
    ];

    public function course () {
        return $this->belongsTo(Course::class);
    }

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }

    public function multiple_response ()
    {
        return $this->hasMany(MultipleResponse::class);
    }

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'multiple_question_id' => 'array',
        'response' => 'array'
    ];
}
