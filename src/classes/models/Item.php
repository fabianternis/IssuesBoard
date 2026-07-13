<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model 
{
    protected $table = 'items';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true; 

    protected $fillable = [
        'id', 
        'project_id', 
        'name', 
        'description', 
        'type',
        'state',
        'external_url'
        // 'image_url'
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

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}