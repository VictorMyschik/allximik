<?php

namespace App\Orchid\Layouts\Language;

use App\Models\System\Translate;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class TranslateListLayout extends Table
{
    public $target = 'list';

    public function columns(): array
    {
        return [
            TD::make('id', __('ID'))->sort(),
            TD::make('code', __('Code'))->sort(),
            TD::make('translate', __('Translate'))->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Translate $translate) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->type(Color::PRIMARY())
                            ->icon('pencil')
                            ->modal('translate')
                            ->modalTitle('Translate id ' . $translate->id())
                            ->method('saveTranslate')
                            ->asyncParameters(['id' => $translate->id()]),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Are you sure you want to delete the Translate?'))
                            ->method('remove', ['id' => $translate->id()]),
                    ])),
        ];
    }
}
