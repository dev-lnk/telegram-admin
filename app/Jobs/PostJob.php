<?php

namespace App\Jobs;

use App\Actions\PostAction;
use App\Exceptions\PostException;
use App\Models\Post;
use App\Services\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Longman\TelegramBot\Exception\TelegramException;

class PostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        private readonly int $postId,
        private readonly string $whenToPost
    ) {
    }

    /**
     * @throws TelegramException
     * @throws PostException
     */
    public function handle(): void
    {
        $post = Post::query()->where('id', $this->postId)->first();

        if($post->when_to_post->format('Y-m-d H:i:s') !== $this->whenToPost
            || ! is_null($post->posted_at)
        ) {
            return;
        }

        $bot = new TelegramBot($post->channel->bot->bot_key, $post->channel->bot->getAliasForBot());

        PostAction::make($post, $bot)->handle();
    }
}
