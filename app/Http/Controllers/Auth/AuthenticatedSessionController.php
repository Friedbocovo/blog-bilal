<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $redirect = $request->input('redirect');

        if ($redirect) {
            if (Str::startsWith($redirect, '/')) {
                return redirect()->to($redirect);
            }

            $parsedRedirect = parse_url($redirect);

            if ($parsedRedirect !== false && isset($parsedRedirect['scheme'], $parsedRedirect['host'])) {
                $redirectPort = $parsedRedirect['port'] ?? ($parsedRedirect['scheme'] === 'https' ? 443 : 80);
                $currentPort = $request->getPort();

                if (
trim($parsedRedirect['host'], '.') === $request->getHost()
                    && $redirectPort == $currentPort
                    && $parsedRedirect['scheme'] === $request->getScheme()) {
                    return redirect()->to($redirect);
                }
            }
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
