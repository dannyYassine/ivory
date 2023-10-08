<?php

namespace Ivory;

use Exception;

class UnauthorizedException extends Exception
{
    function __construct(string $message)
    {
        parent::__construct($message, 401);
    }
}