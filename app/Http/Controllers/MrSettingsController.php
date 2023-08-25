<?php

namespace App\Http\Controllers;

use App\Classes\Repository\MrRepositoryBaseClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Контроллер настроек платформы
 */
class MrSettingsController extends Controller
{
  public function getSettings(Request $request): JsonResponse
  {
    $key = $request->input('key');

    $result = match ($key) {
      'allowed_upload_formats' => ['allowed_upload_formats' => MrRepositoryBaseClass::ALLOWED_FILE_EXTENSIONS],
      default => ['error' => 'unknown key'],
    };

    return response()->json($result);
  }
}
