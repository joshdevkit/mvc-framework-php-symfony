<?php

namespace App\Models;

use App\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_role';

    protected $fillable = [
        'role_id',
        'user_id'
    ];
}
