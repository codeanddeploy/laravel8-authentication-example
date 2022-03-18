<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TwoFactor\Authy;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TwoFactorVerifyRequest;

class TwoFactorController extends Controller
{
	public function __construct(User $users, Authy $authy) 
	{
		$this->users = $users;
		$this->authy = $authy;
	}

    /**
     * Display login page.
     * 
     * @return Renderable
     */
    public function show()
    {
        return view('auth.token');
    }

    public function perform(TwoFactorVerifyRequest $request) 
    {
    	$user = $this->users->find(session('auth.2fa.id'));

        if(!$user){
            return redirect('login');
        }

        $verfiy = $this->authy->verifyToken($user->authy_id, $request->get('authy_token'));

        if($verfiy->ok()){
            Auth::login($user);
            return redirect('/');
        } else {
            return redirect('token')->with('authy_error', __('The token you entered is incorrect'));
        }
    }
}
