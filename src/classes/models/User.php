<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    protected $table = 'users';

    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = ['id', 'username', 'email', 'password'];

    public $timestamps = false; 

    function projects() {
        return Project::where('user_id', $this->id);
    }
}