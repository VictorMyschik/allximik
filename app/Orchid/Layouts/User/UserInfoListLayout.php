<?php

namespace App\Orchid\Layouts\User;

use App\Helpers\System\MrDateTime;
use App\Models\UserInfo;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserInfoListLayout extends Table
{
    public $target = 'user-info';

    public function columns(): array
    {

        return [TD::make('id', 'ID')->sort(),
            TD::make('user_id', 'User ID')->sort(),
            TD::make('name', 'Login')->sort(),
            TD::make('email', 'Email')->sort(),
            TD::make('gender', 'Gender')->render(fn($gender) => !is_null($gender['gender']) ? UserInfo::getGenderList()[$gender['gender']] : 'unknown')->sort(),
            TD::make('full_name', 'Full name')->sort(),
            TD::make('birthday', 'Birthday')->render(fn($user) => $user['birthday'] ? Carbon::parse($user['birthday'])->format(MrDateTime::SHORT_DATE) : '')->sort(),
            TD::make('created_at', 'Created')->render(fn($user) => $user['created_at']?->format(MrDateTime::SHORT_TIME_SHORT_DATE))->sort(),

            TD::make('Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(UserInfo $info) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->modal('user_info_modal')
                            ->method('saveUserInfo')
                            ->modalTitle('Изменить информацию о пользователе')
                            ->asyncParameters(['id' => $info->id])
                            ->icon('pen'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Are you sure you want to delete the info?'))
                            ->method('remove', ['id' => $info->id]),
                    ])),
        ];
    }
}
