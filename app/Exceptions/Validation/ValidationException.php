<?php

declare(strict_types=1);

namespace App\Exceptions\Validation;

use Exception;

class ValidationException extends Exception
{
  public function getField(): ?string
  {
    return null;
  }

  public function getSection(): ?string
  {
    return null;
  }

  public function getTitle(): ?string
  {
    return null;
  }
}
