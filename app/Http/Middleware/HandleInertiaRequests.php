<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
                'print_data' => fn () => $request->session()->get('print_data'),
                'popup_data' => fn () => $request->session()->get('popup_data'),
            ],
            'lembaga' => fn () => \Illuminate\Support\Facades\Cache::remember('lembaga_global', 60*60, function () {
                return \App\Models\Lembaga::first();
            }),
        ];
    }

    /**
     * Handle the incoming request.
     * 
     * PERBAIKAN: Override handle method untuk force 303
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = parent::handle($request, $next);

        // Force 303 redirect for PUT, PATCH, DELETE requests that are Inertia requests
        if ($response instanceof \Illuminate\Http\RedirectResponse &&
            in_array($request->method(), ['PUT', 'PATCH', 'DELETE', 'POST']) &&
            $request->header('X-Inertia')) {
            $response->setStatusCode(303);
        }

        return $response;
    }
}