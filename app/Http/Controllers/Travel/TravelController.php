<?php

namespace App\Http\Controllers\Travel;

use App\Classes\Travel\TravelClass;
use App\Classes\Validation\TravelValidation;
use App\Exceptions\Validation\InputMissingException;
use App\Exceptions\Validation\PermissionDeniedException;
use App\Http\Controllers\Controller;
use App\Models\Travel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TravelController extends Controller
{
  public function __construct(private readonly TravelClass $travel, private readonly TravelValidation $validationClass)
  {
    $this->middleware('auth.jwt', ['except' => ['getList']]);
  }

  public function create(Request $request): JsonResponse
  {
    $input = $this->validationClass->validateCreate($request);
    $travel = $this->travel->createTravel($input);
    $response = $this->travel->getTravelData($travel);

    return $this->successResult($response, 201);
  }

  public function update(Request $request): JsonResponse
  {
    $input = $this->validationClass->validateUpdate($request);
    $travel = $this->travel->updateTravel($input);
    $response = $this->travel->getTravelData($travel);

    return $this->successResult($response);
  }

  public function delete(Request $request): JsonResponse
  {
    $input = $this->validationClass->validateDelete($request);

    $travel = Travel::loadBy($input['id']);
    $travel->delete_mr();

    return $this->successResult();
  }

  public function details(Request $request): JsonResponse
  {
    $travel = Travel::loadBy((int)$request->get('id'));

    if (!$travel) {
      throw new InputMissingException('Travel not found');
    }

    if (!$travel->canView()) {
      throw new PermissionDeniedException();
    }

    return $this->successResult($this->travel->getTravelData($travel));
  }

  public function getList(): JsonResponse
  {
    return $this->successResult($this->travel->getConvertedList());
  }
}
