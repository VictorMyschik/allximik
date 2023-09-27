<?php

namespace App\Http\Controllers\Travel;

use App\Classes\Travel\TravelClass;
use App\Classes\Validation\TravelValidation;
use App\Exceptions\Validation\InputMissingException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TravelController extends Controller
{
  public function __construct(private readonly TravelClass $travel, private readonly TravelValidation $validationClass)
  {
    $this->middleware('auth.jwt', ['except' => ['getList']]);
  }

  /**
   * @throws InputMissingException
   */
  public function create(Request $request): JsonResponse
  {
    $input = $this->validationClass->validateCreate($request);
    $travel = $this->travel->createTravel($input);
    $response = $this->travel->getTravelData($travel);

    return $this->successResult($response, 201);
  }

  public function getList(): JsonResponse
  {
    return $this->successResult($this->travel->getConvertedList());
  }
}
