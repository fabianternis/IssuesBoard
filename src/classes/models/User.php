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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    function projects() {
        // return Project::where('user_id', $this->id);
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    function hasProjects(): bool
    {
        // return (count($this->projects()) > 0);
        return $this->projects()->exists();
    }
}