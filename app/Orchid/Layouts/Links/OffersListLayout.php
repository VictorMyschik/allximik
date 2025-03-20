<?php

namespace App\Orchid\Layouts\Links;

use App\Models\Link;
use App\Models\Offer;
use App\Services\ParsingService\ExtractorInterface;
use App\Services\ParsingService\LinkRepositoryInterface;
use App\Services\Telegram\OfferExtractor;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OffersListLayout extends Table
{
    protected $target = 'list';

    public function __construct(
        private readonly LinkRepositoryInterface $linkRepository,
    ) {}

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')->sort(),
            TD::make('', 'Site')->render(function (Offer $offer) {
                return $offer->getLink()->getType()->value;
            }),
            TD::make('', 'Photo')->render(function (Offer $offer) {
                if ($this->extract($offer)->getPhoto()) {
                    return View('admin.image')->with(['path' => $this->extract($offer)->getPhoto()]);
                }
                return '';
            })->sort(),
            TD::make('', 'Price')->render(function (Offer $offer) {
                return $this->extract($offer)->getPrice();
            })->sort(),
            TD::make('Title')->render(function (Offer $offer) {
                return $this->extract($offer)->getTitle();
            })->sort(),
            TD::make('Link')->render(function (Offer $offer) {
                return \Orchid\Screen\Actions\Link::make('Link')->icon('eye')->target('_blank')->href($this->extract($offer)->getLink());
            })->sort(),
            TD::make('user')->render(function (Offer $offer) {
                return implode(', ', $this->linkRepository->getUserIdsByLinkId($offer->getLink()->id));
            })->sort(),

            TD::make('created_at', 'Created')
                ->render(fn(Offer $offer) => $offer->created_at->format('d.m.Y'))
                ->sort(),
            TD::make('updated_at', 'Updated')
                ->render(fn(Offer $offer) => $offer->updated_at?->format('d.m.Y'))
                ->sort(),

            TD::make('#', 'Действия')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Offer $offer) {
                    return DropDown::make()->icon('options-vertical')->list([
                        Button::make('delete')
                            ->icon('trash')
                            ->confirm('This item will be removed permanently.')
                            ->method('remove', [
                                'id' => $offer->id,
                            ]),
                    ]);
                }),
        ];
    }

    private function extract(Offer $offer): ExtractorInterface
    {
        $data = json_decode($offer->getSl(), true, 512, JSON_THROW_ON_ERROR);

        return new OfferExtractor($offer->getLink()->getType(), $data);
    }

    protected function hoverable(): bool
    {
        return true;
    }
}
