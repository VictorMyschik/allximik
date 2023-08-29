<?php

namespace App\Orchid\Layouts\User;

use App\Helpers\System\MrDateTime;
use App\Models\User;
use App\Models\UserInfo;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserInfoListLayout extends Table
{
  public $target = 'user-info';

  public function columns(): array
  {
    return [
      TD::make('id', 'ID')->sort(),
      TD::make('name', 'Login')->sort(),
      TD::make('email', 'Email')->sort(),
      TD::make('gender', 'Gender')->render(fn($gender) => !is_null($gender['gender']) ? UserInfo::getGenderList()[$gender['gender']] : 'unknown')->sort(),
      TD::make('full_name', 'Full name')->sort(),
      TD::make('created_at', 'Created')->render(fn(User $user) => $user->created_at?->format(MrDateTime::SHORT_TIME_SHORT_DATE))->sort(),
    ];
  }
}
