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
}