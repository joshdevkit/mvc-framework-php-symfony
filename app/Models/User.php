<?php

namespace App\Models;

use App\Database\Eloquent\Model;
use App\Database\Eloquent\Relations\BelongsToMany;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function info()
    {
        return $this->hasMany(UserInfo::class, 'user_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'user_id', 'id', 'user_role');
    }
}
