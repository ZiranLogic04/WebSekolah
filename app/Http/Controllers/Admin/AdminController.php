<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function dashboard(Request $request)
    {
        return Inertia::render('Admin/Dashboard', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function projects(Request $request)
    {
        return Inertia::render('Admin/Projects', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function templates(Request $request)
    {
        return Inertia::render('Admin/Templates', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function services(Request $request)
    {
        return Inertia::render('Admin/Services', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function estimates(Request $request)
    {
        return Inertia::render('Admin/Estimates', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function about(Request $request)
    {
        return Inertia::render('Admin/About', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function contact(Request $request)
    {
        return Inertia::render('Admin/Contact', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }

    public function users(Request $request)
    {
        return Inertia::render('Admin/Users', [
            'auth' => [
                'user' => $request->user() ? [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null
            ]
        ]);
    }
}