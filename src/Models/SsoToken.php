<?php
namespace Axenso\Sso\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SsoToken extends Model {
    use HasFactory;
    //protected $table= 'sso_tokens';
    protected $connection = 'mysql';
    protected $fillable=[
       'token',
       'expires_at',
       'refresh_token'
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];

}