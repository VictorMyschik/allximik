<?php

namespace App\Orchid\Screens\Links;

use App\Models\UserLink;
use App\Orchid\Filters\Links\LinkListFilerFilter;
use App\Orchid\Layouts\Links\LinksListLayout;
use App\Orchid\Layouts\Links\ShowQueryLayout;
use App\Services\ParsingService\LinkRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LinkListScreen extends Screen
{
    public $name = 'Links';

    public function __construct(private Request $request, private LinkRepositoryInterface $linkRepository) {}

    public function query(): array
    {
        return [
            'list' => LinkListFilerFilter::queryQuery(),
        ];
    }

    public function layout(): iterable
    {
        return [
            LinkListFilerFilter::displayFilterCard($this->request),
            LinksListLayout::class,

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
        $userLink = UserLink::loadByOrDie((int)$request->get('id'));
        $list = $this->linkRepository->getUserIdsByLinkId($userLink->getLinkId());

        if (count($list) > 1) {
            $userLink->delete();
            return;
        }

        $userLink->getLink()->delete();

        Toast::warning(__('Link was removed'))->delay(1000);
    }

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $list = [];
        foreach (LinkListFilerFilter::FIELDS as $item) {
            if (!is_null($request->get($item))) {
                $list[$item] = $request->get($item);
            }
        }

        return redirect()->route('links.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('links.list');
    }
    #endregion
}
