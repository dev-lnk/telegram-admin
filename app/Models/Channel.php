<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'telegram_url',
        'chat_id',
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
}
