<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Utilisateurs administrateurs ayant ce rôle.
     */
    public function adminUsers()
    {
        return $this->belongsToMany(User::class, 'user_admin_role');
    }

    /**
     * Permissions associées au rôle.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}