<?php
namespace Axenso\Sso\Http\Controllers;

use Axenso\Sso\Sso;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Axenso\Sso\Traits\ApiResponser;


class SsoController extends Controller
{
    use ApiResponser;

    public function login(Request $request){
        $sso = new Sso();
        $response = $sso->login($request->email,$request->password);
        return $this->showMessage($response->object(),$response->status());
    }

    public function register(Request $request){
       $sso = new Sso();
       $response =  $sso->register($request->email,$request->password,$request->password_confirmation,$request->profile);
       return $this->showMessage($response->object(),$response->status());
    }

    public function verifyEmail(Request $request){
        $sso = new Sso();
        $response =  $sso->verifyEmail($request->activation_code);
        return $this->showMessage($response->object(),$response->status());
     }

    public function consent(Request $request) {
        $sso = new Sso();
        $response =  $sso->consent($request->user_id);
        return $this->showMessage($response->object(),$response->status());
    }

    public function changePassword(Request $request) {
        $sso = new Sso();
        $response = $sso->changePassword($request->user_id,$request->old_password,$request->password,$request->password_confirmation);
        return $this->showMessage($response->object(),$response->status());
    }

    public function requestResetPassword(Request $request) {
        $sso = new Sso();
        $response = $sso->requestResetPassword($request->email);
        return $this->showMessage($response->object(),$response->status());

    }
    public function passwordReset(Request $request) {
        $sso = new Sso();
        $response = $sso->passwordReset($request->password,$request->password_confirmation,$request->reset_token);
        return $this->showMessage($response->object(),$response->status());
    }
    public function getProfile(Request $request) {
        $sso = new Sso();
        $response = $sso->getProfile($request->user_id);
        return $this->showMessage($response->object(),$response->status());
    }
    public function updateProfile(Request $request) {
        $sso = new Sso();
        $response = $sso->updateProfile($request->user_id,$request->profile);
        return $this->showMessage($response->object(),$response->status());
    }
}
