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
        $response =  $sso->verifyEmail($request->email,$request->activation_code);
        return $this->showMessage($response->object(),$response->status());
     }

}