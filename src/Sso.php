<?php

namespace Axenso\Sso;

use Carbon\Carbon;
use Axenso\Sso\Models\SsoToken;
use Axenso\Sso\Traits\ApiResponser;
use Illuminate\Support\Facades\Http;

class Sso {
    use ApiResponser;
    protected $grant_type;
    protected $client_secret;
    protected $client_id;
    protected $sso_base_url;


    public function __construct()
    {
        $this->token = '';
        $this->grant_type = config('sso.sso_grant_type');
        $this->client_secret = config('sso.sso_client_secret');
        $this->client_id = config('sso.sso_client_id');
        $this->sso_base_url = config('sso.sso_base_url');
    }

    public function login($email,$password) {
        $token = $this->getToken($email,$password);

        $response = Http::accept('application/json')
                            ->withToken($token->token)
                            ->post($this->sso_base_url.'/api/user/login',[
                                'email' => $email,
                                'password' => $password,
                                'client_id' => $this->client_id
                            ]);
        return $response;
    }
    public function register($email,$password,$password_confirmation,$profile) {
        $payload = array(
          'email' => $email,
          'password' => $password,
          'password_confirmation' => $password_confirmation,
          'profile' => $profile,
          'account_id' =>  $this->client_id,
          'client_id' =>  $this->client_id,
          'client_secret' =>  $this->client_secret,
        );
       
        $response = Http::post($this->sso_base_url.'/api/user/signup', $payload);
        return $response;
 
    }

    public function verifyEmail($email,$activation_code) {
        $payload = array(
            'email' => $email,
            'activation_code' => $activation_code,
            'client_id' =>  $this->client_id,
            'client_secret' =>  $this->client_secret,
          );
         
          $response = Http::post($this->sso_base_url.'/api/user/activate', $payload);
          return $response;
    }

    public function consent() {

    }

    public function updateProfile() {

    }

    public function passwordResetRequest() {

    }

    public function passwordReset() {

    }

    public function changePassword() {

    }

    public function getToken($email,$password) {
        $now = Carbon::now()->addHours(1);
        $existingToken = SsoToken::where('expires_at' ,'>' ,$now )->first();
        if ($existingToken) {
            return $existingToken;
        }
        $sessionToken = Http::post($this->sso_base_url.'/oauth/token', [
            'grant_type' => $this->grant_type,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'username' => $email,
            'password' => $password,
        ]);
        if ($sessionToken->status() == 200) {
            $token = SsoToken::create([
                        'token' => $sessionToken->object()->access_token ,
                        'refresh_token' => $sessionToken->object()->refresh_token ,
                        'expires_at' =>  Carbon::now()->addHours(23)
                    ]);
            return $token;
        }
        else {
            return null;
        }
        return null;
    
    }
}