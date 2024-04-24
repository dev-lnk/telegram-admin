<?php

namespace App\Models;

use App\Jobs\PostJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'channel_id',
        'name',
        'content',
        'images',
        'moonshine_user_id',
        'created_at',
        'when_to_post',
        'posted_at',
    ];

    public $casts = [
        'images' => 'json',
        'created_at' => 'datetime',
        'when_to_post' => 'datetime',
        'posted_at'=> 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(static function (self $model) {
            if($model->when_to_post !== null) {
                PostJob::dispatch($model->id, $model->when_to_post->format('Y-m-d H:i:s'))
                    ->delay(now()->diffInSeconds($model->when_to_post));
            }
        });

        static::updated(static function (self $model) {
            if($model->wasChanged('when_to_post') && $model->when_to_post !== null) {
                PostJob::dispatch($model->id, $model->when_to_post->format('Y-m-d H:i:s'))
                    ->delay(now()->diffInSeconds($model->when_to_post));
            }
        });
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function moonshineUser(): BelongsTo
    {
        return $this->belongsTo(\MoonShine\Models\MoonshineUser::class);
    }


}
