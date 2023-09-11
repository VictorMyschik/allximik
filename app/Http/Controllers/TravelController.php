<?php

namespace App\Http\Controllers;

use App\Classes\Travel\TravelClass;
use Illuminate\Http\JsonResponse;

class TravelController extends Controller
{
  public function __construct(private TravelClass $travel)
  {
  }

  public function getList(): JsonResponse
  {
    return $this->successResult($this->travel->getConvertedList());
  }
}
