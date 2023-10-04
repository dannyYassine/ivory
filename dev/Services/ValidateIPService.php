<?php

namespace Dev\Services;

class ValidateIPService {
    public function validate(string $ip): string
    {
        return in_array($ip, ['173.177.93.35']);
    }
}