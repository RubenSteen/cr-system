<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

/*
 * Allow logged in users to change their own password.
*/

class ChangePasswordController extends Controller
{
    /**
     * Display the change password form.
     *
     * @return \Inertia\Response
     */
    public function showForm()
    {
        return Inertia::render('Auth/ChangePassword');
    }

    /**
     * Update the password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->validatePassword($request);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('home')->with('success', 'Your password has been changed!');
    }

    /**
     * Validate the user password change request.
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validatePassword($request)
    {
        $request->validate([
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
        ]);
    }
}
