<?php

namespace App\Exceptions;

class EmptyFileException extends \Exception
{
    protected $message = 'File must not be empty.';
}
