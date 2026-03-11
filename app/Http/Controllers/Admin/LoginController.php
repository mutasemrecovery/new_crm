<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show_login_view()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt(
            ['username' => $request->username, 'password' => $request->password],
        )) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => app()->getLocale() === 'ar'
                    ? 'اسم المستخدم أو كلمة المرور غير صحيحة.'
                    : 'The username or password is incorrect.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.showlogin');
    }

    public function editlogin($id)
    {
        $data = Admin::findOrFail($id);
        return view('admin.auth.edit', compact('data'));
    }

    public function updatelogin(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);
        try {
            $admin->username = $request->username;
            $admin->password = Hash::make($request->password);
            if ($admin->save()) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.showlogin')
                    ->with('success', 'Profile updated. Please login again.');
            }
            return back()->with('error', 'Something went wrong.');
        } catch (\Exception $ex) {
            return back()->with('error', 'Error: ' . $ex->getMessage())->withInput();
        }
    }
}