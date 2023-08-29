<?php

namespace App\Orchid\Layouts\User;

use App\Models\User;
use App\Models\UserInfo;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class UserInfoEditLayout extends Rows
{
  public function fields(): array
  {
    $out[] = Select::make('info.user_id')
      ->empty()
      ->required()
      ->options(User::all()->pluck('name', 'id')->toArray())
      ->value(request()->get('user.user_id'))
      ->title('User');

    $out[] =Input::make('info.full_name')
      ->type('text')
      ->required()
      ->title('Address');

    $out[] = Select::make('info.gender')
      ->required()
      ->options(UserInfo::getGenderList())
      ->value(request()->get('info.gender'))
      ->title('Gender');

    $out[] = TextArea::make('info.about')
      ->type('text')
      ->rows(5)
      ->maxlength(8000)
      ->title('About me');

    // Birthday
    $out[] = Input::make('info.birthday')
      ->type('date')
      //->required()
      ->title('Birthday');

    return $out;
  }
}
