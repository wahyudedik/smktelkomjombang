<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || $user->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.verify-email', compact('user'));
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'hash' => 'required|string',
            'token' => 'required|string',
        ]);

        $user = User::findOrFail($request->id);

        // Check if hash matches
        if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid.');
        }

        // Check if user is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email sudah terverifikasi.');
        }

        // Verify with token
        if ($user->verifyEmailWithToken($request->token)) {
            return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
        }

        return redirect()->route('login')->with('error', 'Token verifikasi tidak valid atau sudah expired.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || $user->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }

        // Generate new token and send notification
        $user->generateEmailVerificationToken();
        $user->notify(new EmailVerificationNotification($user));

        return back()->with('success', 'Link verifikasi baru telah dikirim ke email Anda.');
    }

    /**
     * Verify email for registration process.
     */
    public function verifyRegistration(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'hash' => 'required|string',
            'token' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->id);

        // Check if hash matches
        if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
            return view('auth.verification-failed')->with('error', 'Link verifikasi tidak valid.');
        }

        // Check if user is already verified
        if ($user->hasVerifiedEmail()) {
            return view('auth.verification-success')->with('message', 'Email sudah terverifikasi.');
        }

        // Verify with token
        if ($user->verifyEmailWithToken($request->token)) {
            return view('auth.verification-success')->with('message', 'Email berhasil diverifikasi! Silakan login.');
        }

        return view('auth.verification-failed')->with('error', 'Token verifikasi tidak valid atau sudah expired.');
    }

    /**
     * Resend verification email for unauthenticated users.
     */
    public function resendForGuest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        /** @var \App\Models\User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->hasVerifiedEmail()) {
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
