<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\FAQ;

use App\Models\Faq;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class FAQListLayout extends Table
{
  public $target = 'list';

  public function columns(): array
  {
    return [
      TD::make('title', __('Title')),
      TD::make('text', __('Text'))->render(function (Faq $faq) {
        return $faq->getText();
      }),

      TD::make(__('Actions'))
        ->align(TD::ALIGN_CENTER)
        ->width('100px')
        ->render(fn(Faq $faq) => DropDown::make()
          ->icon('bs.three-dots-vertical')
          ->list([
            ModalToggle::make('Edit')
              ->type(Color::PRIMARY())
              ->icon('pencil')
              ->modal('faq_modal')
              ->modalTitle('Edit FAQ id ' . $faq->id)
              ->method('saveFAQ')
              ->asyncParameters(['id' => $faq->id]),

            Button::make(__('Delete'))
              ->icon('bs.trash3')
              ->confirm(__('Are you sure you want to delete the faq?'))
              ->method('remove', ['id' => $faq->id]),
          ])),
    ];
  }
}
