<?php

namespace App\Classes\Email;

use App\Mail\HikeInviteEmail;
use App\Models\EmailInvite;
use Illuminate\Support\Facades\Mail;

class EmailService
{
  public static function sendHikeInvite(EmailInvite $invite): void
  {
    $data['subject'] = 'Приглашение в поход';
    $data['hike_type'] = $invite->getHike()->getHikeType()->getName();
    $data['name'] = $invite->getHike()->getName();

    $result = Mail::to($invite->getEmail())->send(new HikeInviteEmail($data));

    if ($result) {
      $invite->setStatus(EmailInvite::STATUS_SEND);
    } else {
      $invite->setStatus(EmailInvite::STATUS_ERROR);
    }

    $invite->save_mr();
  }
}
