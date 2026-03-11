<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show employee login form.
     */
    public function showLogin()
    {
        return view('employee.auth.login');
    }

    /**
     * Handle login — users table uses phone + password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'phone'    => $request->phone,
            'password' => $request->password,
        ];

        // Check if account is active (activate = 1)
        $user = \App\Models\User::where('phone', $request->phone)->first();

        if ($user && $user->activate == 2) {
            return back()
                ->withInput($request->only('phone'))
                ->withErrors([
                    'phone' => app()->getLocale() === 'ar'
                        ? 'حسابك موقوف. تواصل مع المدير.'
                        : 'Your account is deactivated. Please contact your admin.',
                ]);
        }

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('employee.dashboard'));
        }

        return back()
            ->withInput($request->only('phone'))
            ->withErrors([
                'phone' => app()->getLocale() === 'ar'
                    ? 'رقم الهاتف أو كلمة المرور غير صحيحة.'
                    : 'The phone number or password is incorrect.',
            ]);
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.showlogin');
    }
}