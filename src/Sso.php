<?php

namespace Axenso\Sso;

use Carbon\Carbon;
use Axenso\Sso\Models\SsoToken;
use Axenso\Sso\Traits\ApiResponser;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

class Sso {
    use ApiResponser;
    protected $grant_type;
    protected $client_secret;
    protected $client_id;
    protected $sso_base_url;
    protected $token;

    public function __construct()
    {
        $this->grant_type = config('sso.sso_grant_type');
        $this->client_secret = config('sso.sso_client_secret');
        $this->client_id = config('sso.sso_client_id');
        $this->sso_base_url = config('sso.sso_base_url');
        $this->token = $this->getToken();
    }

    public function login($email,$password) {
        $response = Http::accept('application/json')
                             ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                            ->post($this->sso_base_url.'/api/user/login',[
                                'email' => $email,
                                'password' => $password,
                                'client_id' => $this->client_id
                            ]);
        return $response;
    }
    public function loginByToken($token,$origin_client_id) {
        $response = Http::accept('application/json')
        ->withHeaders(['origin' => config('app.url')])
       ->withToken($this->token->token)
       ->post($this->sso_base_url.'/api/user/userTokenAuth',[
           'token' => $token,
           'client_id' => $this->client_id,
           'origin_client_id' => $origin_client_id
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
        $response = Http::accept('application/json')
                             ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                            ->post($this->sso_base_url.'/api/user/signup', $payload);
        return $response;
    }

    public function verifyEmail($activation_code) {
        $payload = array(
            'activation_code' => $activation_code,
          );
          $response = Http::accept('application/json')
                            ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                            ->post($this->sso_base_url.'/api/user/activate', $payload);
          return $response;
    }

    public function consent($email) {
        $payload = [
            'client_id' => $this->client_id,
            'email' => $email
        ];
        $response = Http::accept('application/json')
                         ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/consent',$payload);
        return $response;
    }

    public function requestResetPassword($email) {
        $payload = [
            'email' => $email,
            'client_id' => $this->client_id,
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/password/reset-request',$payload);
        return $response;
    }

    public function passwordReset($password,$password_confirmation,$reset_token) {
        $payload = [
            'reset_token' => $reset_token,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ];
        $response = Http::accept('application/json')
                         ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/password/reset',$payload);
        return $response;
    }

    public function changePassword($user_id,$old_password,$password,$password_confirmation) {
        $payload = [
            'user_id' => $user_id,
            'old_password' => $old_password,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ];
        $response = Http::accept('application/json')
                         ->withHeaders(['origin' => config('app.url')])
                            ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/password/change',$payload);
        return $response;
    }
    public function getProfile($user_id) {
        $payload = [
            'user_id' => $user_id,
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->get($this->sso_base_url.'/api/user/get-profile',$payload);
        return $response;
    }
    public function updateProfile($user_id,$profile) {
        $payload = [
            'user_id' => $user_id,
            'client_id' => $this->client_id,
            'profile' => $profile,
        ];
        $response = Http::accept('application/json')
                         ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/update-profile',$payload);
        return $response;
    }
    public function lookUpEmail($email) {
        $payload = [
            'email' => $email,
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/lookup-email',$payload);
        return $response;
    }

    public function lookUpConsent($client_id,$user_id) {
        $payload = [
            'client_id' => $client_id,
            'user_id' => $user_id,
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/lookup-consent',$payload);
        return $response;
    }

    public function ManualConsent($client_id,$user_id) {
        $payload = [
            'client_id' => $client_id,
            'user_id' => $user_id,
        ];
        $response = Http::accept('application/json')
                        ->withHeaders(['origin' => config('app.url')])
                        ->withToken($this->token->token)
                        ->post($this->sso_base_url.'/api/user/manual-consent',$payload);
        return $response;
    }


    public function getProfessions() {
        $response = Http::accept('application/json')
                            ->withHeaders(['origin' => config('app.url')])
                            ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/professions');
        return $response;
    }

    public function getCountries() {
        $response = Http::accept('application/json')
                            ->withHeaders(['origin' => config('app.url')])
                            ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/countries');
        return $response;
    }

    public function getCities($province_short) {
        $response = Http::accept('application/json')
          ->withHeaders(['origin' => config('app.url')])
          ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/cities?province='.$province_short);
        return $response;
    }

    public function getCityCode($city) {
        $response = Http::accept('application/json')
          ->withHeaders(['origin' => config('app.url')])
          ->get($this->sso_base_url.'/api/data/'.$this->client_id.'/cityCode?city='.$city);
        return $response;
    }

    public function getGlobalConfig() {
        $response = Http::accept('application/json')
          ->withHeaders(['origin' => config('app.url')])
          ->get($this->sso_base_url.'/api/data/configuration');
        return $response;
    }


    public function getToken() {
        $now = Carbon::now()->addHours(1);
        $existingToken = SsoToken::where('expires_at' ,'>' ,$now )->first();

        if ($existingToken != null ) {
            return $existingToken;
        }
        $sessionToken = Http::post($this->sso_base_url.'/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        if ($sessionToken->status() == 200) {
            $token = SsoToken::create([
                        'token' => $sessionToken->object()->access_token ,
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
