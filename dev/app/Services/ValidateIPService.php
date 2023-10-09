<?php

namespace App\Services;

class ValidateIPService {
    public function validate(string $ip): string
    {
        return in_array($ip, 
        ['173.177.93.35', '99.240.221.86', '3.134.238.10', '3.129.111.220', '52.15.118.168']
    );
    }
}