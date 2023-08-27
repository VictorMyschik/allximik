<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @param Dashboard $dashboard
   *
   * @return void
   */
  public function boot(Dashboard $dashboard): void
  {
    parent::boot($dashboard);

    // ...
  }

  /**
   * Register the application menu.
   *
   * @return Menu[]
   */
  public function menu(): array
  {
    return [
      Menu::make('Get Started')->title('Navigation')->route(config('platform.index')),
      // Hike
      Menu::make('Hike list')->title('Hike')->icon('bs.list')->route('hike.list'),
      // References

      Menu::make('Country')->title('References')->icon('bs.list')->route('reference.country.list'),
      Menu::make('Currency')->icon('bs.list')->route('reference.currency.list'),
      Menu::make('Currency Rate')->icon('bs.list')->route('reference.currency-rate.list'),
      Menu::make('Hike types')->icon('bs.list')->route('reference.hike-type.list'),

      // FAQ
      Menu::make('FAQ')->title('Information')->icon('bs.book')->route('faq.list'),

      Menu::make(__('Users'))
        ->icon('bs.people')
        ->route('platform.systems.users')
        ->permission('platform.systems.users')
        ->title(__('Access Controls')),

      Menu::make(__('Roles'))
        ->icon('bs.lock')
        ->route('platform.systems.roles')
        ->permission('platform.systems.roles')
        ->divider(),

    ];
  }

  /**
   * Register permissions for the application.
   *
   * @return ItemPermission[]
   */
  public function permissions(): array
  {
    return [
      ItemPermission::group(__('System'))
        ->addPermission('platform.systems.roles', __('Roles'))
        ->addPermission('platform.systems.users', __('Users')),
    ];
  }
}
