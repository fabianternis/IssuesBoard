<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model 
{
    protected $table = 'items';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $keyName = 'id';

    public $timestamps = true; 

    protected $fillable = [
        'id', 
        'project_id', 
        'name', 
        'description', 
        'type',
        'state',
        'external_url',
        // 'image_url'
        'order_index',
        'deleted_at'
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


    public function isDeleted(): bool
    {
        return !is_null($this->deleted_at);
    }

    public function is_deleted(): bool
    {
        return $this->isDeleted();
    }
 
    public function delete_soft()
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