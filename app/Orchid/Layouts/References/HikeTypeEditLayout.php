<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class HikeTypeEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      Input::make('hike-type.name')
        ->title('Name')
        ->required()
        ->maxlength(255),

      TextArea::make('hike-type.description')
        ->title('Description')
        ->rows(5)
        ->maxlength(8000),
    ];
  }
}
