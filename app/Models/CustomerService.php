<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','sector','subject','description','status'];

    public function user () 
    {
        return $this->belongsTo(User::class);
    }

    public function customer_service_comments () 
    {
        return $this->hasMany(CustomerServiceComment::class);
    }

}
