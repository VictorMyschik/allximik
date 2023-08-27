<?php

namespace App\Orchid\Layouts\Hike;

use App\Models\Hike;
use App\Models\HikeType;
use App\Models\Reference\Country;
use App\Models\User;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class HikeEditLayout extends Rows
{
  public function fields(): array
  {
    return [
      Input::make('hike.name')
        ->title('Name')
        ->required()
        ->maxlength(255),

      TextArea::make('hike.description')
        ->title('Description')
        ->rows(5)
        ->maxlength(8000),

      Select::make('hike.status')
        ->title('Status')
        ->required()
        ->options(Hike::getStatusList()),

      Select::make('hike.user_id')
        ->title('User')
        ->required()
        ->options(User::all()->pluck('name', 'id')->toArray()),

      Select::make('hike.country_id')
        ->title('Country')
        ->required()
        ->empty('Select country')
        ->options(Country::all()->pluck('name', 'id')->toArray()),

      Select::make('hike.hike_type_id')
        ->title('Hike type')
        ->required()
        ->empty('Select hike type')
        ->options(HikeType::all()->pluck('name', 'id')->toArray()),

      Select::make('hike.public')
        ->title('Public')
        ->required()
        ->empty('Select hike public type')
        ->options(Hike::getPublicList()),
    ];
  }
}
