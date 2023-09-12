<?php

declare(strict_types=1);

namespace App\Exceptions\Validation;

class WrongImageMimeTypeException extends ValidationException
{
  protected $message = 'Uploaded file is not a valid image';
}
