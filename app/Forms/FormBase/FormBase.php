<?php

namespace App\Forms\FormBase;

use App\Helpers\System\MtFloatHelper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class FormBase extends Controller
{
    protected array $v = [];
    protected array $errors = [];
    protected string $size = 'w-50';

    public function processForm(): Factory|View|array|Application|null
    {
        $method = request()->getMethod();

        if (in_array($method, ['GET', 'POST'])) {
            return $this->getFormBuilder();
        } elseif ('PUT' === $method) {
            $routeParameters = Route::getFacadeRoot()->current()->parameters();
            $this->validateFormBase($routeParameters);

            try {
                $this->submitForm($routeParameters);

                return $this->v;
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
                $this->returnError();
            }
        }

        return null;
    }

    public function getFormBuilder(): View|Factory|array|Application
    {
        $routeParameters = Route::getFacadeRoot()->current()->parameters();

        $form = array(
            '#title' => '',
            '#size'  => $this->size ?? 'w-50',
            '#url'   => ''
        );

        $this->builderForm($form, $routeParameters);

        if (!isset($form['#btn_info'])) {
            $form['#url'] = request()->url();
        }

        $formDisplay = View('form.base_form.form_templates')->with(['form' => $form])->toHtml();

        if (request()->getMethod() === 'POST') {
            return array(
                'html'      => $formDisplay,
                'form_data' => $form,
            );
        } else { // GET запрос для дебага
            return View('form.base_form.form_render')->with([
                'form'      => $formDisplay,
                'form_data' => $form,
            ]);
        }
    }

    #region Validation

    /**
     * Базовая валидация на заполненность и тип данных
     */
    protected function validateFormBase(array $routeParameters = []): void
    {
        $fields = $this->getFormBuilder()['form_data'];

        // Get original form data.
        foreach ($fields as $fieldName => $parameters) {
            if (str_starts_with($fieldName, '#') || !isset($parameters['#type'])) {
                unset($fields[$fieldName]);
            }
        }

        foreach ($fields as $fieldName => $parameters) {
            // value from input
            $value = request()->get($fieldName, null);

            $title = $parameters['#title'] ?? '';

            if (array_key_exists('#required', $parameters) && true === $parameters['#required']) {
                if (empty(trim($value))) {
                    $this->errors[$fieldName] = 'Поле "' . $title . '" должно быть заполнено';
                }
            }

            $this->returnError();

            if ($value) {
                // Type numeric
                if ('number' === $parameters['#type']) {
                    if (MtFloatHelper::canConvert($value)) {
                        $value = MtFloatHelper::toFloat($value);
                    } else {
                        $this->errors[$fieldName] = 'Поле "' . $parameters['#title'] . '" имеет не верный формат';
                        $this->returnError();
                    }
                } elseif ('textfield' === $parameters['#type']) {
                    if (isset($parameters['#max']) && (int)$parameters['#max'] > strlen($value)) {
                        $this->errors[$fieldName] = 'Поле "' . $parameters['#title'] . '" ограничено ' . $parameters['#max'] . ' символами';
                    }
                }
            }

            $this->v[$fieldName] = $value;
        }

        $this->returnError();

        if (method_exists($this, 'validateForm')) {
            $this->validateForm($routeParameters);

            $this->returnError();
        }
    }

    private function returnError(): void
    {
        if (count($this->errors)) {
            Log::alert('Wrong set form', $this->errors);
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, json_encode($this->errors));
        }
    }
    #endregion
}
