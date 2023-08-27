<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Email;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class EmailScreen extends Screen
{
  public function query(): iterable
  {
    return [];
  }

  public function name(): ?string
  {
    return 'Email';
  }

  public function description(): ?string
  {
    return 'Шаблоны писем';
  }

  public function commandBar(): iterable
  {
    return [];
  }

  public function layout(): iterable
  {
    $fakeData['hike_invite'] = [
      'token'     => '1234567890',
      'name'      => 'Восхождение на Казбек',
      'hike_type' => 'Горный поход',
    ];

    return [
      Layout::tabs([
        'Hike Invite' => Layout::view('emails.hike_invite', ['data' => $fakeData['hike_invite']]),
        //'Email'       => Layout::view('emails.hike_invite', ['data' => $fakeData['hike_invite']]),
      ]),
    ];
  }
}
