<?php

namespace Traits;

use Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->recordActivity('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = $model->getChanges();
            if (empty($changes)) {
                return;
            }

            $original = array_intersect_key($model->getOriginal(), $changes);
            $model->recordActivity('updated', $original, $changes);
        });

        static::deleted(function ($model) {
            $model->recordActivity('deleted', $model->getAttributes(), null);
        });
    }

    protected function recordActivity(string $action, ?array $pre, ?array $post): void
    {
        ActivityLog::create([
            'user_id' => $this->getCurrentUserId(),
            'object_type' => $this->getMorphClass(),
            'object_id' => $this->getKey(),
            'action' => $action,
            'data_pre' => $pre,
            'data_post' => $post,
            'performed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    protected function getCurrentUserId(): ?string
    {
        return $_SESSION['user_id'] ?? null; 
        // whyever i ade a function for that ...
    }

    public function generateDiff()
    {
        return null;
    }
}