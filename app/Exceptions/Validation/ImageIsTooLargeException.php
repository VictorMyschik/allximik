<?php

declare(strict_types=1);

namespace App\Exceptions\Validation;

class ImageIsTooLargeException extends ValidationException
{
  protected $message = 'Image file size too large';
}
