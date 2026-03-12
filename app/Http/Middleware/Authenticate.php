<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Admin routes → admin login
        if ($request->is('*/admin') || $request->is('*/admin/*') ||
            $request->is('admin')   || $request->is('admin/*')) {
            return route('admin.login');
        }

        // Employee routes (default) → employee login
        return route('employee.showlogin');
    }
}
