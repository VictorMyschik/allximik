<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Models\System\Settings;
use App\Models\User;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
  public const PERMISSION_TRAVEL = 'Travel';
  public const PERMISSION_SYSTEM = 'System';
  public const PERMISSION_SETTINGS = 'Settings';
  public const PERMISSION_LANGUAGE = 'Language';

  public function boot(Dashboard $dashboard): void
  {
    parent::boot($dashboard);

    // ...
  }

  public function menu(): array
  {
    // Travel
    $menu[] = Menu::make('Travel list')->title('Travel')->icon('bs.list')->route('travel.list');

    // References
    $menu[] = Menu::make('References')->icon('grid')->list([
      Menu::make('CountryResponse')->icon('bs.list')->route('reference.country.list'),
      Menu::make('Currency')->icon('bs.list')->route('reference.currency.list'),
      Menu::make('Currency Rate')->icon('bs.list')->route('reference.currency-rate.list'),
      Menu::make('Travel types')->icon('bs.list')->route('reference.travel-type.list'),
      Menu::make('Emails')->icon('bs.list')->route('reference.email.list'),
      Menu::make('Category Equipments')->icon('bs.list')->route('reference.category.equipments.list'),
      Menu::make('Equipments')->icon('bs.list')->route('reference.equipments.list'),
    ]);

    $menu[] = Menu::make('Users')->icon('grid')->list([
      Menu::make('User list')->icon('bs.list')->route('user.info.list'),
      Menu::make('User Contacts')->icon('bs.list')->route('user.info.address.list'),
    ]);

    $menu[self::PERMISSION_SETTINGS] = Menu::make('System')->icon('info')->list([
      Menu::make('Settings')->icon('settings')->route('setup.list'),
      Menu::make('Cache')->icon('database')->route('system.info.cache'),
      Menu::make('Cron')->icon('calendar')->route('system.info.cron'),
    ])->divider();

    // FAQ
    $menu[] = Menu::make('FAQ')->title('Information')->icon('bs.book')->route('faq.list');


    $menu[] = Menu::make(__('Users (system)'))->icon('bs.people')->route('platform.systems.users')
      ->permission('platform.systems.users')->title(__('Access Controls'));

    $menu[] = Menu::make(__('Roles'))->icon('bs.lock')->route('platform.systems.roles')
      ->permission('platform.systems.roles')->divider();

    $menu[self::PERMISSION_LANGUAGE] = Menu::make('Language')->icon('language')->route('language.list');

    foreach ($menu as $name => $item) {
      if (is_numeric($name)) {
        continue;
      }

      if (!User::canView($name)) {
        unset($menu[$name]);
      }
    }

    return $menu;
  }

  private function categoryMenu(): array
  {
    $list = [];

    foreach (array_unique(array_column(Settings::getSettingList(), 'category')) as $item) {
      $list[$item] = Menu::make($item)
        ->icon('target')
        ->route('setup.list', ['category' => [$item]]);
    }

    ksort($list);

    return $list;
  }

  public function permissions(): array
  {
    $groups = [];

    $group = ItemPermission::group('System');
    $group->addPermission('platform.systems.roles', __('Roles'));
    $group->addPermission('platform.systems.users', __('Users'));
    $groups[] = $group;

    foreach (self::getGroupList() as $groupName) {
      // Set group
      $group = ItemPermission::group($groupName);
      // Set permissions
      foreach (['view', 'edit', 'delete'] as $value) {
        $group->addPermission($groupName . '.' . $value, $value);
      }

      $groups[] = $group;
    }

    return $groups;
  }

  private static function getGroupList(): array
  {
    return [
      self::PERMISSION_TRAVEL,
      self::PERMISSION_SETTINGS,
      self::PERMISSION_SYSTEM,
      self::PERMISSION_LANGUAGE,
    ];
  }
}
