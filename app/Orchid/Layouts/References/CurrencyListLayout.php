<?php

namespace App\Orchid\Layouts\References;

use App\Models\Reference\Currency;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class CurrencyListLayout extends Table
{
  public $target = 'list';

  public function columns(): array
  {
    return [
      TD::make('id', __('ID'))->sort(),
      TD::make('name', __('Name'))->sort(),
      TD::make('code', __('Code'))->sort(),
      TD::make('text_code', __('Text code'))->sort(),
      TD::make('rounding', __('Rounding'))->sort(),
      TD::make('description', __('Description'))->sort(),

      TD::make(__('Actions'))
        ->align(TD::ALIGN_CENTER)
        ->width('100px')
        ->render(fn(Currency $currency) => DropDown::make()
          ->icon('bs.three-dots-vertical')
          ->list([
            ModalToggle::make('Edit')
              ->type(Color::PRIMARY())
              ->icon('pencil')
              ->modal('currency_modal')
              ->modalTitle('Edit currency id ' . $currency->id)
              ->method('saveCurrency')
              ->asyncParameters(['id' => $currency->id]),

            Button::make(__('Delete'))
              ->icon('bs.trash3')
              ->confirm(__('Are you sure you want to delete the currency?'))
              ->method('remove', ['id' => $currency->id]),
          ])),
    ];
  }
}
