<?php

namespace App\Orchid\Screens\References;

use App\Models\Reference\Currency;
use App\Orchid\Layouts\References\CurrencyEditLayout;
use App\Orchid\Layouts\References\CurrencyListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReferenceCurrencyScreen extends Screen
{
  public function query(): iterable
  {
    return [
      'list' => Currency::filters([])->paginate(20)
    ];
  }

  public function name(): ?string
  {
    return 'Валюты';
  }

  public function description(): ?string
  {
    return 'Справочник валют';
  }

  public function commandBar(): iterable
  {
    return [
      ModalToggle::make('Add')
        ->type(Color::PRIMARY())
        ->icon('plus')
        ->modal('currency_modal')
        ->modalTitle('Create New Currency')
        ->method('saveCurrency')
        ->asyncParameters(['id' => 0])
    ];
  }

  public function layout(): iterable
  {
    return [
      CurrencyListLayout::class,
      Layout::modal('currency_modal', CurrencyEditLayout::class)->async('asyncGetCurrency'),
    ];
  }

  public function asyncGetCurrency(int $id = 0): array
  {
    return [
      'currency' => Currency::loadBy($id) ?: new Currency()
    ];
  }

  public function saveCurrency(Request $request): void
  {
    $data = $request->validate([
      'currency.name'        => 'required|string',
      'currency.code'        => 'required|string',
      'currency.text_code'   => 'required|string',
      'currency.rounding'    => 'required|integer',
      'currency.description' => 'nullable|string',
    ])['currency'];

    Currency::updateOrCreate(
      ['id' => (int)$request->get('id')],
      $data
    );

    Toast::info('Currency was saved');
  }

  public function remove(int $id): void
  {
    Currency::loadBy($id)?->delete_mr();
  }
}
