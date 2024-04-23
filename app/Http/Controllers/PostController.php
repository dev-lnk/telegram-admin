<?php

namespace App\Http\Controllers;

use App\Actions\PostAction;
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
    public function post(TelegramBot $bot)
    {
        $postId = request()->validate([
            'id' => ['int', 'required']
        ])['id'];

        $post = Post::query()->where('id', $postId)->firstOrFail();

        try {
            PostAction::make($post, $bot)->handle();
        } catch (PostException $e) {
            $this->toast($e->getMessage(), 'error');
            return back();
        }

        $this->toast('Пост опубликован');

        return back();
    }
}
