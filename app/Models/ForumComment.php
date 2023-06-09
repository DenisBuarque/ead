<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use HasFactory;

    protected $fillable = ['comment','user_id','forum_id'];

    public function forum () {
        return $this->belongsTo(Forum::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
