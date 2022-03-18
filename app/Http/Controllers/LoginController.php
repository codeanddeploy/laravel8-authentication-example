<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwoFactor\Authy;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Login\RememberMeExpiration;

class LoginController extends Controller
{
    use RememberMeExpiration;

    protected $authy;

    public function __construct(Authy $authy) 
    {
        $this->authy = $authy;
    }

    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle account login request
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
            return redirect()->to('login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user, $request->get('remember'));

        if($request->get('remember')):
            $this->setRememberMeExpiration($user);
        endif;

        return $this->authenticated($request, $user);
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user) 
    {
        if(!$user->isTwoFactorEnabled()){
            return redirect()->intended();
        }

        $status = $this->authy->verifyUserStatus($user->authy_id);

        if($status->ok() && $status->bodyvar('status')->registered) {
            Auth::logout();

            $request->session()->put('auth.2fa.id', $user->id);

            $sms = $this->authy->sendToken($user->authy_id);

            if($sms->ok()){
                return redirect('/token');
            }
        } else {
             Auth::logout();
            return redirect('login')->with('message', __('Could not confirm Authy status!'));
        }
        
    }
}
