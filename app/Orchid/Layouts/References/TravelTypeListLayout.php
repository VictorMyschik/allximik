<?php

namespace App\Orchid\Layouts\References;

use App\Models\TravelType;
use App\Models\Reference\CurrencyRate;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class TravelTypeListLayout extends Table
{
  public $target = 'list';

  public function columns(): array
  {
    return [
      TD::make('id', __('ID'))->sort(),

      TD::make('name', 'Name'),

      TD::make('description', 'Description'),

      TD::make(__('Actions'))
        ->align(TD::ALIGN_CENTER)
        ->width('100px')
        ->render(fn(TravelType $travelType) => DropDown::make()
          ->icon('bs.three-dots-vertical')
          ->list([
            ModalToggle::make('Edit')
              ->type(Color::PRIMARY())
              ->icon('pencil')
              ->modal('travel_type')
              ->modalTitle('Edit currency id ' . $travelType->id)
              ->method('saveTravelType')
              ->asyncParameters(['id' => $travelType->id]),

            Button::make(__('Delete'))
              ->icon('bs.trash3')
              ->confirm(__('Are you sure you want to delete the travel type?'))
              ->method('remove', ['id' => $travelType->id]),
          ])),
    ];
  }
}
