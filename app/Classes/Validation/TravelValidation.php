<?php

namespace App\Classes\Validation;

use App\Exceptions\Validation\InputMissingException;
use App\Exceptions\Validation\PermissionDeniedException;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelValidation
{
  public function __construct(protected ?User $user) {}

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
      throw new InputMissingException($validator->errors()->first());
    }

    $data = $validator->safe()->only(['name', 'description', 'status', 'country_id', 'visible_kind', 'travel_type_id']);
    $data['user_id'] = $this->user->id();

    return $data;
  }

  public function validateUpdate(Request $request): array
  {
    $validator = Validator::make($request->all(), [
      'id'             => 'required|int|exists:travel,id',
      'name'           => 'string|max:255',
      'description'    => 'string|max:8000',
      'status'         => 'int|in:-1,1,2',
      'country_id'     => 'int|exists:country,id',
      'visible_kind'   => 'int|in:0,1,2',
      'travel_type_id' => 'int|exists:travel_type,id',
    ]);

    if ($validator->fails()) {
      throw new InputMissingException($validator->errors()->first());
    }

    $id = (int)$validator->safe()->only('id')['id'];

    if (!Travel::loadByOrDie($id)->canEdit($this->user)) {
      throw new PermissionDeniedException();
    }

    $data = $validator->safe()->only(['name', 'description', 'status', 'country_id', 'visible_kind', 'travel_type_id']);
    $data['user_id'] = $this->user->id();
    $data['id'] = $id;

    return $data;
  }

  public function validateDelete(Request $request): array
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|int|exists:travel,id',
    ]);

    if ($validator->fails()) {
      throw new InputMissingException($validator->errors()->first());
    }

    $id = (int)$validator->safe()->only('id')['id'];

    if (!Travel::loadByOrDie($id)->canDelete($this->user)) {
      throw new PermissionDeniedException();
    }

    return ['id' => $id];
  }

  public function validateDetails(Request $request): void
  {
    $validator = Validator::make($request->all(), [
      'travel_id' => 'required|int|exists:travel,id',
    ]);

    if ($validator->fails()) {
      throw new InputMissingException($validator->errors()->first());
    }

    $id = (int)$validator->safe()->only('travel_id')['travel_id'];

    if (!Travel::loadByOrDie($id)->canView($this->user)) {
      throw new PermissionDeniedException();
    }
  }
}
