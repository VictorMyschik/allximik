<?php

namespace App\Classes\Travel\Image;

use App\Helpers\System\MrDateTime;
use App\Models\Travel;
use App\Models\TravelImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

readonly class ImageClass
{
  public function __construct(private ?User $user)
  {
  }

  public function uploadImage(Travel $travel, UploadedFile $image, array $properties): array
  {
    // check is double file
    $travelImageProperties = $this->getExistsOrSaveNewImage($travel, $image);

    $travelImageProperties['size'] = $image->getSize();
    $travelImageProperties['original_name'] = $image->getClientOriginalName();
    $travelImageProperties['travel_id'] = $travel->id();
    $travelImageProperties['image_type'] = $properties['image_type'];
    $travelImageProperties['description'] = $properties['description'] ?? null; // optionally
    $travelImageProperties['group'] = $properties['group'] ?? null; // optionally

    return $this->addNewImage($travelImageProperties);
  }

  private function getExistsOrSaveNewImage(Travel $travel, UploadedFile $image): array
  {
    $hash = $this->getFileHash($image);
    $existsImage = DB::table(TravelImage::getTableName())->where('hash', $hash)->first();

    if ($existsImage) {
      return ['name' => $existsImage->name, 'hash' => $existsImage->hash];
    }

    // Save to local storage
    $name = self::getImageNameByType($image->getMimeType());
    $savedLocalFileWithRelativePath = $image->storeAs($travel->getDirNameForImages(), $name, ['disk' => 'local']);

    // Move to cloud. See .env FILESYSTEM_DISK variable
    if (config('filesystems.default') !== 'local') {
      Storage::put($savedLocalFileWithRelativePath, Storage::disk('local')->get($savedLocalFileWithRelativePath));
      Storage::disk('local')->delete($savedLocalFileWithRelativePath);
    }

    return ['name' => $name, 'hash' => $hash];
  }

  private function getFileHash(UploadedFile $file): string
  {
    return md5_file($file->getRealPath());
  }

  public static function getImageNameByType(?string $type): string
  {
    return md5(microtime()) . self::getImageExtensionByType($type);
  }

  public static function getImageExtensionByType(?string $type): ?string
  {
    return match ($type) {
      'image/pjpeg', 'image/jpeg' => '.jpg',
      'image/x-png', 'image/png' => '.png',
      'image/gif' => '.gif',
      'image/svg+xml' => '.svg',
      default => '',
    };
  }

  private function addNewImage(array $properties): array
  {
    $saveImage = new TravelImage();

    $saveImage->setTravelID((int)$properties['travel_id']);
    $saveImage->setSize($properties['size']);
    $saveImage->setHash($properties['hash']);
    $saveImage->setName($properties['name']);
    $saveImage->setUserID($this->user->id());
    $saveImage->setOriginalName($properties['original_name']);

    $this->setImageProperties($saveImage, $properties);

    $id = $saveImage->save_mr();

    $properties['id'] = $id;

    return $properties;
  }

  public function setImageProperties(TravelImage &$image, array $properties): void
  {
    if (array_key_exists('description', $properties)) {
      $image->setDescription($properties['description']);
    }

    if (array_key_exists('group', $properties)) {
      $image->setGroup($properties['group']);
    }

    if (array_key_exists('image_type', $properties)) {
      $image->setKind($properties['image_type']);
    }
  }

  public function deleteImage(TravelImage $image): void
  {
    $images = DB::table(TravelImage::getTableName())->where('name', $image->getName())->count();

    if ($images === 1) {
      $this->deleteImageFromStorage($image);
    }

    $image->delete_mr();
  }

  private function deleteImageFromStorage(TravelImage $image): void
  {
    $imagePath = $image->getTravel()->getDirNameForImages() . '/' . $image->getName();
    $imagePath = str_replace('storage/', '', $imagePath);
    Storage::delete($imagePath);
  }

  public function getTravelImageData(TravelImage $image): array
  {
    return [
      'id'          => $image->id(),
      'name'        => $image->getName(),
      'url'         => $this->getPublicUrl($image),
      'description' => $image->getDescription(),
      'group'       => $image->getGroup(),
      'kind'        => $image->getKindName(),
      'sort'        => $image->getSort(),
      'created'     => $image->getCreatedObject()->format(MrDateTime::SHORT_DATE),
    ];
  }

  private function getPublicUrl(TravelImage $image): string
  {
    return route('api.travel.image.get', ['travel_id' => $image->getTravel()->id(), 'image_name' => $image->getName()]);
  }
}
