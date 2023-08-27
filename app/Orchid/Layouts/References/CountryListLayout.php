<?php

namespace App\Orchid\Layouts\References;

use App\Models\Reference\Country;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class CountryListLayout extends Table
{
  public $target = 'list';

  public function columns(): array
  {
    return [
      TD::make('id', __('ID'))->sort(),
      TD::make('continent', __('Continent'))->render(fn(Country $country) => $country->getContinentName())->sort(),
      TD::make('name', __('Name'))->sort(),
      TD::make('iso3166alpha2', __('ISO 3166 alpha2'))->sort(),
      TD::make('iso3166alpha3', __('ISO 3166 alpha3'))->sort(),
      TD::make('iso3166numeric', __('ISO 3166 numeric'))->sort(),

      TD::make(__('Actions'))
        ->align(TD::ALIGN_CENTER)
        ->width('100px')
        ->render(fn(Country $country) => DropDown::make()
          ->icon('bs.three-dots-vertical')
          ->list([
            ModalToggle::make('Edit')
              ->type(Color::PRIMARY())
              ->icon('pencil')
              ->modal('country_modal')
              ->modalTitle('Edit country id ' . $country->id)
              ->method('saveCountry')
              ->asyncParameters(['id' => $country->id]),

            Button::make(__('Delete'))
              ->icon('bs.trash3')
              ->confirm(__('Are you sure you want to delete the country?'))
              ->method('remove', ['id' => $country->id]),
          ])),
    ];
  }
}
