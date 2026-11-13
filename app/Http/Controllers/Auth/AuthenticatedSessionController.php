<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuditTrailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi dan arahkan ke dashboard peran masing-masing
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('kepsek')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->hasRole('guru')) {
            return redirect()->intended(route('guru.dashboard'));
        }

        if ($user->hasRole('wali')) {
            // Set session anak pertama aktif secara default jika belum dipilih
            if ($user->wali && $user->wali->siswa()->exists()) {
                $anakPertama = $user->wali->siswa()->first();
                session(['anak_aktif_id' => $anakPertama->id]);
            }
            return redirect()->intended(route('wali.dashboard'));
        }

        return redirect()->intended('/');
    }

    /**
     * Logout pengguna
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            AuditTrailService::catat('LOGOUT', 'users', Auth::id());
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
