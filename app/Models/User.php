<?php

namespace App\Models;

use App\Database\Eloquent\Model;
use App\Database\Eloquent\Relations\HasMany;
use App\Traits\ModelLoaderTrait;

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
}
