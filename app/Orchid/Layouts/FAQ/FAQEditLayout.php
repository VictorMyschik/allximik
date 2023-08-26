<?php

namespace App\Orchid\Layouts\FAQ;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class FAQEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      Input::make('faq.title')
        ->type('text')
        ->max(255)
        ->required()
        ->title('Title'),

      TextArea::make('faq.text')
        ->rows(5)
        ->required()
        ->title('Text'),
    ];
  }
}
