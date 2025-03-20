<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Links;

use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;

class ShowQueryLayout extends Rows
{
    public function fields(): array
    {
        return [
            ViewField::make('link')->view('admin.common.json'),
        ];
    }
}
