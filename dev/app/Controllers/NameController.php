<?php

namespace App\Controllers;

use App\Models\User;
use OpenSwoole\Http\Request;

class NameController {
    public function execute(Request $request) {
        $name = $request->get['name'];
        (new User(['name' => $name]))->save();

        return User::where('name', $name)->first();
    }
}