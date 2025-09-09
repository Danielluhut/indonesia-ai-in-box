<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ambil input login (username atau email)
        $loginInput = $request->input('login');

        // deteksi apakah input berupa email atau username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // cari user berdasarkan email atau username
        $user = User::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        if (!$user) {
            return back()->with('error', ucfirst($fieldType) . ' not found.')->withInput();
        }

        // cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Wrong Password.')->withInput();
        }

        // login user
        Auth::login($user);
        $request->session()->regenerate();

        // redirect sesuai role
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect('/admin-dashboard'),
            'maintenance' => redirect('/maintenance-dashboard'),
            default => redirect('/user-dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
