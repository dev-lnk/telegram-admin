<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use App\Models\Post;
use MoonShine\Pages\Page;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Metrics\ValueMetric;

class Dashboard extends Page
{
    /**
     * @return array<string, string>
     */
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Статистика';
    }

    /**
     * @return list<MoonShineComponent>
     */
    public function components(): array
	{
		return array(
            ValueMetric::make('Всего постов')
                ->value(Post::query()->count())
        );
	}
}
