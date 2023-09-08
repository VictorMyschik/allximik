<?php

namespace App\Exceptions;

use Exception;

class APIAuthException extends Exception
{
  protected $message = 'Unauthorized';
}
