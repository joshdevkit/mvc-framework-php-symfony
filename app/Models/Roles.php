<?php

namespace App\Models;

use App\Database\Eloquent\Model;
use App\Database\Eloquent\Relations\BelongsToMany;

class Roles extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'role_name'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
    }
}
