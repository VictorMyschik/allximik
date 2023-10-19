<?php

namespace API\Travel;

use App\Classes\Travel\Image\ImageClass;
use App\Models\TravelImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class CRUDTravelImageTest extends BaseTest
{
  public function testUploadImage()
  {
    $email = self::randomEmail();
    $password = self::randomString(20);

    $user = $this->createUser($email, $password);
    $travel = $this->createTravel($user->id());

    // Upload image
    $fileUpload = new UploadedFile(__DIR__ . '/test_image.jpg', 'test_image.jpg', 'image/jpeg', null, true);

    $properties['image_type'] = TravelImage::KIND_MAIN;
    $properties['description'] = 'description';
    $properties['group'] = 'group';

    $imageClass = new ImageClass($user);

    $result = $imageClass->uploadImage($travel, $fileUpload, $properties);

    $this->assertNotEmpty($result);
    $image = TravelImage::loadByOrDie($result['id']);

    self::assertTrue(Storage::exists($travel->getDirNameForImages() . '/' . $result['name']));

    // Update description
    $args = [
      'group'       => self::randomString(),
      'description' => 'new description',
      'image_type'  => array_rand(TravelImage::getKindList()),
    ];

    $imageClass->setImageProperties($image, $args);
    $image->save_mr();

    // Asserts
    self::assertEquals($args['group'], $image->getGroup());
    self::assertEquals($args['description'], $image->getDescription());
    self::assertEquals($args['image_type'], $image->getKind());

    $image = TravelImage::loadByOrDie($result['id']);
    $image->delete_mr();

    self::assertFalse(Storage::exists($travel->getDirNameForImages() . '/' . $result['name']));

    // Delete
    DB::table($user->getTable())->where('id', $user->id())->delete();
    self::assertNull($user->fresh());
    self::assertNull($travel->fresh());
  }
}
