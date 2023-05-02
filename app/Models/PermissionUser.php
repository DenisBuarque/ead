<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionUser extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission_user';
    
    protected $fillable = ['permission_id','user_id'];

    public function permissions () {
        return $this->belongsTo(Permission::class);
    }

    public function users () {
        return $this->belongsTo(User::class);
    }
}
