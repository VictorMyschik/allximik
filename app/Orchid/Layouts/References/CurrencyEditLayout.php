<?php

namespace App\Orchid\Layouts\References;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class CurrencyEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            // continent
            Input::make('currency.name')
                ->type('text')
                ->max(200)
                ->required()
                ->title('Name'),

            Input::make('currency.code')
                ->type('text')
                ->max(3)
                ->required()
                ->title('Code'),

            Input::make('currency.text_code')
                ->type('text')
                ->max(3)
                ->required()
                ->title('Text Code'),

            Input::make('currency.rounding')
                ->type('number')
                ->max(6)
                ->required()
                ->title('Rounding'),

            Input::make('currency.description')
                ->type('text')
                ->max(255)
                ->title('Description'),
        ];
    }
}
