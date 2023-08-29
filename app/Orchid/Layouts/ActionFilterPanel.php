<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class ActionFilterPanel
{
  public static function getActionsButtons(): Group
  {
    return Group::make([
      Button::make('Filter')->method('runFiltering')->type(Color::INFO()),
      Button::make('Clear')->method('clearFilter')->type(Color::SECONDARY()),
    ])->autoWidth();
  }
}
