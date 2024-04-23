<?php

namespace App\Http\Controllers;

use App\Actions\GetChatIdAction;
use App\Exceptions\ChannelException;
use App\Models\Channel;
use MoonShine\Http\Controllers\MoonShineController;

class ChannelController extends MoonShineController
{
    public function getChatId()
    {
        $channelId = request()->validate([
            'id' => ['int', 'required']
        ])['id'];

        $channel = Channel::query()->where('id', $channelId)->firstOrFail();

        try {
            GetChatIdAction::make($channel)->handle();
        } catch (ChannelException $e) {
            $this->toast($e->getMessage(), 'error');
            return back();
        }

        $this->toast('ChatId успешно установлен');

        return back();
    }
}
