<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\PostException;
use App\Models\Post;
use App\Services\TelegramBot;
use Illuminate\Support\Facades\Storage;
use Longman\TelegramBot\Exception\TelegramException;
use MoonShine\Traits\Makeable;

final class PostAction
{
    use Makeable;
    
    public function __construct(
        private Post $post,
        private TelegramBot $bot
    ) {
    }

    /**
     * @throws TelegramException
     * @throws PostException
     */
    public function handle(): void
    {
        $images = [];
        if(! empty($this->post->images)) {
            foreach ($this->post->images as $image) {
                $images[] = Storage::disk('public')->path($image);
            }
        }

        $content = str($this->post->content)
            ->replace('<p>', '')
            ->replace('</p>', '')
            ->replace('<br>', PHP_EOL)
            ->replace('&nbsp;', ' ')
            ->value();

        $channelId = $this->post->channel->chat_id ?? $this->post->channel->getAliasForBot();

        $result = $this->bot->sendToChannel($channelId, $content, $images);

        if($result->raw_data['ok'] !== true) {
            throw new PostException("Ошибка публикации поста. {$result->raw_data['description']}. Code: {$result->raw_data['error_code']}");
        }

        $this->post->posted_at = now();

        $this->post->save();
    }
}