<?php

namespace App\Orchid\Screens\Hike;

use App\Classes\Email\EmailService;
use App\Mail\WebhookEmail;
use App\Models\EmailInvite;
use App\Models\Hike;
use App\Models\UIH;
use App\Orchid\Layouts\Hike\HikeEditLayout;
use App\Orchid\Layouts\Hike\InviteByEmailEditLayout;
use App\Orchid\Layouts\Hike\InviteListLayout;
use App\Orchid\Layouts\Hike\UIHActiveListLayout;
use App\Orchid\Layouts\Hike\UIHNotActiveListLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class HikeDetailsScreen extends Screen
{
  public ?Hike $hike = null;

  public function query(Hike $hike): array
  {
    return [
      'hike'           => $hike,
      'active-uih'     => UIH::filters([])->where('hike_id', $hike->id())->where('status', UIH::STATUS_APPROVED)->paginate(20),
      'not-active-uih' => UIH::filters([])->where('hike_id', $hike->id())->where('status', UIH::STATUS_REJECTED)->paginate(20),
      'invite-uih'     => EmailInvite::filters([])->where('hike_id', $hike->id())->paginate(20)
    ];
  }

  public function name(): ?string
  {
    return $this->hike?->getName();
  }

  public function description(): ?string
  {
    return $this->hike?->getDescription();
  }

  public function commandBar(): iterable
  {
    $id = (int)$this->hike?->id();

    return [
      ModalToggle::make('Edit')
        ->type(Color::BASIC())
        ->icon('pencil')
        ->modal('hike_modal')
        ->modalTitle('Edit hike id')
        ->method('saveHike')
        ->asyncParameters(['id' => $id]),

      Button::make(__('Delete'))
        ->icon('bs.trash3')
        ->confirm(__('Are you sure you want to delete the hike?'))
        ->method('remove', ['id' => $id]),
    ];
  }

  public function layout(): iterable
  {
    $out = [Layout::modal('hike_modal', HikeEditLayout::class)->async('asyncGetHike')];

    if ($hike = Hike::loadBy((int)$this->hike?->id())) {
      $publicLink = route('hike.public.link', ['token' => $hike->getPublicId()]);

      $out[] = Layout::modal('new_invite_email_modal', InviteByEmailEditLayout::class);

      $columns[] = Layout::legend('Описание похода', [
        Sight::make('Статус')->render(fn() => $hike->getStatusName()),
        Sight::make('Страна')->render(fn() => $hike->getCountry()->getName()),
        Sight::make('Тип похода')->render(fn() => $hike->getHikeType()->getName()),
        Sight::make('Тип публичности')->render(fn() => $hike->getPublicName())->popover(Hike::getPublicDescription()[$hike->getPublic()]),
        Sight::make('Публичная страница')
          ->render(fn() => Hike::PUBLIC_FOR_ME !== $hike->getPublic() ? "<a target='_blank' href='" . $publicLink . "'>$publicLink</a>" : 'Публичная страница не доступна'),
      ])->title('Основная информация');

      if (Hike::PUBLIC_FOR_ME !== $hike->getPublic()) {
        $columns[] = Layout::tabs([
          'Активные'   => UIHActiveListLayout::class,
          'Отказ'      => UIHNotActiveListLayout::class,
          'В ожидании' => InviteListLayout::class,
          'Пригласить' => Layout::rows([
            Group::make([
              Link::make('QR code')->icon('qrcode')->target('_blank')->type(Color::INFO())
                ->href('https://api.qrserver.com/v1/create-qr-code/?data=' . $publicLink . '&amp;size=200x200'),

              ModalToggle::make('Email')
                ->type(Color::INFO())
                ->modal('new_invite_email_modal')
                ->modalTitle('Create new invite by email')
                ->method('createNewInvite')
                ->asyncParameters(['id' => $hike->id()]),
            ])->autoWidth()
          ])
        ])->activeTab('Активные');
      }

      $out[] = Layout::columns($columns);
    }

    return $out;
  }

  public function asyncGetHike(int $id = 0): array
  {
    return [
      'hike' => Hike::loadBy($id) ?: new Hike()
    ];
  }

  public function asyncGetUIH(int $id = 0): array
  {
    return [
      'uih' => UIH::loadBy($id) ?: new UIH()
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
      'hike.public'       => 'required|integer',
    ])['hike'];

    $hike = Hike::loadBy($request->get('id')) ?: new Hike();
    $hike->fill($data);
    $hike->save_mr();

    Toast::info('Hike was saved');
  }

  public function saveUIH(Request $request): void
  {
    $data = $request->validate([
      'uih.status' => 'required|integer',
    ])['uih'];

    UIH::updateOrCreate(
      ['id' => (int)$request->get('id')],
      $data
    );

    Toast::info('UIH was saved');
  }

  public function remove(int $id): RedirectResponse
  {
    Hike::loadBy($id)?->delete_mr();

    return redirect()->route('hike.list');
  }

  public function removeUIH(int $id): void
  {
    UIH::loadBy($id)?->delete_mr();
  }

  public function createNewInvite(int $id): void
  {
    $email = request()->validate([
      'email' => 'required|email|max:255'
    ])['email'];

    $exists = DB::table(EmailInvite::getTableName())->where('email', $email)->where('hike_id', $id)->exists();
    if ($exists) {
      Toast::error('Приглашение уже отправлено');
      return;
    }


    $hike = Hike::loadByOrDie($id);

    $invite = new EmailInvite();
    $invite->setEmail($email);
    $invite->setHikeId($hike->id());
    $invite->setStatus(EmailInvite::STATUS_NEW);
    $invite->setToken($invite->generateToken());
    $invite->setUserID($hike->getUser()->id);
    $invite->save_mr();

    EmailService::sendHikeInvite($invite);

    Toast::info('Приглашение отправлено');
  }

  public function resendEmailInvite(int $id): void
  {
    $invite = EmailInvite::loadByOrDie($id);
    EmailService::sendHikeInvite($invite);
    Toast::info('Приглашение отправлено');
  }
}
