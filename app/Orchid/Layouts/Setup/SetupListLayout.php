<?php

namespace App\Orchid\Layouts\Setup;

use App\Helpers\System\MrDateTime;
use App\Models\System\Settings;
use App\Models\User;
use App\Orchid\Screens\Settings\SettingsScreen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SetupListLayout extends Table
{
  protected $target = 'list';

  protected function columns(): iterable
  {
    return [
      TD::make('id', 'ID')->sort(),
      TD::make('active', 'Active')->sort()->active(),
      TD::make('category')->sort()->defaultHidden(),
      TD::make('name')->sort(),
      TD::make('code_key')->sort()->defaultHidden(),
      TD::make('value', 'Value')->width('50%'),
      TD::make('description', 'Description')->width('50%')->defaultHidden(),
      TD::make('created_at', 'Created')
        ->render(fn(Settings $setup) => MrDateTime::toDateTime($setup->getCreatedObject()))
        ->sort()
        ->defaultHidden(),
      TD::make('updated_at', 'Updated')
        ->render(fn(Settings $setup) => $setup->getUpdatedObject()?->format(MrDateTime::AMERICAN_DATE_TIME))
        ->sort()
        ->defaultHidden(),

      TD::make(__('Actions'))
        ->align(TD::ALIGN_CENTER)
        ->width('100px')
        ->render(function (Settings $setup) {
          return DropDown::make()->icon('options-vertical')->list([
            ModalToggle::make('Edit')
              ->icon('pencil')
              ->modal(SettingsScreen::SETUP_MODAL)
              ->modalTitle(SettingsScreen::SETUP_MODAL_TITLE)
              ->method('saveSetup')
              ->asyncParameters(['id' => $setup->id()]),

            Button::make('Delete')
              ->icon('trash')
              ->confirm('This item will be removed permanently.')
              ->method('remove', [
                'id' => $setup->id,
              ])->canSee(User::canDelete(SettingsScreen::$screen)),
          ]);
        })->canSee(User::canEdit(SettingsScreen::$screen)),
    ];
  }
}
