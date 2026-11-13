<?php

namespace App\Http\Requests\Auth;

use App\Services\AuditTrailService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login'    => ['required', 'string'], // Bisa username, NIP, NISN, atau email
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Memproses otentikasi login dengan proteksi brute force rate limiter
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = $this->input('login');
        $fieldType  = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'Username/Email atau kata sandi yang Anda masukkan salah.',
            ]);
        }

        // Cek status aktif user
        $user = Auth::user();
        if (! $user->is_aktif) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => 'Akun Anda dinonaktifkan. Silakan hubungi pihak Tata Usaha sekolah.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Catat jejak login di audit trail
        AuditTrailService::catat('LOGIN', 'users', $user->id, null, ['ip' => $this->ip()]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
    }
}
