<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model 
{
    protected $table = 'projects';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'user_id', 'name', 'description', 'repo_url', 'deleted_at', 'settings'];
    public $timestamps = false;

    protected $casts = [
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'project_id')->orderBy('order_index');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id');
    }

    public function user()
    {
        return $this->owner();
    }

    public function isDeleted(): bool
    {
        return !is_null($this->deleted_at);
    }

    public function is_deleted(): bool
    {
        return $this->isDeleted();
    }

    public function soft_delete()
    {
        if(!$this->isDeleted()) {
            $this->deleted_at = $this->freshTimestamp();
            return $this->save();
        }
    }

    public function delete()
    {
        return $this->soft_delete();
    }
}