<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','course_id','discipline_id','note','file'];

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
