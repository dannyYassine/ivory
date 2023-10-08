<?php

namespace Ivory;

use Exception;

class ForbiddenException extends Exception
{
    function __construct(string $message)
    {
        parent::__construct($message, 403);
    }
}