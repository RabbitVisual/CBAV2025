<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of public user profiles.
     *
     * This method fetches all active users with their profiles
     * to be displayed in a public member directory.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with('profile')->active()->paginate(20);
        return view('member.users.index', compact('users'));
    }

    /**
     * Display the specified user profile.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Ensure we don't show inactive user profiles via direct URL access
        if (!$user->ativo) {
            abort(404);
        }

        $user->load('profile', 'cargos', 'roles');
        return view('member.users.show', compact('user'));
    }
}