<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $repo;
    public function __construct()
    {
        $this->repo = new AuthRepository();
    }

    public function loginView()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = isset($request->remember) ? true : false;

        if ($this->repo->login($credentials, $remember)) {
            return redirect()->route('dashboard');
        }
        return redirect()
            ->route('login')
            ->withInput()
            ->withErrors(['auth' => 'Invalid email or password.']);
    }

    public function logout(Request $request)
    {
        $this->repo->logout($request);
        return redirect()->route('login');
    }

}
