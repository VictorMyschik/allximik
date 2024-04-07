<?php

declare(strict_types=1);

namespace App\Forms\Account\Travel;

use App\Forms\FormBase\FormBase;
use App\Models\Travel;
use App\Services\Travel\TravelService;

class NameDescriptionTravelForm extends FormBase
{
  public string $size = 'w-75';

  public function __construct(private readonly TravelService $service) {}

  protected function builderForm(array &$form, array $args): void
  {
    $travel = Travel::loadBy((int)$args['travel_id']) ?: new Travel();

    $form['#title'] = 'Редактирование путешествия';

    $form['name'] = array(
      '#type'  => 'textfield',
      '#title' => 'Наименование путешествия',
      '#class' => ['mr-border-radius-5'],
      '#value' => $travel->getName()
    );
  }

  protected function validateForm(): void
  {
    if (!$this->v['name']) {
      $this->errors['name'] = 'Наименование не указано';
    }
  }

  protected function submitForm(array $routeParameters): void
  {
    $this->service->updateTravel((int)$routeParameters['travel_id'], $this->v);
  }
}
