<?php

namespace App\Orchid\Layouts\References;

use App\Models\HikeType;
use App\Models\Reference\CurrencyRate;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class HikeTypeListLayout extends Table
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
        ->render(fn(HikeType $hikeType) => DropDown::make()
          ->icon('bs.three-dots-vertical')
          ->list([
            ModalToggle::make('Edit')
              ->type(Color::PRIMARY())
              ->icon('pencil')
              ->modal('hike_type')
              ->modalTitle('Edit currency id ' . $hikeType->id)
              ->method('saveHikeType')
              ->asyncParameters(['id' => $hikeType->id]),

            Button::make(__('Delete'))
              ->icon('bs.trash3')
              ->confirm(__('Are you sure you want to delete the hike type?'))
              ->method('remove', ['id' => $hikeType->id]),
          ])),
    ];
  }
}
