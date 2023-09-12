<?php

namespace App\Orchid\Screens\Settings;

use App\Models\Settings;
use App\Models\User;
use App\Orchid\Filters\SetupFilter;
use App\Orchid\Layouts\Setup\SetupEditLayout;
use App\Orchid\Layouts\Setup\SetupListLayout;
use App\Orchid\PlatformProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SettingsScreen extends Screen
{
    public const SETUP_MODAL = 'setup_modal';

    public const SETUP_MODAL_TITLE = 'Settings';

    public string $name = 'Settings';

    public string $description = 'Managing Platform Settings';

    public static string $screen = PlatformProvider::PERMISSION_SETTINGS;

    public function permission(): ?iterable
    {
        return [
            self::$screen . '.' . User::TYPE_VIEW,
        ];
    }

    public function query(): iterable
    {
        return [
            'setup' => SetupFilter::query(),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add')
                ->type(Color::PRIMARY())
                ->icon('plus')
                ->modal(self::SETUP_MODAL)
                ->modalTitle(self::SETUP_MODAL_TITLE)
                ->method('saveSetup')
                ->asyncParameters(['id' => 0])
                ->canSee(User::canEdit(PlatformProvider::PERMISSION_SETTINGS)),
        ];
    }

    public function layout(): iterable
    {
        return [
            SetupFilter::displayFilterCard(),
            SetupListLayout::class,
            Layout::modal(self::SETUP_MODAL, SetupEditLayout::class)->async('asyncGetSetup'),
        ];
    }

    #region Popup From
    public function asyncGetSetup(int $id): iterable
    {
        abort_unless(User::canEdit(self::$screen), 403);

        return [
            'setup' => Setup::loadBy($id) ?: new Setup(),
        ];
    }

    public function saveSetup(Request $request): void
    {
        abort_unless(User::canEdit(self::$screen), 403);

        $id = (int) $request->get('id');
        $setupIn = $request->get('setup');

        $setup = Setup::loadBy($id) ?: new Setup();

        $setup->setActive((bool) $setupIn['active']);
        $setup->setCategory($setupIn['category']);
        $setup->setName($setupIn['name']);
        $setup->setValue($setupIn['value']);
        $setup->setCodeKey($setupIn['code_key']);
        $setup->setDescription($setupIn['description']);

        $id = $setup->save_mr();

        Toast::success('Saved with ID' . $id);
    }

    public function remove(Request $request): void
    {
        abort_unless(User::canDelete(self::$screen), 403);

        $setup = Setup::loadByOrDie((int) $request->get('id'));
        $setup->delete_mr();

        Toast::warning(__('Setup was removed'));
    }
    #endregion

    #region Filter
    public function runFiltering(Request $request): RedirectResponse
    {
        $list = [];
        foreach (SetupFilter::getFilterFields() as $item) {
            if (! is_null($request->get($item))) {
                $list[$item] = $request->get($item);
            }
        }

        return redirect()->route('setup.list', $list);
    }

    public function clearFilter(): RedirectResponse
    {
        return redirect()->route('setup.list');
    }
    #endregion
}
