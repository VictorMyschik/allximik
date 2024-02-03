<?php

namespace App\Orchid\Screens\SystemInfo;

use App\Classes\Cron\CronBase;
use App\Models\Cron;
use App\Orchid\Layouts\SystemInfo\CronEditLayout;
use App\Orchid\Layouts\SystemInfo\CronListLayout;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CronScreen extends Screen
{
  private const ALL_ACTIVE = -1;

  public ?string $name = 'Cron';

  public function query(): iterable
  {
    return [
      'list' => Cron::filters([])->paginate(),
    ];
  }

  public function commandBar(): iterable
  {
    return [
      ModalToggle::make('Add')
        ->type(Color::PRIMARY())
        ->icon('plus')
        ->modal('cron_modal')
        ->modalTitle('Create New Cron Job')
        ->method('saveCron')
        ->asyncParameters(['id' => 1]),

      Button::make('Run all active')
        ->icon('refresh')
        ->method('run', ['id' => self::ALL_ACTIVE])
        ->confirm('Run all Cron jobs'),
    ];
  }

  public function layout(): iterable
  {
    return [
      Layout::modal('cron_modal', CronEditLayout::class)->async('asyncGetCron'),
      CronListLayout::class,
    ];
  }

  public function asyncGetCron(int $id = 0): iterable
  {
    return [
      'cron' => Cron::loadBy($id) ?: new Cron(),
    ];
  }

  public function saveCron(Request $request): void
  {
    $cron = Cron::loadBy((int)$request->get('id')) ?: new Cron();
    $cron->setActive((bool)$request->get('cron')['active']);
    $cron->setCronKey($request->get('cron')['cron_key']);
    $cron->setPeriod((int)$request->get('cron')['period']);
    $cron->setDescription($request->get('cron')['description']);
    $cron->setName($request->get('cron')['name']);
    $cron->save_mr();
  }

  public function remove(Request $request): void
  {
    $cron = Cron::loadByOrDie((int)$request->get('id'));
    $cron->delete_mr();

    Toast::warning('Cron was removed');
  }

  public function run(Request $request): void
  {
    $id = (int)$request->get('id');

    $jobsList = $id === self::ALL_ACTIVE ? Cron::where('active', true)->get() : [Cron::loadByOrDie($id)];

    $object = new CronBase();
    $object->setLog('Load cron list to start: ' . count($jobsList) . ' jobs');

    foreach ($jobsList as $job) {
      try {
        $object->run($job);
      } catch (Exception $e) {
        $object->setLog($e->getMessage());
      }
    }

    $object->setLog('Finish load list');
  }
}
