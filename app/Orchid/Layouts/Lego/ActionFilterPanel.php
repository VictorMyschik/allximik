<?php

namespace App\Orchid\Layouts\Lego;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;

class ActionFilterPanel
{
    public static function getActionsButtons(array $properties = []): Group
    {
        return Group::make([
            Button::make('Filter')->name('поиск')->method('runFiltering', $properties)->class('mr-btn-success'),
            Button::make('Clear')->name('очистить')->method('clearFilter')->class('mr-btn-danger'),
        ])->autoWidth();
    }
}
