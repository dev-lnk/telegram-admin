<?php

namespace App\Models;

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

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function moonshineUser(): BelongsTo
    {
        return $this->belongsTo(\MoonShine\Models\MoonshineUser::class);
    }


}
