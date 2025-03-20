<?php

namespace App\Orchid\Layouts\Links;

use App\Models\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LinksListLayout extends Table
{
    protected $target = 'list';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('user')->sort(),
            TD::make('type')->sort(),
            TD::make('path')->sort(),
            TD::make('query')->render(function (Link $link) {
                return ModalToggle::make(' ')
                    ->icon('eye')
                    ->modal('show_link_query')
                    ->parameters(['id' => $link->id])
                    ->modalTitle('Query');
            })->sort(),
            TD::make('created_at', 'Created')
                ->render(fn(Link $link) => $link->created_at->format('d.m.Y'))
                ->sort()
                ->defaultHidden(),
            TD::make('updated_at', 'Updated')
                ->render(fn(Link $link) => $link->updated_at?->format('d.m.Y'))
                ->sort()
                ->defaultHidden(),

            TD::make('#', 'Действия')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Link $link) {
                    return DropDown::make()->icon('options-vertical')->list([
                        Button::make('delete')
                            ->icon('trash')
                            ->confirm('This item will be removed permanently.')
                            ->method('remove', [
                                'id' => $link->id,
                            ]),
                    ]);
                }),
        ];
    }
}
