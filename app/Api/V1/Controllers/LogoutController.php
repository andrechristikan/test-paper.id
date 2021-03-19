<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Auth;

class LogoutController extends Controller
{
    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard()->logout();

        return response()
            ->json([
                'status' => 'ok',
                'message' => 'Successfully logged out'
            ]);
    }
}
