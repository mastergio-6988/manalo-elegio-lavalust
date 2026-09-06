<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model
{
    protected $table = 'users';

    protected $primary_key = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'username'
    ];

    protected $guarded = ['id'];

    protected $timestamps = false;
}
