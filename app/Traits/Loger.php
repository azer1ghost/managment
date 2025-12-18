<?php

namespace App\Traits;

use App\Models\Log;
use App\Models\User;

trait Loger
{
    use GetClassInfo;

    public function logs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Log::class, 'logable');
    }

    /**
     * Get human-readable name for the model
     * 
     * @param mixed $model
     * @return string
     */
    protected static function getLoggableName($model): string
    {
        $modelName = class_basename(get_class($model));
        
        // Special handling for different models
        if ($modelName === 'Work') {
            return $model->code ?? "İş #{$model->id}";
        } elseif ($modelName === 'Client') {
            return $model->fullname ?? "Müştəri #{$model->id}";
        } elseif ($modelName === 'User') {
            $fullName = trim(($model->name ?? '') . ' ' . ($model->surname ?? ''));
            return $fullName ?: "İstifadəçi #{$model->id}";
        }
        
        // Default: use ID
        return ucfirst($modelName) . " #{$model->id}";
    }

    /**
     * Get user's full name for logging
     * 
     * @return string
     */
    protected static function getUserNameForLog(): string
    {
        $user = auth()->user();
        if ($user) {
            $fullName = trim(($user->name ?? '') . ' ' . ($user->surname ?? ''));
            return $fullName ?: "İstifadəçi #{$user->id}";
        }
        return 'Sistem';
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $modelName = ucfirst(class_basename(get_class($model)));
            $userId = auth()->id();
            $userName = static::getUserNameForLog();
            $itemName = static::getLoggableName($model);

            // Human-readable message
            $message = "{$userName} '{$itemName}' {$modelName}-ni yaratdı";
            
            \Log::channel('daily')->notice($message, [
                'user_id' => $userId,
                'user_name' => $userName,
                'model' => $modelName,
                'model_id' => $model->id,
                'item_name' => $itemName,
                'action' => 'created',
                'data' => $model->toArray()
            ]);

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'created',
                'data' => json_encode($model),
                'message' => $message
            ]);
        });

        static::updated(function ($model){
            $modelName = ucfirst(class_basename(get_class($model)));
            $userId = auth()->id();
            $userName = static::getUserNameForLog();
            $itemName = static::getLoggableName($model);

            $newValues = $model->getChanges();
            $changedFields = implode(', ', array_keys($newValues));

            // Human-readable message
            $message = "{$userName} '{$itemName}' {$modelName}-ni yenilədi" . 
                      (count($newValues) > 0 ? " (Dəyişikliklər: {$changedFields})" : "");

            $data = [
                'old' => array_intersect_key($model->getOriginal(), $newValues),
                'new' => $newValues
            ];

            \Log::channel('daily')->info($message, [
                'user_id' => $userId,
                'user_name' => $userName,
                'model' => $modelName,
                'model_id' => $model->id,
                'item_name' => $itemName,
                'action' => 'updated',
                'changed_fields' => array_keys($newValues),
                'data' => $data
            ]);

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'updated',
                'data' => json_encode($data),
                'message' => $message
            ]);
        });

        static::deleted(function ($model){
            $modelName = ucfirst(class_basename(get_class($model)));
            $userId = auth()->id();
            $userName = static::getUserNameForLog();
            $itemName = static::getLoggableName($model);

            // Human-readable message
            $message = "{$userName} '{$itemName}' {$modelName}-ni sildi";

            \Log::channel('daily')->warning($message, [
                'user_id' => $userId,
                'user_name' => $userName,
                'model' => $modelName,
                'model_id' => $model->id,
                'item_name' => $itemName,
                'action' => 'deleted',
                'data' => $model->toArray()
            ]);

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'deleted',
                'data' => json_encode($model),
                'message' => $message
            ]);
        });

        static::forceDeleted(function ($model){
            $modelName = ucfirst(class_basename(get_class($model)));
            $userId = auth()->id();
            $userName = static::getUserNameForLog();
            $itemName = static::getLoggableName($model);

            // Human-readable message
            $message = "{$userName} '{$itemName}' {$modelName}-ni tamamilə sildi (force delete)";

            \Log::channel('daily')->alert($message, [
                'user_id' => $userId,
                'user_name' => $userName,
                'model' => $modelName,
                'model_id' => $model->id,
                'item_name' => $itemName,
                'action' => 'force-deleted',
                'data' => $model->toArray()
            ]);

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'force-deleted',
                'data' => json_encode($model),
                'message' => $message
            ]);
        });

        static::restored(function ($model){
            $modelName = ucfirst(class_basename(get_class($model)));
            $userId = auth()->id();
            $userName = static::getUserNameForLog();
            $itemName = static::getLoggableName($model);

            // Human-readable message
            $message = "{$userName} '{$itemName}' {$modelName}-ni bərpa etdi";

            \Log::channel('daily')->notice($message, [
                'user_id' => $userId,
                'user_name' => $userName,
                'model' => $modelName,
                'model_id' => $model->id,
                'item_name' => $itemName,
                'action' => 'restored',
                'data' => $model->toArray()
            ]);

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'restored',
                'data' => json_encode($model),
                'message' => $message
            ]);
        });

    }
}