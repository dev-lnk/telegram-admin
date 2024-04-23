<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Channel;

use MoonShine\ActionButtons\ActionButton;
use MoonShine\Components\FlexibleRender;
use MoonShine\Components\FormBuilder;
use MoonShine\Fields\Hidden;
use MoonShine\Fields\Number;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Switcher;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;

/**
 * @extends ModelResource<Channel>
 */
class ChannelResource extends ModelResource
{
    protected string $model = Channel::class;

    protected string $title = 'Channel';

    protected string $column = 'name';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('id')->sortable(),

                Text::make('Название канала', 'name')->required(),

                BelongsTo::make('Бот', 'bot', resource: new BotResource())->required(),

                Text::make('Ссылка на канал', 'telegram_url')
                    ->hideOnIndex(),

                Number::make('ChatId канала', 'chat_id')
                    ->min(-pow(2, 63))
                    ->max(pow(2, 63) - 1)
                    ->hideOnIndex(),

                Switcher::make('Повторная публикация постов', 'is_repeat_post')
            ]),
        ];
    }

    public function formButtons(): array
    {
        return [
            ActionButton::make('Получить ChatId', route('get_chat_id'))
                ->canSee(fn (Model $item) => $item->chat_id === null)
                ->primary()
                ->withConfirm(
                    'Получение ChatId',
                    formBuilder: fn (FormBuilder $formBuilder, Model $item) => $formBuilder->fields([
                        FlexibleRender::make('Для получения ChatId, перешлите любое сообщение вашему боту из данного канала, и нажмите подтвердить'),
                        Hidden::make('id')->setValue($item->id)
                    ])
                )
        ];
    }

    public function search(): array
    {
        return ['name'];
    }

    public function rules(Model $item): array
    {
        return [
            'name' => ['required'],
            'bot_id' => ['required'],
            'telegram_url' => 'required_without:chat_id',
            'chat_id' => 'required_without:telegram_url',
        ];
    }
}
