<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{Str, Carbon};

class User extends Model 
{
    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'username', 'email', 'password', 'github_id', 'github_email', 'github_token', 'github_refresh_token', 'hackclub_id', 'hackclub_email', 'hackclub_token', 'hackclub_refresh_token', 'deleted_at', 'settings'];
    public $timestamps = false; 

    // public $casts = [
    //     'settings' => 'json',
    // ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $casts = [
        'deleted_at' => 'datetime',
        'settings' => 'json',
    ];

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
    
    public function isDeleted(): bool
    {
        return !is_null($this->deleted_at) && Carbon::parse($this->deleted_at)->isPast();
    }

    public function delete_soft()
    {
        if(!$this->isDeleted()) {
            $this->deleted_at = $this->freshTimestamp()->addDays(28);
            return $this->save();
        }
    }

    public function delete()
    {
        return $this->delete_soft();
    }

    public function is_deleted(): bool
    {
        return $this->isDeleted();
    }

    public function get_deletion_status(): string
    {
        if (is_null($this->deleted_at)) {
            return 'open';
        } elseif(!is_null($this->deleted_at) && Carbon::parse($this->deleted_at)->isPast()) {
            // return 'fulfilled';
            return 'deleted';
        } else {
            return 'pending';
        }
    }

    public function recover()
    {
        if($this->is_deleted()) {
            $this->deleted_at = null;
            return $this->save();
        } else {
            return false;
        }
    }
}