<?php

declare(strict_types=1);

namespace App\Forms\Account\Travel;

use App\Forms\FormBase\FormBase;
use App\Models\Travel;
use App\Services\References\CountryService;
use App\Services\Travel\TravelService;

class NameDescriptionTravelForm extends FormBase
{
    public string $size = 'w-50';

    public function __construct(
        private readonly TravelService  $service,
        private readonly CountryService $countryService
    ) {}

    protected function builderForm(array &$form, array $args): void
    {
        $travel = Travel::loadBy((int)$args['travel_id']) ?: new Travel();

        $form['#title'] = __('mr-t.account_form_travel_edit');

        $form['status'] = array(
            '#type'          => 'select',
            '#title'         => __('mr-t.account_form_status'),
            '#default_value' => $travel->id() ? $travel->getStatus() : Travel::STATUS_DRAFT,
            '#options'       => Travel::getStatusList(),
        );

        $form['name'] = array(
            '#type'  => 'textfield',
            '#title' => __('mr-t.account_form_name'),
            '#class' => ['mr-border-radius-5'],
            '#value' => $travel->getName(),
        );

        $form['country_id'] = array(
            '#type'          => 'select',
            '#title'         => __('mr-t.account_form_country'),
            '#required'      => true,
            '#default_value' => $travel->id() ? $travel->getCountry()->id() : 0,
            '#options'       => [0 => 'не выбрано'] + $this->countryService->getSelectList()
        );
    }

    protected function validateForm(array $routeParameters): void
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
