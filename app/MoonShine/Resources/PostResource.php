<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FlexibleRender;
use MoonShine\Components\FormBuilder;
use MoonShine\Enums\JsEvent;
use MoonShine\Fields\Hidden;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Fields\Image;
use MoonShine\Fields\Date;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Support\AlpineJs;
use MoonShine\Components\Modal;

/**
 * @extends ModelResource<Post>
 */
class PostResource extends ModelResource
{
    protected string $model = Post::class;

    protected string $title = '';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('id')->sortable(),

                BelongsTo::make('Канал', 'channel', resource: new ChannelResource()),

                Text::make('Пост', 'name'),

                TinyMce::make('Контент', 'content')
                    ->plugins('code emoticons preview link')
                    ->toolbar('undo redo | bold italic underline strikethrough | emoticons charmap | removeformat preview link code')
                    ->addConfig('newline_behavior', 'linebreak')
                    ->addConfig('browser_spellcheck', 'true')
                    ->hideOnIndex()
                ,

                Image::make('Изображения', 'images')
                    ->disk('public')
                    ->dir('images')
                    ->removable()
                    ->multiple(),

                BelongsTo::make('Пользователь', 'moonshineUser', resource: new MoonShineUserResource()),

                Date::make('Когда публиковать', 'when_to_post')
                    ->withTime()
                    ->format('m.d H:i')
                    ->sortable(),

                Date::make('Опубликовано', 'posted_at')
                    ->format('m.d H:i')
                    ->hideOnForm()
                    ->sortable(),
            ]),
        ];
    }

    public function buttons(): array
    {
        return [
            ActionButton::make('Опубликовать', route('post'))
                ->canSee(fn (Model $item) => $item->channel->is_test_channel || $item->posted_at === null)
                ->primary()
                ->withConfirm(
                    'Публикация',
                    formBuilder: fn (FormBuilder $formBuilder, Model $item) => $formBuilder->fields([
                        FlexibleRender::make('Вы уверены, что хотите опубликовать пост?'),
                        Hidden::make('id')->setValue($item->id)
                    ])
                )
            ,
            ActionButton::make('AI review', fn($item) => route('ai-review', ['id' => $item->id]))
                ->async(
                    'POST',
                    '#ai-review',
                    [AlpineJs::event(JsEvent::MODAL_TOGGLED, 'ai-review-modal')]
                )
        ];
    }

    public function formPageComponents(): array
    {
        return [
            Modal::make(
                'Результат редактирования',
                static fn() => FlexibleRender::make('<div id="ai-review"></div>')
            )->name('ai-review-modal')
        ];
    }

    public function query(): Builder
    {
        if(request()->has('sort')) {
            return parent::query();
        }

        return parent::query()
            ->orderByRaw(
                "CASE
                    WHEN `posted_at` IS NULL THEN 0
                    ELSE 1
                END"
            )
            ->orderByDesc('posted_at');
    }

    public function filters(): array
    {
        return [
            BelongsTo::make('Канал', 'channel', resource: new ChannelResource()),
            Text::make('Пост', 'name'),
            Text::make('Контент', 'content'),
        ];
    }

    public function search(): array
    {
        return ['content'];
    }

    public function rules(Model $item): array
    {
        return [
            'name' => ['required'],
            'content' => ['required'],
            'channel_id' => ['required']
        ];
    }
}
