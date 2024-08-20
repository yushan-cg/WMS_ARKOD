<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
protected $fillable = [
        'user_id',
        'message',
        'is_read',
        'uid',
    ];
}
