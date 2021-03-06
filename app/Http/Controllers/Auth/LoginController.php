<?php

namespace Inayat\Http\Controllers\Auth;

use Inayat\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inayat\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout', 'authenticate']);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');

        if (!(User::where('phone', '=', $request->get('phone'))->exists())) {
            return back()->with('danger', 'User does not exist!');
        }

        if (Auth::attempt(['phone' => $phone, 'password' => $password]))
        {
            return redirect()->intended('dashboard');
        }

        return back()->with('warning', 'Wrong Password or Inactive Account. Contact Admin!');
    }

    /**
     * Logout Action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
    }
}
