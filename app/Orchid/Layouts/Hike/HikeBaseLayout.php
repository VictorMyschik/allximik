<?php

namespace App\Orchid\Layouts\Hike;

use App\Models\Hike;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class HikeBaseLayout extends Rows
{
  public function fields(): array
  {
    /** @var Hike $hike */
    $hike = $this->query['hike'];

    return [
        Layout::legend('', [
          Sight::make('id', 'ID'),
          Sight::make('name'),
          Sight::make('description'),
        ]),
      /*  Layout::legend('user', [
          Sight::make('id'),
        ]),*/
      // Label::make('country')->title('Страна')->value($hike->getCountry()->getName()),
      //Label::make('type')->title('Тип')->value($hike->getHikeType()->getName())->name('type'),
    ];
  }
}
