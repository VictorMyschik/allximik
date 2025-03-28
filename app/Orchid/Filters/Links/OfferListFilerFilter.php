<?php

namespace App\Orchid\Filters\Links;

use App\Models\Lego\ActionFilterPanel;
use App\Models\Offer;
use App\Services\ParsingService\Enum\SiteType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class OfferListFilerFilter extends Filter
{
    public const array FIELDS = [
        'link_id',
        'type',
        'external_id',
    ];

    public static function queryQuery(): iterable
    {
        return Offer::filters([self::class])->select(Offer::getTableName() . '.*')->paginate(50);
    }

    public function run(Builder $builder): Builder
    {
        $input = $this->request->all();

        if (!is_null($input['type'] ?? null)) {
            $builder->where(Offer::getTableName() . '.type', (string)$input['type']);
        }

        if (!is_null($input['external_id'] ?? null)) {
            $builder->where(Offer::getTableName() . '.offer_id', (string)$input['external_id']);
        }

        return $builder;
    }

    public static function displayFilterCard(Request $request): Rows
    {
        $input = $request->all(self::FIELDS);

        return Layout::rows([
            Group::make([
                Select::make('type')
                    ->empty('[all]')
                    ->options(SiteType::getSelectList())
                    ->value($input['type'])
                    ->title('Type'),
                Input::make('external_id')
                    ->value($input['external_id'])
                    ->title('External ID'),
            ]),

            ViewField::make('')->view('space'),

            ActionFilterPanel::getActionsButtons(),
        ]);
    }
}
