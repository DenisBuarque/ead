<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleResponse extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','course_id','discipline_id','multiple_question_id','gabarito','option'];

    public function multiple_question ()
    {
        return $this->belongsTo(MultipleQuestion::class);
    }
}
