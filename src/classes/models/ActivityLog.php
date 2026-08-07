<?php

namespace Models;

// use Illuminate\Database\Eloquent;
// use Eloquent\Concerns\HasUuids;
// use Eloquent\Model;
// use Eloquent\Relations\{BelongsTo, MorphTo};
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use RuntimeException;

class ActivityLog extends Model 
{
    use HasUuids;

    protected $table = 'activity_log';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'object_type',
        'object_id',
        'action',
        'data_pre',
        'data_post',
        'performed_at',
    ];

    protected $casts = [
        'data_pre' => 'array',
        'data_post' => 'array',
        'performed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->performed_at)) {
                $model->performed_at = date('Y-m-d H:i:s');
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function object(): MorphTo
    {
        return $this->morphTo('object');
    }

    public function delete(): bool
    {
        throw new RuntimeException('Activity log entries cannot be deleted.'); // is this soemwhere, you would say "period"?
    }
}