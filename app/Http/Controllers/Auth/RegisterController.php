<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:guru,siswa,sarpras', // Restrict to safe roles for public registration
            'terms' => 'required|accepted',
        ]);

        // Get role for assignment
        $role = \Spatie\Permission\Models\Role::where('name', $request->role)->first();
        if (!$role) {
            return back()->withErrors(['role' => 'Role tidak valid.'])->withInput();
        }

        // Create user without email verification
        // Note: Public registration restricts to safe roles (guru, siswa, sarpras)
        // Custom roles can only be assigned by admin via /admin/user-management
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Not verified yet
            'is_verified_by_admin' => false, // Not verified by admin
        ]);

        // Assign role using Spatie Permission
        $user->syncRoles([$role]);

        // Generate email verification token
        $user->generateEmailVerificationToken();

        // Send email verification notification
        $user->notify(new EmailVerificationNotification($user));

        // Log user in (they will be redirected to verification notice)
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Akun berhasil dibuat! Silakan verifikasi email Anda untuk melanjutkan.');
    }

    /**
     * Resend verification email for registered users.
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email sudah terverifikasi.');
        }

        if ($user->isVerifiedByAdmin()) {
            return back()->with('info', 'Akun ini sudah diverifikasi oleh admin.');
        }

        // Generate new token and send notification
        $user->generateEmailVerificationToken();
        $user->notify(new EmailVerificationNotification($user));

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda.');
    }
}
