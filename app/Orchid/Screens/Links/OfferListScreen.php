<?php

namespace App\Orchid\Screens\Links;

use App\Models\Offer;
use App\Models\UserLink;
use App\Orchid\Filters\Links\OfferListFilerFilter;
use App\Orchid\Layouts\Links\OffersListLayout;
use App\Orchid\Layouts\Links\ShowQueryLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class OfferListScreen extends Screen
{
    public $name = 'Offers';

    public function __construct(private Request $request) {}

    public function query(): array
    {
        return [
            'list' => OfferListFilerFilter::queryQuery(),
        ];
    }

    public function layout(): iterable
    {
        return [
            OfferListFilerFilter::displayFilterCard($this->request),
            OffersListLayout::class,

            Layout::modal('show_link_query', ShowQueryLayout::class)->withoutApplyButton()->async('asyncGetLink'),
        ];
    }

    public function asyncGetLink(int $id): array
    {
        $userLink = UserLink::loadByOrDie($id);
        parse_str($userLink->getLink()->getQuery(), $input);

        return ['link' => $input];
    }

    public function remove(Request $request): void
    {
        $offer = Offer::loadByOrDie((int)$request->get('id'));
        $offer->delete();
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $list = [];
        foreach (OfferListFilerFilter::FIELDS as $item) {
            if (!is_null($request->get($item))) {
                $list[$item] = $request->get($item);
            }
        }

        return redirect()->route('offers.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('offers.list');
    }
    #endregion
}
