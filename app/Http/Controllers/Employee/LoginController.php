<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('employee.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if account is active before attempting
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

        if (Auth::guard('web')->attempt(
            ['phone' => $request->phone, 'password' => $request->password],
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            // نفس أسلوب الـ admin — intended بيحترم الـ locale prefix تلقائياً
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

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.showlogin');
    }
}