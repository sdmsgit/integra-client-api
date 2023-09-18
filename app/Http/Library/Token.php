<?php

namespace App\Http\Library;

use Illuminate\Support\Facades\DB;

class Token
{

    public function getToken($database, $apiKeyId)
    {
        $datetimeNow = date("Y-m-d H:i:s");
        $tokenData = DB::connection($database)->table('m_access_token')->select('token', 'expired_at')->where('api_key_id', $apiKeyId)->where('expired_at', '>', $datetimeNow)->first();
        if ($tokenData) {
            return $tokenData;
        } else {
            $token = $this->generateRandomString(20);
            $checkExists = DB::connection($database)->table('m_access_token')->where('api_key_id', $apiKeyId)->where('token', $token)->first();
            while ($checkExists) {
                $token = $this->generateRandomString(20);
                $checkExists = DB::connection($database)->table('m_access_token')->where('api_key_id', $apiKeyId)->where('token', $token)->first();
            }
            $dataInput = array(
                'api_key_id' => $apiKeyId,
                'token' => $this->generateRandomString(20),
                'expired_at' => date("Y-m-d H:i:s", strtotime("+1 day")),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            );

            $insertData = DB::connection($database)->table('m_access_token')->insert(
                $dataInput
            );
            $tokenData = DB::connection($database)->table('m_access_token')->select('token', 'expired_at')->where('api_key_id', $apiKeyId)->where('expired_at', '>', $datetimeNow)->first();

            return $tokenData;
        }
    }

    public function validateToken($database, $token)
    {
        $datetimeNow = date("Y-m-d H:i:s");
        $tokenData = DB::connection($database)->table('m_access_token')->select('token', 'expired_at')->where('token', $token)->where('expired_at', '>', $datetimeNow)->first();
        if (!$tokenData) {
            return false;
        } else {
            return true;
        }
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ=';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
