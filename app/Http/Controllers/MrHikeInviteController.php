<?php

namespace App\Http\Controllers;

use App\Models\EmailInvite;
use App\Models\UIH;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MrHikeInviteController
{
  public function index(string $token, string $status): RedirectResponse
  {
    $token = substr($token, 0, 32);
    abort_if(!preg_match('/[a-z0-9]{32}/', $token), 404);

    /** @var EmailInvite $emailInvite */
    $emailInvite = EmailInvite::where('token', $token)->first();

    if (!$emailInvite) {
      return redirect('/');
    }

    $user = User::where('email', $emailInvite->getEmail())->first();

    if ($user) {
      Auth::login($user);

      $status = $status === 'true' ? UIH::STATUS_APPROVED : UIH::STATUS_REJECTED;

      UIH::where('hike_id', $emailInvite->getHike()->id())->where('user_id', $user->id)->updateOrCreate([
        'hike_id' => $emailInvite->getHike()->id(),
        'user_id' => $user->id,
      ], [
        'status' => $status,
      ]);

      $publicId = $emailInvite->getHike()->getPublicId();

      $emailInvite->delete_mr();

      return redirect()->route('hike.public.link', ['token' => $publicId]);
    }

    if ($status === 'false') {
      return redirect('/');
    }

    Auth::logout();
    return redirect()->route('register')->withInput(['email' => $emailInvite->getEmail(), 'token' => $token]);
  }
}
