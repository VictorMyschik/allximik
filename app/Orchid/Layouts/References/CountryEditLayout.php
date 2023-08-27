<?php

namespace App\Orchid\Layouts\References;

use App\Models\Reference\Country;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CountryEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      // continent
      Select::make('country.continent')
        ->options(Country::getContinentList())
        ->required()
        ->empty('Не выбрано', 0)
        ->title('Continent'),

      Input::make('country.name')
        ->type('text')
        ->max(255)
        ->required()
        ->title('Name'),

      Input::make('country.iso3166alpha2')
        ->type('text')
        ->max(2)
        ->required()
        ->title('ISO 3166 alpha2'),

      Input::make('country.iso3166alpha3')
        ->type('text')
        ->max(3)
        ->required()
        ->title('ISO 3166 alpha3'),

      Input::make('country.iso3166numeric')
        ->type('text')
        ->max(3)
        ->required()
        ->title('ISO 3166 numeric'),
    ];
  }
}
