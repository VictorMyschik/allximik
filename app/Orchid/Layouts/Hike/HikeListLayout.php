<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Hike;

use App\Models\Hike;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class HikeListLayout extends Table
{
  public $target = 'list';

  public function columns(): array
  {
    return [
      TD::make('id', __('ID'))->sort(),

      TD::make('name', 'Name'),

      TD::make('description', 'Description'),

      TD::make('status', 'Status')->render(fn(Hike $hike) => $hike->getStatusName()),

      TD::make('user_id', 'User')->render(fn(Hike $hike) => $hike->getUser()->name),

      TD::make('country', 'Country')->render(fn(Hike $hike) => $hike->getCountry()->getName()),

      TD::make('hike_type_id', 'Hike type')->render(fn(Hike $hike) => $hike->getHikeType()->getName()),


      TD::make(__('Actions'))
        ->align(TD::ALIGN_CENTER)
        ->width('100px')
        ->render(fn(Hike $hike) => DropDown::make()
          ->icon('bs.three-dots-vertical')
          ->list([
            ModalToggle::make('Edit')
              ->type(Color::PRIMARY())
              ->icon('pencil')
              ->modal('hike_modal')
              ->modalTitle('Edit hike id ' . $hike->id)
              ->method('saveHike')
              ->asyncParameters(['id' => $hike->id]),

            Button::make(__('Delete'))
              ->icon('bs.trash3')
              ->confirm(__('Are you sure you want to delete the hike?'))
              ->method('remove', ['id' => $hike->id]),
          ])),
    ];
  }
}
