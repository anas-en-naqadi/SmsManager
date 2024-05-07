<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationGroupModel extends Model
{
    use HasFactory;

    protected $table= "sms_group_notifications";

    protected $fillable = [
        'from',
        'to',
        'body',
        'user_id',
        'service_id',
        'scheduled_at',
        'status',


    ];

    public function service()
    {
        return $this->belongsTo(ServiceCredentialsModel::class);
    }
}
