<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function home(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('main');
        }

        return view('home');
    }

    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('main');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('main');
        }

        $credentials = $request->validated();

        $usuario = Usuario::query()->where('username', $credentials['user'])->first();
        $validPassword = $usuario && (
            Hash::isHashed($usuario->password)
                ? Hash::check($credentials['pass'], $usuario->password)
                : hash_equals((string) $usuario->password, $credentials['pass'])
        );

        if (! $validPassword) {
            return back()
                ->withErrors(['user' => 'El usuario o la contraseña no son correctos.'])
                ->onlyInput('user');
        }

        $request->session()->regenerate();
        $request->session()->put([
            'user_id' => $usuario->id,
            'user_type' => $usuario->tipo,
            'username' => $usuario->username,
        ]);

        return redirect()->route('main');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
