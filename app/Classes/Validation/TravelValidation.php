<?php

namespace App\Classes\Validation;

use App\Exceptions\Validation\InputMissingException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelValidation
{
  public function __construct(private ?User $user)
  {
  }

  /**
   * @throws InputMissingException
   */
  public function validateCreate(Request $request): array
  {
    $validator = Validator::make($request->all(), [
      'name'           => 'required|string|max:255',
      'description'    => 'string|max:8000',
      'status'         => 'required|int|in:-1,1,2',
      'country_id'     => 'required|int|exists:country,id',
      'visible_kind'   => 'required|int|in:0,1,2',
      'travel_type_id' => 'required|int|exists:travel_type,id',
    ]);

    if ($validator->fails()) {
      throw new InputMissingException();
    }

    $data = $validator->safe()->only(['name', 'description', 'status', 'country_id', 'visible_kind', 'travel_type_id']);
    $data['user_id'] = $this->user->id();

    return $data;
  }
}
