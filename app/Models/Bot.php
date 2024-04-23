<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bot extends Model
{
    protected $fillable = [
        'name',
        'bot_key',
        'bot_url',
        'channels',
    ];

    public function getAliasForBot(): string
    {
        return str($this->bot_url)
            ->replace('https://t.me/', '')
            ->replace('https://', '')
            ->replace('t.me/', '')
            ->value()
        ;
    }

    public function channels(): HasMany
    {
        return $this->hasMany(Channel::class);
    }
}
