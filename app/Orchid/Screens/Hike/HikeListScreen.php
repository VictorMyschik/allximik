<?php

namespace App\Orchid\Screens\Hike;

use App\Models\Hike;
use App\Orchid\Layouts\Hike\HikeEditLayout;
use App\Orchid\Layouts\Hike\HikeListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class HikeListScreen extends Screen
{
  public function query(): iterable
  {
    return [
      'list' => Hike::filters([])->paginate(20)
    ];
  }

  public function name(): ?string
  {
    return 'Походы';
  }

  public function description(): ?string
  {
    return "Список походов";
  }

  public function commandBar(): iterable
  {
    return [
      ModalToggle::make('Add')
        ->type(Color::PRIMARY())
        ->icon('plus')
        ->modal('hike_modal')
        ->modalTitle('Create New Hike')
        ->method('saveHike')
        ->asyncParameters(['id' => 0])
    ];
  }

  public function layout(): iterable
  {
    return [
      HikeListLayout::class,
      Layout::modal('hike_modal', HikeEditLayout::class)->async('asyncGetHike'),
    ];
  }

  public function asyncGetHike(int $id = 0): array
  {
    return [
      'hike' => Hike::loadBy($id) ?: new Hike()
    ];
  }

  public function saveHike(Request $request): void
  {
    $data = $request->validate([
      'hike.name'         => 'required|string|max:255',
      'hike.description'  => 'nullable|string|max:8000',
      'hike.status'       => 'required|integer',
      'hike.user_id'      => 'required|integer',
      'hike.country_id'   => 'required|integer',
      'hike.hike_type_id' => 'required|integer',
    ])['hike'];

    $hike = Hike::loadBy($request->get('id')) ?: new Hike();
    $hike->fill($data);
    $hike->save_mr();

    Toast::info('Hike was saved');
  }

  public function remove(int $id): void
  {
    Hike::loadBy($id)?->delete_mr();
  }
}
