<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nivel',
        'institution',
        'image',
        'year',
        'registration',
        'name',
        'phone',
        'email',
        'password',
        'rg',
        'cpf',
        'filiation',
        'sexo',
        'conclusion',
        'church',
        'naturalness',
        'country',
        'marital_status',
        'access',
        'birth_date',
        'birthplace',
        'date_entry',
        'date_exit',
        'zip_code',
        'address',
        'number',
        'district',
        'city',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function customerservice () 
    {
        return $this->hasMany(CustomerService::class);
    }

    public function customer_service_comments () 
    {
        return $this->hasMany(CustomerServiceComment::class);
    }

    public function forum () {
        return $this->belongsTo(Forum::class);
    }

    public function forum_opinions () {
        return $this->hasMany(ForumOpinion::class);
    }

    public function forum_comments () {
        return $this->hasMany(ForumComment::class);
    }

    public function customer_service () {
        return $this->hasMany(CustomerService::class);
    }

    public function discipline ()
    {
        return $this->hasMany(Discipline::class);
    }

    public function registrations ()
    {
        return $this->hasMany(Registration::class);
    }

    public function inscription ()
    {
        return $this->hasMany(Inscription::class);
    }

    public function course ()
    {
        return $this->belongsTo(Course::class);
    }

    public function customer_serive_comments () 
    {
        return $this->hasMany(CustomerServiceComment::class);
    }

    public function permissions ()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function permission_user () {
        return $this->hasMany(PermissionUser::class);
    }

    public function hasPermissions ($collection)
    {
        $permissions_user = $this->permission_user;
        return $collection->intersect($permissions_user)->count();
    }

    public function direct_chat () {
        return $this->hasMany(DirectChat::class);
    }

    public function direct_chat_messages () {
        return $this->hasMany(DirectChatMessage::class);
    }

    public function job () {
        return $this->hasOne(Job::class);
    }

    public function multiple_response (){
        return $this->hasMany(MultipleResponse::class);
    }

    public function open_response (){
        return $this->hasMany(OpenResponse::class);
    }

    public function notes () {
        return $this->hasMany(Note::class);
    }


    /*
    public function access () 
    {
        return $this->hasMany(Access::class);
    }
    */
}
