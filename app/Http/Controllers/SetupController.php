<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\User\UserCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (User::all()->count() > 0) {
            abort(500);
        }

        $this->middleware('guest');
    }

    /**
     * Show the application's setup form.
     *
     * @return \Inertia\Response
     */
    public function show()
    {
        return Inertia::render('Setup/Show');
    }

    /**
     * Does the needed action so the application is useable
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function persist(Request $request)
    {
        $userCreateInstance = new UserCreate;

        $rules = Arr::only($userCreateInstance->rules(), ['username', 'first_name', 'last_name', 'email']);

        $rules = array_merge($rules, $userCreateInstance->passwordRules());

        $validatedData = Validator::make($request->all(), $rules, $userCreateInstance->messages(), $userCreateInstance->attributes())->validate();

        $user = User::create(array_merge($validatedData, [
            'active' => true,
            'admin' => true,
        ]));

        Auth::loginUsingId($user->id);

        return redirect()->route('login');
    }
}
