<?php

namespace App\Orchid\Screens\References;

use App\Models\Equipment;
use App\Orchid\Filters\EquipmentFilter;
use App\Orchid\Layouts\References\EquipmentEditLayout;
use App\Orchid\Layouts\References\EquipmentListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EquipmentScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'list' => EquipmentFilter::runQuery()
        ];
    }

    public function name(): ?string
    {
        return 'Снаряжение';
    }

    public function description(): ?string
    {
        return 'Справочник снаряжение';
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add')
                ->type(Color::PRIMARY())
                ->icon('plus')
                ->modal('equipment')
                ->modalTitle('Create New Equipment')
                ->method('saveEquipment')
                ->asyncParameters(['id' => 0])
        ];
    }

    public function layout(): iterable
    {
        return [
            EquipmentFilter::displayFilterCard(),
            EquipmentListLayout::class,
            Layout::modal('equipment', EquipmentEditLayout::class)->async('asyncGetEquipment'),
        ];
    }

    public function asyncGetEquipment(int $id = 0): array
    {
        return [
            'equipment' => Equipment::loadBy($id) ?: new Equipment()
        ];
    }

    public function saveEquipment(Request $request): void
    {
        $data = $request->validate([
            'equipment.name'        => 'required|string',
            'equipment.description' => 'nullable|string',
            'equipment.category_id' => 'nullable|integer',
        ])['equipment'];

        Equipment::updateOrCreate(
            ['id' => (int)$request->get('id')],
            $data
        );

        Toast::info('Equipment was saved');
    }

    public function remove(int $id): void
    {
        try {
            Equipment::loadBy($id)?->delete_mr();
        } catch (\Exception $e) {
            Toast::error($e->getMessage());
        }
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $list = [];
        foreach (EquipmentFilter::FIELDS as $item) {
            if (!is_null($request->get($item))) {
                $list[$item] = $request->get($item);
            }
        }

        return redirect()->route('reference.equipments.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('reference.equipments.list');
    }
    #endregion
}
