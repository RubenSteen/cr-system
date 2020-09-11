<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Facades\App\Http\Requests\User\UserCreate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class UserController extends AdminBaseController
{
    /**
     * Display a listing of the users.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $users = User::paginate(15)->map(function (User $user) {
            return [
                'id' => $user->id,
                'fullName' => $user->fullName(),
                'active' => $user->active,
                'admin' => $user->admin,
                'created_at' => readable_date_time_string($user->created_at),
            ];
        });

        return Inertia::render('User/Admin/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new question for the specified map.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('User/Admin/Create');
    }

    /**
     * Store a newly created map in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = UserCreate::validateData($request->all());

        User::create($validatedData);

        return redirect()->route('admin.user.index')->with('success', 'User was successfully created!');
    }
}
