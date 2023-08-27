<?php

namespace App\Orchid\Screens\References;

use App\Models\HikeType;
use App\Orchid\Layouts\References\HikeTypeEditLayout;
use App\Orchid\Layouts\References\HikeTypeListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class HikeTypeListScreen extends Screen
{
  public function query(): iterable
  {
    return [
      'list' => HikeType::filters([])->paginate(20)
    ];
  }

  public function name(): ?string
  {
    return 'Типы походов';
  }

  public function description(): ?string
  {
    return 'Справочник типов походов';
  }

  public function commandBar(): iterable
  {
    return [
      ModalToggle::make('Add')
        ->type(Color::PRIMARY())
        ->icon('plus')
        ->modal('hike_type')
        ->modalTitle('Create New Hike Type')
        ->method('saveHikeType')
        ->asyncParameters(['id' => 0])
    ];
  }

  public function layout(): iterable
  {
    return [
      HikeTypeListLayout::class,
      Layout::modal('hike_type', HikeTypeEditLayout::class)->async('asyncGetHikeTypeList'),
    ];
  }

  public function asyncGetHikeTypeList(int $id = 0): array
  {
    return [
      'hike-type' => HikeType::loadBy($id) ?: new HikeType()
    ];
  }

  public function saveHikeType(Request $request): void
  {
    $data = $request->validate([
      'hike-type.name' => 'required|string',
      'hike-type.description' => 'nullable|string',
    ])['hike-type'];

    HikeType::updateOrCreate(
      ['id' => (int)$request->get('id')],
      $data
    );

    Toast::info('Hike type was saved');
  }

  public function remove(int $id): void
  {
    HikeType::loadBy($id)?->delete_mr();
  }
}
