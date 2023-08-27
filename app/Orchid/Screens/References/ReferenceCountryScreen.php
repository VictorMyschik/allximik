<?php

namespace App\Orchid\Screens\References;

use App\Models\Reference\Country;
use App\Orchid\Layouts\References\CountryEditLayout;
use App\Orchid\Layouts\References\CountryListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReferenceCountryScreen extends Screen
{
  public function query(): iterable
  {
    return [
      'list' => Country::filters([])->paginate(20)
    ];
  }

  public function name(): ?string
  {
    return 'Страны';
  }

  public function description(): ?string
  {
    return 'Справочник стран';
  }

  public function commandBar(): iterable
  {
    return [
      ModalToggle::make('Add')
        ->type(Color::PRIMARY())
        ->icon('plus')
        ->modal('country_modal')
        ->modalTitle('Create New Country')
        ->method('saveCountry')
        ->asyncParameters(['id' => 0])
    ];
  }

  public function layout(): iterable
  {
    return [
      CountryListLayout::class,
      Layout::modal('country_modal', CountryEditLayout::class)->async('asyncGetCountry'),
    ];
  }

  public function asyncGetCountry(int $id = 0): array
  {
    return [
      'country' => Country::loadBy($id) ?: new Country()
    ];
  }

  public function saveCountry(Request $request): void
  {
    $data = $request->validate([
      'country.name' => 'required|string|max:255',
      'country.continent' => 'required|integer|min:0|max:7',
      'country.iso3166alpha2' => 'required|string|max:2',
      'country.iso3166alpha3' => 'required|string|max:3',
      'country.iso3166numeric' => 'required|string|max:3',
    ])['country'];

    Country::updateOrCreate(
      ['id' => (int)$request->get('id')],
      $data
    );

    Toast::info('Country was saved');
  }

  public function remove(int $id): void
  {
    Country::loadBy($id)?->delete_mr();
  }
}
