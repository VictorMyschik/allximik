<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Communicate;
use App\Models\User;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserCommunicateEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      Select::make('communicate.user_id')
        ->empty()
        ->required()
        ->options(User::all()->pluck('name', 'id')->toArray())
        ->value(request()->get('user_id'))
        ->title('User'),

      Select::make('communicate.kind')
        ->empty()
        ->required()
        ->options(Communicate::getKindList())
        ->value(request()->get('kind'))
        ->title('Address kind'),

      Input::make('communicate.address')
        ->type('text')
        ->required()
        ->title('Address')
    ];
  }
}
