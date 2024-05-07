<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCredentialsModel extends Model
{
    use HasFactory;


    protected $table="user_service_credentials";


protected $fillable = [
'user_id' ,
'service_name',
'service_key',
'service_token',
'phone_number'
];
    public function toUser (){
        return $this->belongsTo(User::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notifications::class);
    }
}
