<?php
namespace Mackilanu\Models;
use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class User
 * This is the base model for the 'users' table.
 * If you've modified the table, please change the fields in this
 * class according to your configuration.
 */
class User extends Model
{

    protected $fillable = [
        'email', 'password', 'firstName', 'lastName'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

}