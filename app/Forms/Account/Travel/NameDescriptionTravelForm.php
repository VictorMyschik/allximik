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
        $travel = $this->service->getTravelById((int)$args['travel_id']);

        $form['#title'] = __('mr-t.account_form_travel_edit');

        $form['status'] = array(
            '#type'          => 'select',
            '#title'         => __('mr-t.account_form_status'),
            '#default_value' => $travel ? $travel->getStatus() : Travel::STATUS_DRAFT,
            '#options'       => Travel::getStatusList(),
        );

        $form['title'] = array(
            '#type'  => 'textfield',
            '#title' => __('mr-t.Title'),
            '#class' => ['mr-border-radius-5'],
            '#value' => $travel?->getTitle(),
        );

        $form['country_id'] = array(
            '#type'          => 'select',
            '#title'         => __('mr-t.country'),
            '#required'      => true,
            '#default_value' => $travel ? $travel->getCountry()->id() : 0,
            '#options'       => [0 => 'не выбрано'] + $this->countryService->getSelectList()
        );
    }

    protected function validateForm(array $routeParameters): void
    {
        if (!$this->v['title']) {
            $this->errors['title'] = 'Наименование не указано';
        }
    }

    protected function submitForm(array $routeParameters): void
    {
        $this->service->saveTravel((int)$routeParameters['travel_id'], $this->v);
    }
}
