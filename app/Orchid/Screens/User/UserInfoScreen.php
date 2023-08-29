<?php

namespace App\Orchid\Screens\User;

use App\Orchid\Filters\UserInfoFilter;
use App\Orchid\Layouts\User\UserInfoListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class UserInfoScreen extends Screen
{
  public function name(): ?string
  {
    return 'Пользователи';
  }

  /**
   * Display header description.
   */
  public function description(): ?string
  {
    return 'Список пользователей платформы';
  }

  public function query(): iterable
  {
    return [
      'user-info' => UserInfoFilter::query(),
    ];
  }

  public function commandBar(): iterable
  {
    return [
    ];
  }

  /**
   * @return \Orchid\Screen\Layout[]
   */
  public function layout(): iterable
  {
    return [
      UserInfoFilter::displayFilterCard(),
      UserInfoListLayout::class,
    ];
  }

  public function runFiltering(Request $request): RedirectResponse
  {
    $list = [];
    foreach (UserInfoFilter::getFilterFields() as $item) {
      if (!is_null($request->get($item))) {
        $list[$item] = $request->get($item);
      }
    }

    return redirect()->route('user.info.list', $list);
  }

  public function clearFilter(): RedirectResponse
  {
    return redirect()->route('user.info.list');
  }
}
