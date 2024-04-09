<?php

declare(strict_types=1);

namespace App\Http\Controllers\Travel;

use App\Classes\Travel\TravelClass;
use App\Classes\Validation\TravelValidation;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Travel\Request\TravelDetailsRequest;
use App\Models\Travel;
use App\Services\TravelApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TravelController extends Controller
{
    public function __construct(
        private readonly TravelClass      $travel,
        private readonly TravelValidation $validationClass,
        private readonly TravelApiService $travelApiService,
    ) {}

    public function index(int $travel_id): View
    {
        $out = ['travel_id' => $travel_id];

        return View('account.travel.index', $out);
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

    public function details(TravelDetailsRequest $request): JsonResponse
    {
        $this->validationClass->validateDetails($request);

        return $this->successResult(
            $this->travelApiService->getTravelDetailsResponse($request->getTravelId())
        );
    }

    public function getList(): JsonResponse
    {
        return $this->successResult($this->travel->getConvertedList());
    }
}
