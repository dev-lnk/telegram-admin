<?php

namespace App\Http\Controllers;

use App\Actions\PostAction;
use App\Api\Coze\CozeApi;
use App\Api\Coze\CozeApiException;
use App\Exceptions\PostException;
use App\Models\Post;
use App\Services\TelegramBot;
use Longman\TelegramBot\Exception\TelegramException;
use MoonShine\Http\Controllers\MoonShineController;

class PostController extends MoonShineController
{
    /**
     * @throws TelegramException
     */
    public function post()
    {
        $postId = request()->validate([
            'id' => ['int', 'required']
        ])['id'];

        $post = Post::query()->where('id', $postId)->firstOrFail();

        $bot = new TelegramBot($post->channel->bot->bot_key, $post->channel->bot->getAliasForBot());

        try {
            PostAction::make($post, $bot)->handle();
        } catch (PostException $e) {
            $this->toast($e->getMessage(), 'error');
            return back();
        }

        $this->toast('Пост опубликован');

        return back();
    }

    public function aiReview(int $postId, CozeApi $api)
    {
        $post = Post::query()->where('id', $postId)->firstOrFail();

        $response = $api->post('chat', [
            'query' => 'Выполни редактирование текста для телеграм канала, расставь правильно запятые и исправь речевые обороты: ' . $post->content,
            'stream' => false
        ]);

        try {
            $result = $response->content();

            if(empty($result['messages'])) {
                throw new CozeApiException('Content: '. json_encode($result));
            }

            $review = $result['messages'][0]['content'];
        } catch (\Throwable $e) {
            report($e);

            $review = 'Ошибка получения review';
        }

        return response()->json([
            'html' => $review
        ]);
    }
}
