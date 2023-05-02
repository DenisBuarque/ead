<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumOpnion extends Model
{
    use HasFactory;

    protected $fillable = ['forum_comment_id','user_id','opnion'];

    public function user () 
    {
        return $this->belongsTo(User::class);
    }
}
