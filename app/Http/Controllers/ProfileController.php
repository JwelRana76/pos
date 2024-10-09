<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    function home(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select(['id', 'name', 'email', 'created_at']);
            return DataTables::of($data)
                ->addColumn('created_at', function ($item) {
                    return $item->created_at->format('d-M-Y');
                })
                ->addColumn('amount', function ($item) {
                    return 10;
                })
                ->make(true);
        }
        $columns = [
            ['name' => 'name', 'data' => 'name'],
            ['name' => 'email', 'data' => 'email'],
            ['name' => 'created_at', 'data' => 'created_at'],
            ['name' => 'amount', 'data' => 'amount'],
        ];
        return view('dashboard', compact('columns'));
    }
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select(['id', 'name', 'email', 'created_at'])->get();
            return DataTables::of($data)->make(true);
        }
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
