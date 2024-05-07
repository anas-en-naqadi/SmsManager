<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftsModel extends Model
{
    use HasFactory;

    protected $table="drafts";

    protected $fillable = [
        'from',
        'to',
        'body',
        'user_id',
        'service_id',
        'scheduled_at',
        'category'
    ];

    public function service()
    {
        return $this->belongsTo(ServiceCredentialsModel::class);
    }
}
