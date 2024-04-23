<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bot;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\HasMany;


/**
 * @extends ModelResource<Bot>
 */
class BotResource extends ModelResource
{
    protected string $model = Bot::class;

    protected string $title = 'Bot';

    protected string $column = 'name';

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('id')
                    ->sortable(),

                Text::make('Название бота', 'name')->required(),

                Text::make('Ключ бота', 'bot_key')
                    ->eye()
                    ->hideOnIndex()
                    ->required(),

                Text::make('Ссылка на бота', 'bot_url')->required(),

                HasMany::make('Каналы', 'channels', resource: new ChannelResource())->onlyLink(),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
