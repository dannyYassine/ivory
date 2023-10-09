<?php

namespace App\Controllers;

use App\Models\User;
use OpenSwoole\Http\Request;

class GetUsersController {
    public function execute(Request $request) {
        return User::where($request->get)->get();
    }
}