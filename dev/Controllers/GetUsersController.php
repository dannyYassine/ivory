<?php

namespace Dev\Controllers;

use Dev\Models\User;
use OpenSwoole\Http\Request;

class GetUsersController {
    public function execute(Request $request) {
        return User::where($request->get)->get();
    }
}