<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\ChannelException;
use App\Models\Channel;
use App\Support\Makeable;
use Illuminate\Support\Facades\Http;

final class GetChatIdAction
{
    use Makeable;

    public function __construct(
        private Channel $channel
    ) {
    }

    /**
     * @throws ChannelException
     */
    public function handle(): void
    {
        $bot = config('telegram.bot_key');

        $response = Http::get("https://api.telegram.org/bot$bot/getUpdates");

        $data = json_decode($response->body(), true);

        if($data['ok'] !== true) {
            throw new ChannelException("Ошибка публикации получения ChatId. {$data['description']}. Code: {$data['error_code']}");
        }

        $updates = $data['result'];

        $chatId = 0;

        for($i = count($updates) - 1; $i >=0 ; $i--) {
            if(! isset($updates[$i]['message'])) {
                continue;
            }

            if(isset($updates[$i]['message']['forward_from_chat'])) {
                $chatId = $updates[$i]['message']['forward_from_chat']['id'];
                break;
            }
        }

        if(! $chatId) {
            throw new ChannelException("Ошибка публикации получения ChatId");
        }

        $this->channel->chat_id = $chatId;

        $this->channel->save();
    }
}