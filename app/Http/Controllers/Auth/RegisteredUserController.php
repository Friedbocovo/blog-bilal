<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $redirect = $request->input('redirect');

        if ($redirect) {
            if (Str::startsWith($redirect, '/')) {
                return redirect()->to($redirect);
            }

            $parsedRedirect = parse_url($redirect);

            if ($parsedRedirect !== false && isset($parsedRedirect['scheme'], $parsedRedirect['host'])) {
                $redirectPort = $parsedRedirect['port'] ?? ($parsedRedirect['scheme'] === 'https' ? 443 : 80);
                $currentPort = $request->getPort();

                if (rtrim($parsedRedirect['host'], '.') === $request->getHost()
                    && $redirectPort == $currentPort
                    && $parsedRedirect['scheme'] === $request->getScheme()) {
                    return redirect()->to($redirect);
                }
            }
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
