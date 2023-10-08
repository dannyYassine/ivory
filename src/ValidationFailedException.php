<?php

namespace Ivory;

use Exception;

class ValidationFailedException extends Exception
{
    function __construct(string $message)
    {
        parent::__construct($message, 400);
    }
}