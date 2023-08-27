<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Support\Color;

class ActionFilterPanel
{
  public static function getActionsButtons(int $hikeID): Group
  {
    return Group::make([
      ModalToggle::make('Email')
        ->type(Color::INFO())
        ->modal('new_invite_email_modal')
        ->modalTitle('Create new invite')
        ->method('createNewInvite')
        ->asyncParameters(['id' => $hikeID]),

      ModalToggle::make('QR code')
        ->type(Color::INFO())
        ->modal('new_invite_qr_modal')
        ->modalTitle('Create new invite')
        ->method('showQRCode')
        ->asyncParameters(['id' => $hikeID]),
    ])->autoWidth();
  }
}
