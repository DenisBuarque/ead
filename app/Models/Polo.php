<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polo extends Model
{
    use HasFactory;

    protected $fillable = ['title','phone','email','zip_code','address','city','state'];

    /*public function user () {
        return $this->hasOne(User::class);
    }
    */
    
    public function cources () {
        return $this->hasOne(Cource::class);
    }
}
