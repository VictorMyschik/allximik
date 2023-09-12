<?php

namespace App\Classes;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageClass
{
  public function uploadImage(object $object, UploadedFile $image, int $type)
  {
    $fileName = self::getImageNameByType($image->getMimeType());
    $pathName = self::getFilesFolderModelID($object);

    // Save to local storage
    $savedLocalFileWithRelativePath = $image->storeAs($pathName, $fileName, ['disk' => 'local']);

    // Move to cloud
    if (config('filesystems.default') !== 'local') {
      Storage::put($savedLocalFileWithRelativePath, Storage::disk('local')->get($savedLocalFileWithRelativePath));
      Storage::disk('local')->delete($savedLocalFileWithRelativePath);
    }

    return $object->addImage($fileName, $type);
  }

  public static function getImageNameByType(?string $type): string
  {
    return time() . '_' . uniqid('php') . self::getImageExtensionByType($type);
  }

  public static function getImageExtensionByType(?string $type): ?string
  {
    return match ($type) {
      'image/pjpeg', 'image/jpeg' => '.jpg',
      'image/x-png', 'image/png' => '.png',
      'image/gif' => '.gif',
      default => '',
    };
  }

  public static function getFilesFolderModelID(object $object): string
  {
    $groupBy = 50;
    $part = (int)($object->id() / $groupBy);

    if ($object->id() % $groupBy > 0) {
      $part++;
    }

    return $object->getDirNameForImages() . '/' . ($part * $groupBy);
  }
}
