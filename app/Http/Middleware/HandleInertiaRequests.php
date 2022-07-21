<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request)
    {
        return parent::version($request);
    }

    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'user' => function () use ($request) {
                if (!$request->user()) {
                    return;
                }
            },
            'locale' => app()->getLocale()
        ]);
    }
}