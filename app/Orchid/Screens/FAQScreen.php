<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Faq;
use App\Orchid\Layouts\FAQ\FAQEditLayout;
use App\Orchid\Layouts\FAQ\FAQListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class FAQScreen extends Screen
{
  public function query(): iterable
  {
    return [
      'list' => Faq::paginate(10)
    ];
  }

  public function name(): ?string
  {
    return 'FAQ';
  }

  public function description(): ?string
  {
    return 'Часто задаваемые вопросы';
  }

  public function commandBar(): iterable
  {
    return [
      ModalToggle::make('Add')
        ->type(Color::PRIMARY())
        ->icon('plus')
        ->modal('faq_modal')
        ->modalTitle('Create New FAQ')
        ->method('saveFAQ')
        ->asyncParameters(['id' => 0])
    ];
  }

  public function layout(): iterable
  {
    return [
      FaqListLayout::class,
      Layout::modal('faq_modal', FAQEditLayout::class)->async('asyncGetFAQ'),
    ];
  }

  public function asyncGetFAQ(Faq $faq): array
  {
    return [
      'faq' => $faq,
    ];
  }

  public function saveFAQ(Request $request): array
  {
    $data = $request->validate([
      'faq.title' => 'required|string',
      'faq.text' => 'required|string',
    ])['faq'];

    $faq = Faq::updateOrCreate(
      ['id' => (int)$request->get('id')],
      $data
    );

    Toast::info('FAQ was saved');

    return ['faq' => $faq,];
  }
}