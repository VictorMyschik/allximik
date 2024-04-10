<?php

declare(strict_types=1);

namespace App\Http\Controllers\Travel;

use App\Classes\Travel\TravelClass;
use App\Classes\Validation\TravelValidation;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Travel\Request\CreateTravelRequest;
use App\Http\Controllers\Travel\Request\TravelDetailsRequest;
use App\Http\Controllers\Travel\Request\UpdateTravelRequest;
use App\Models\Travel;
use App\Services\Travel\TravelApiService;
use App\Services\Travel\TravelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class TravelController extends Controller
{
    public function __construct(
        private readonly TravelClass      $travel,
        private readonly TravelValidation $validationClass,
        private readonly TravelApiService $travelApiService,
        private readonly TravelService    $travelService,
    )
    {
        $this->middleware('auth', ['except' => ['getList']]);
    }

    public function index(int $travel_id): View
    {
        $out = ['travel_id' => $travel_id];

        return View('account.travel.index', $out);
    }

    public function create(CreateTravelRequest $request): JsonResponse
    {
        $input = $request->validated();
        $input['user_id'] = $request->user()->id;

        $id = $this->travelService->saveTravel(0, $input);

        return $this->successResult(
            $this->travelApiService->getTravelDetailsResponse($id), 201
        );
    }

    public function update(UpdateTravelRequest $request): JsonResponse
    {
        $input = $this->validationClass->validateUpdate($request);
        $this->travelService->saveTravel((int)$input['id'], $input);

        return $this->successResult(
            $this->travelApiService->getTravelDetailsResponse((int)$input['id'])
        );
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
