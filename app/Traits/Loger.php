<?php

namespace App\Traits;

use App\Models\Log;

trait Loger
{
    use GetClassInfo;

    public function logs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Log::class, 'logable');
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $modelName = ucfirst($model->getClassShortName());
            $userId = auth()->id();

            \Log::channel('daily')->notice("User #" . $userId . " created new $modelName. Content is: " . json_encode($model));

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'created',
                'data' => json_encode($model),
                'message' => "User #" . $userId . " created new $modelName."
            ]);
        });

        static::updated(function ($model){
            $modelName = ucfirst($model->getClassShortName());
            $userId = auth()->id();

            $newValues = $model->getChanges();

            $data = [
                'old' => array_intersect_key($model->getOriginal(), $newValues),
                'new' => $newValues
            ];

            \Log::channel('daily')->info("User #" . $userId . " updated $modelName (ID #{$model->id}). Content is: " . json_encode($data));

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'updated',
                'data' => json_encode($data),
                'message' => "User #" . $userId . " updated $modelName."
            ]);
        });

        static::deleted(function ($model){
            $modelName = ucfirst($model->getClassShortName());
            $userId = auth()->id();

            \Log::channel('daily')->warning("User #" . $userId . " deleted $modelName (ID #{$model->id}). Content is: " . json_encode($model));

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'deleted',
                'data' => json_encode($model),
                'message' => "User #" . $userId . " deleted $modelName."
            ]);
        });

        static::forceDeleted(function ($model){
            $modelName = ucfirst($model->getClassShortName());
            $userId = auth()->id();

            \Log::channel('daily')->alert("User #" . $userId . " force-deleted $modelName (ID #{$model->id}). Content is: " . json_encode($model));

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'force-deleted',
                'data' => json_encode($model),
                'message' => "User #" . $userId . " force-deleted $modelName."
            ]);
        });

        static::restored(function ($model){
            $modelName = ucfirst($model->getClassShortName());
            $userId = auth()->id();

            \Log::channel('daily')->notice("User #" . $userId . " restored $modelName (ID #{$model->id}). Content is: " . json_encode($model));

            $model->logs()->create([
                'user_id' => $userId,
                'action' => 'restored',
                'data' => json_encode($model),
                'message' => "User #" . $userId . " restored $modelName."
            ]);
        });

    }
}