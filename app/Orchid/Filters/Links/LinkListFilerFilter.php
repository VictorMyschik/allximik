<?php

namespace App\Orchid\Filters\Links;

use App\Models\Lego\ActionFilterPanel;
use App\Models\Link;
use App\Models\UserLink;
use App\Services\ParsingService\Enum\SiteType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\ViewField;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class LinkListFilerFilter extends Filter
{
    public const array FIELDS = [
        'type',
        'user',
    ];

    public static function queryQuery(): iterable
    {
        return Link::filters([self::class])->paginate(50);
    }

    public function run(Builder $builder): Builder
    {
        $input = $this->request->all();

        $builder->join(UserLink::getTableName(), Link::getTableName() . '.id', '=', UserLink::getTableName() . '.link_id');

        if (!empty($input['user'])) {
            $builder->where(UserLink::getTableName() . '.user', (int)$input['user']);
        }

        if (!is_null($input['type'] ?? null)) {
            $builder->where('type', (string)$input['type']);
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
                    ->title('Категория'),
                Select::make('user')
                    ->empty('[all]')
                    ->value($input['user'])
                    ->fromModel(UserLink::class, 'user', 'user')
                    ->title('Telegram User'),
            ]),

            ViewField::make('')->view('space'),

            ActionFilterPanel::getActionsButtons(),
        ]);
    }
}
