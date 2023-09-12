<?php

namespace App\Classes;

use App\Exceptions\Validation\ImageIsTooLargeException;
use App\Helpers\System\MrBaseHelper;
use App\Models\Settings;
use Illuminate\Http\UploadedFile;

class ValidationClass
{
  private static function validateUploadedImageSize(UploadedFile $uploadedFile): void
  {
    // Check size
    $configMaxFileSize = Settings::loadMaxFileSize();
    $configMaxFileSize = (int) MrBaseHelper::strToBytes((string) $configMaxFileSize);
    $maxAllowedInINI = MrBaseHelper::getMaxUploadSize();
    $currentImageSize = MrBaseHelper::strToBytes($uploadedFile->getSize());

    if ($configMaxFileSize && $currentImageSize < $configMaxFileSize) {
      return;
    } elseif ($configMaxFileSize === 0 && $currentImageSize < $maxAllowedInINI) {
      return;
    }

    throw new ImageIsTooLargeException();
  }
}
