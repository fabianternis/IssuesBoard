<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model 
{
    protected $table = 'projects';

    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = ['id', 'user_id', /*'slug',*/ 'name', 'description', 'repo_url'];

    public $timestamps = false; 
}