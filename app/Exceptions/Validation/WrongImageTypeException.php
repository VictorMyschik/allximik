<?php

declare(strict_types=1);

namespace App\Exceptions\Validation;

class WrongImageTypeException extends ValidationException
{
  protected $message = 'Type must be equal to 1 (main) or 2 (list)';
}
