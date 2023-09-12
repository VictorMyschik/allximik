<?php

namespace App\Orchid\Layouts\Travel;

use App\Models\Settings;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class TravelImageUploadMainEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      Group::make([
        Upload::make('images')->title('Main photo')->groups('photo')->maxFiles(10)->maxFileSize(Settings::getMaxUploadFileSize()), // Size in MB
      ]),
    ];
  }
}
