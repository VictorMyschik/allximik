<?php

declare(strict_types=1);

namespace App\Http\Controllers\Travel\Request;

use App\Exceptions\Validation\InputMissingException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'             => 'required|int|exists:travels,id',
            'title'          => 'string|max:255',
            'description'    => 'string|max:8000',
            'status'         => 'int|in:-1,1,2',
            'country_id'     => 'int|exists:country,id',
            'visible_kind'   => 'int|in:0,1,2',
            'travel_type_id' => 'int|exists:travel_type,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new InputMissingException($validator->errors()->first());
    }
}
