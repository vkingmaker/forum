<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;

class RegisterConfirmationController extends Controller
{

    public function index()
    {
        $user = User::where('confirmation_token', request('token'))

            ->firstOrFail()

            ->confirm();


        return redirect('/threads')

            ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    }
}
