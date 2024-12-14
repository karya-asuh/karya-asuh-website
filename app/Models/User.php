<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements AuthenticatableContract
{
    use HasFactory;

    protected $table = 'users';
    // Specify the custom primary key
    protected $primaryKey = 'user_id';  // Use 'user_id' instead of 'id'
    // Define which fields can be mass-assigned
    protected $guarded = [];
    protected $hidden = ['password'];
}
