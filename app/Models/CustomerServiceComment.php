<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerServiceComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','customer_service_id','comment','view_user','view_student'];

    public function user () 
    {
        return $this->belongsTo(User::class);
    }

    public function customer_service () 
    {
        return $this->belongsTo(CustomerService::class);
    }
}
