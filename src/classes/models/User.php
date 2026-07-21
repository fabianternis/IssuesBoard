<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Model 
{
    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'username', 'email', 'password'];
    public $timestamps = false; 

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function ownedProjects() 
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    public function projects() 
    {
        return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id');
    }

    public function hasProjects(): bool
    {
        return $this->projects()->exists() || $this->ownedProjects()->exists();
    }
}