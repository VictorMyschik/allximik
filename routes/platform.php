<?php

declare(strict_types=1);

use App\Orchid\Screens\Email\EmailScreen;
use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\FAQScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\References\CategoryEquipmentScreen;
use App\Orchid\Screens\References\EquipmentScreen;
use App\Orchid\Screens\References\ReferenceCountryScreen;
use App\Orchid\Screens\References\ReferenceCurrencyRateScreen;
use App\Orchid\Screens\References\ReferenceCurrencyScreen;
use App\Orchid\Screens\References\TravelTypeListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Settings\SettingsScreen;
use App\Orchid\Screens\Travel\TravelDetailsScreen;
use App\Orchid\Screens\Travel\TravelListScreen;
use App\Orchid\Screens\User\UserCommunicateScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserInfoScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
  ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
  ->name('platform.profile')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
  ->name('platform.systems.users.edit')
  ->breadcrumbs(fn(Trail $trail, $user) => $trail
    ->parent('platform.systems.users')
    ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
  ->name('platform.systems.users.create')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.systems.users')
    ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
  ->name('platform.systems.users')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
  ->name('platform.systems.roles.edit')
  ->breadcrumbs(fn(Trail $trail, $role) => $trail
    ->parent('platform.systems.roles')
    ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
  ->name('platform.systems.roles.create')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.systems.roles')
    ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
  ->name('platform.systems.roles')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('/form/examples/fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/form/examples/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/form/examples/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/form/examples/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/layout/examples/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/charts/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/cards/examples/cards', ExampleCardsScreen::class)->name('platform.example.cards');


Route::screen('/faq/list', FAQScreen::class)->name('faq.list');
Route::screen('/user-info/list', UserInfoScreen::class)->name('user.info.list');
Route::screen('/user-info/address/list', UserCommunicateScreen::class)->name('user.info.address.list');
// References
Route::screen('/reference/country/list', ReferenceCountryScreen::class)->name('reference.country.list');
Route::screen('/reference/currency/list', ReferenceCurrencyScreen::class)->name('reference.currency.list');
Route::screen('/reference/currency-rate/list', ReferenceCurrencyRateScreen::class)->name('reference.currency-rate.list');
Route::screen('/reference/category-equipments/list', CategoryEquipmentScreen::class)->name('reference.category.equipments.list');
Route::screen('/reference/equipments/list', EquipmentScreen::class)->name('reference.equipments.list');
// Travel
Route::screen('/travel-type/list', TravelTypeListScreen::class)->name('reference.travel-type.list');
Route::screen('/travel/list', TravelListScreen::class)->name('travel.list');
Route::screen('/travel/details/{travel}', TravelDetailsScreen::class)->name('travel.details');
Route::screen('/emails', EmailScreen::class)->name('reference.email.list');

// Setup
Route::screen('setup/list', SettingsScreen::class)->name('setup.list');
