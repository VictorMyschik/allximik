<?php

namespace App\Orchid\Layouts\User;

use App\Helpers\System\MrDateTime;
use App\Models\Communicate;
use App\Models\UserInfo;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class UserCommunicateListLayout extends Table
{
    public $target = 'user-info-address';

    public function columns(): array
    {
        return [
            TD::make('user_id', 'User ID')->sort(),
            TD::make('name', 'Login')->sort(),
            TD::make('email', 'Email (registered)')->sort(),
            TD::make('gender', 'Gender')->render(fn($gender) => !is_null($gender['gender']) ? UserInfo::getGenderList()[$gender['gender']] : 'unknown')->sort(),
            TD::make('full_name', 'Full name')->sort(),
            TD::make('kind', 'Kind')->render(fn($kind) => !is_null($kind['kind']) ? Communicate::getKindList()[$kind['kind']] : '')->sort(),
            TD::make('address', 'Address')->sort(),
            TD::make('created_at', 'Created')->render(fn($communicate) => $communicate['created_at']?->format(MrDateTime::SHORT_TIME_SHORT_DATE))->sort(),
            TD::make('updated_at', 'Updated')->render(fn($communicate) => $communicate['updated_at']?->format(MrDateTime::SHORT_TIME_SHORT_DATE))->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn($communicate) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        ModalToggle::make('Edit')
                            ->type(Color::PRIMARY())
                            ->icon('pencil')
                            ->modal('communicate_modal')
                            ->modalTitle('Edit communicate id ' . $communicate->id)
                            ->method('saveCommunicate')
                            ->asyncParameters(['id' => $communicate->id]),
                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Are you sure you want to delete the communicate?'))
                            ->method('remove', ['id' => $communicate->id]),
                    ])),
        ];
    }
}
