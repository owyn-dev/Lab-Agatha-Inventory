<?php

declare(strict_types=1);

namespace App\Http\Controllers\ManageAccess;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

final class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),

            new Middleware('permission:view_user', only: ['index']),
            new Middleware('permission:show_user', only: ['show']),
            new Middleware('permission:profile_user', only: ['profile']),
            new Middleware('permission:create_user', only: ['create']),
            new Middleware('permission:edit_user', only: ['edit']),
        ];
    }

    public function index()
    {
        $title = 'User List';

        $text_subtitle = 'User List is used to display, manage, and monitor user data in the system';

        return view('manage-access.user-index', compact('title', 'text_subtitle'));
    }

    public function create()
    {
        $title = 'Create User';

        $text_subtitle = 'This page displays form create user data.';

        return view('manage-access.user-create', compact('title', 'text_subtitle'));
    }

    public function show(User $user)
    {
        $title = 'Show User';

        $text_subtitle = 'This page displays detail of user data.';

        $user->load('roles');

        return view('manage-access.user-show', compact('title', 'text_subtitle', 'user'));
    }

    public function edit(User $user)
    {
        $title = 'Edit User';

        $text_subtitle = 'This page displays form edit user data.';

        return view('manage-access.user-edit', compact('title', 'text_subtitle', 'user'));
    }

    public function profile(User $user)
    {
        if (Auth::id() !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $title = 'Account Profile';

        $text_subtitle = 'This page displays your account profile data.';

        return view('manage-access.user-edit-profile', compact('title', 'text_subtitle', 'user'));
    }
}
