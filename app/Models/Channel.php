<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Channel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'telegram_url',
        'chat_id',
        'is_test_channel',
        'created_at',
    ];

    public function getAliasForBot(): string
    {
        $alias = str($this->telegram_url)
            ->replace('https://t.me/', '')
            ->replace('https://', '')
            ->replace('t.me/', '')
            ->value()
        ;

        if(! str_starts_with($alias, '@')) {
            $alias = '@'.$alias;
        }

        return $alias;
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }
}
