<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->role && $user->role->nama_role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role && $user->role->nama_role === 'operator') {
            return redirect()->route('operator.dashboard');
        }

        return redirect()->intended(config('fortify.home'));
    }
}
