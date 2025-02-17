<?php

namespace App\Classes\Email;

use App\Mail\TravelInviteEmail;
use App\Models\EmailInvite;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public static function sendTravelInvite(EmailInvite $invite): void
    {
        $data['subject'] = 'Приглашение в поход';
        $data['travel_type'] = $invite->getTravel()->getTravelType()->getName();
        $data['name'] = $invite->getTravel()->getName();
        $data['token'] = $invite->getToken();

        $result = Mail::to($invite->getEmail())->send(new TravelInviteEmail($data));

        if ($result) {
            $invite->setStatus(EmailInvite::STATUS_SEND);
        } else {
            $invite->setStatus(EmailInvite::STATUS_ERROR);
        }

        $invite->save_mr();
    }
}
