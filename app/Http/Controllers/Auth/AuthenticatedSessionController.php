<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            // Cek apakah email ada di database
            $userExists = User::where('email', $request->email)->exists();

            if (!$userExists) {
                // Email tidak ditemukan
                return back()->withInput($request->only('email', 'remember'))->with('login_error', 'Email yang Anda masukkan tidak sesuai');
            } else {
                // Email ada, berarti password salah
                return back()->withInput($request->only('email', 'remember'))->with('login_error', 'Password yang Anda masukkan tidak sesuai');
            }
        }

        $request->session()->regenerate();

        $redirectUrl = redirect()->intended(route('dashboard', absolute: false))->getTargetUrl();

        return view('auth.login', [
            'login_success' => true,
            'redirect_url' => $redirectUrl
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda berhasil keluar dari sistem.');
    }
}
