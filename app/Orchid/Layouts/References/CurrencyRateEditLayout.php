<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CurrencyRateEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      // continent

      Select::make('currency-rate.currency_id')
        ->title('Currency')
        ->fromModel('App\Models\Reference\Currency', 'name')
        ->required()
        ->placeholder('Currency')
        ->help('Валюта'),

      Input::make('currency-rate.scale')
        ->title('Scale')
        ->type('number')
        ->required()
        ->placeholder('Scale')
        ->help('Количество единиц валюты'),

      Input::make('currency-rate.rate')
        ->title('Rate')
        ->type('number')
        ->step('0.0001')
        ->required()
        ->placeholder('Rate')
        ->help('Курс валюты'),
    ];
  }
}
