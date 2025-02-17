<?php

namespace App\Orchid\Layouts\References;

use App\Models\Reference\Currency;
use App\Models\Reference\CurrencyRate;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class CurrencyRateListLayout extends Table
{
    public $target = 'list';

    public function columns(): array
    {
        return [
            TD::make('id', __('ID'))->sort(),
            TD::make('currency_id', __('Currency'))->render(fn(CurrencyRate $currencyRate) => $currencyRate->getCurrency()->getName()),
            TD::make('scale', 'Scale'),
            TD::make('rate', __('Rate')),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(CurrencyRate $currencyRate) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->type(Color::PRIMARY())
                            ->icon('pencil')
                            ->modal('currency_rate_modal')
                            ->modalTitle('Edit currency id ' . $currencyRate->id)
                            ->method('saveCurrencyRate')
                            ->asyncParameters(['id' => $currencyRate->id]),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Are you sure you want to delete the currency rate?'))
                            ->method('remove', ['id' => $currencyRate->id]),
                    ])),
        ];
    }
}
