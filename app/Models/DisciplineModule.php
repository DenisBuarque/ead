<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineModule extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','description','movie','file','category','discipline_id'];

    public function discipline () {
        return $this->belongsTo(Discipline::class);
    }
}
