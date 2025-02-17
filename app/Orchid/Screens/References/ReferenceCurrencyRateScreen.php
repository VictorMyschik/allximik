<?php

namespace App\Orchid\Screens\References;

use App\Classes\Cron\CurrencyRateService;
use App\Models\Reference\CurrencyRate;
use App\Orchid\Layouts\References\CurrencyRateEditLayout;
use App\Orchid\Layouts\References\CurrencyRateListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReferenceCurrencyRateScreen extends Screen
{
    public function __construct(private readonly CurrencyRateService $service) {}

    public function query(): iterable
    {
        return [
            'list' => CurrencyRate::filters([])->paginate(20)
        ];
    }

    public function name(): ?string
    {
        return 'Курсы валют';
    }

    public function description(): ?string
    {
        return "Справочник курсов валют. Список обновляется по нац.банкам стран";
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add')
                ->type(Color::PRIMARY())
                ->icon('plus')
                ->modal('currency_rate_modal')
                ->modalTitle('Create New Currency Rate')
                ->method('saveCurrencyRate')
                ->asyncParameters(['id' => 0]),
            Button::make('Clear All')->method('clearAllCurrencyRates')->icon('trash'),
            Button::make('Load All')->method('loadAllCurrencyRates')->icon('upload'),
        ];
    }

    public function layout(): iterable
    {
        return [
            CurrencyRateListLayout::class,
            Layout::modal('currency_rate_modal', CurrencyRateEditLayout::class)->async('asyncGetCurrencyRate'),
        ];
    }

    public function loadAllCurrencyRates(): void
    {
        $this->service->update();
    }

    public function clearAllCurrencyRates(): void
    {
        CurrencyRate::truncate();
    }

    public function asyncGetCurrencyRate(int $id = 0): array
    {
        return [
            'currency-rate' => CurrencyRate::loadBy($id) ?: new CurrencyRate()
        ];
    }

    public function saveCurrencyRate(Request $request): void
    {
        $data = $request->validate([
            'currency-rate.currency_id' => 'required|integer',
            'currency-rate.rate'        => 'required|numeric',
        ])['currency-rate'];

        CurrencyRate::updateOrCreate(
            ['id' => (int)$request->get('id')],
            $data
        );

        Toast::info('Currency Rate was saved');
    }

    public function remove(int $id): void
    {
        CurrencyRate::loadBy($id)?->delete_mr();
    }
}
