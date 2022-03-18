<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TwoFactor\Authy;
use App\Http\Requests\TwoFactorVerifyRequest;

class ProfileController extends Controller
{
    protected $users;
    protected $authy;

    public function __construct(User $users, Authy $authy) 
    {
        $this->users = $users;
        $this->authy = $authy;
    }

    public function index() 
    {
        return view('profile.index');
    }

    public function enableTwoFactor(Request $request) 
    {
        $user = auth()->user();

        $checkUser = User::where('authy_country_code', $request->get('country_code'))
            ->where('authy_phone', $request->get('phone_number'))
            ->first();

        if(is_null($checkUser)) {
            $register = $this->authy->register(
                $user->email, 
                $request->get('phone_number'),
                $request->get('country_code')
            );

            if ($register->ok()) {
                $authyId = $register->id();

                $user->update([
                    'authy_status' => false,
                    'authy_id' => $authyId,
                    'authy_country_code' => $request->get('country_code'),
                    'authy_phone' => $request->get('phone_number')
                ]);
            } else {
                return redirect('profile')->with('authy_errors', $register->errors());
            }

        } else {
            $authyId = $checkUser->authy_id;
        }

        $this->authy->sendToken($authyId);

        return redirect('profile/two-factor/verification');
    }

    public function disableTwoFactor(Request $request) 
    {
        $user = auth()->user();

        $user->update([
            'authy_status' => false
        ]);

        return redirect('profile')
            ->with('success',  __('Two factor authentication has been disabled.'));
    }

    public function getVerifyTwoFactor() 
    {
        return view('profile.verify-two-factor');
    }

    public function postVerifyTwoFactor(TwoFactorVerifyRequest $request) 
    {
        $user = auth()->user();

        $verfiy = $this->authy->verifyToken($user->authy_id, $request->get('authy_token'));

        if ( $verfiy->ok() ) {
            $user->update(['authy_status' => 1]);

            return redirect('profile')
                ->with('success', __('Two factor authentication has been enabled.'));
        }

        return redirect('profile/two-factor/verification')->with('authy_error', __('The token you entered is incorrect'));
    }
}
