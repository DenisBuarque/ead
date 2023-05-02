<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preregistration extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','email','status','course_id'];

    public function course () 
    {
        return $this->belongsTo(Course::class);
    }
}
