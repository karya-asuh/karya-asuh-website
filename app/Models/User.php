<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    // Specify the table name
    protected $table = 'users';

    // Define the primary key and its type
    protected $primaryKey = 'user_id';  // Custom primary key
    protected $keyType = 'string';     // Since `user_id` is a UUID (string)

    // Disable auto-increment for the primary key
    public $incrementing = false;

    // Allow mass assignment for all fields
    protected $guarded = [];

    // Hide sensitive fields from serialization
    protected $hidden = ['password', 'remember_token'];
}
