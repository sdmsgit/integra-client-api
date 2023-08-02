<?php

namespace App\Http\Library;

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class ClientApiKey
{

    protected $internalKey;

    public function __construct()
    {
        $this->internalKey = env('INTERNAL_KEY');
    }

    public function decodeToken($token)
    {
        $tokenData = JWT::decode($token,  new Key($this->internalKey, 'HS256'));
        return $tokenData;
    }

    public function validateAppId($token, $database)
    {
        $validateAppId = DB::connection($database)->table('m_client_api_key')->where('app_id', $token)->where('status', true)->where('enabled', true)->first();
        if ($validateAppId) {
            return $validateAppId;
        } else {
            return false;
        }
    }
}
