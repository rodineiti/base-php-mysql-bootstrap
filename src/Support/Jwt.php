<?php

namespace Src\Support;

/**
 * Class Jwt
 * @package Src\Support
 */
class Jwt
{
    /**
     * @param $data
     * @return string
     */
    public static function generate($data)
    {
        $header = json_encode(array("typ"=>"JWT", "alg"=>"HS256"));

        $payload = json_encode($data);

        $hbase = self::base64url_encode($header);
        $pbase = self::base64url_encode($payload);

        $signature = hash_hmac("sha256", $hbase.".".$pbase, CONF_JWT_SECRET, true);
        $bsig = self::base64url_encode($signature);

        $jwt = $hbase.".".$pbase.".".$bsig;

        return $jwt;
    }

    /**
     * @param $token
     * @return array|bool|mixed
     */
    public static function validate($token)
    {
        // Step 1: Check if the TOKEN has 3 parts.
        // Step 2: Hit the signature with the data
        $array = array();

        $jwt_split = explode('.', $token);

        if(count($jwt_split) == 3) {
            $signature = hash_hmac("sha256", $jwt_split[0].".".$jwt_split[1], CONF_JWT_SECRET, true);
            $bsig = self::base64url_encode($signature);

            if($bsig == $jwt_split[2]) {
                $array = json_decode(self::base64url_decode($jwt_split[1]));
                return $array;

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return string
     */
    private static function base64url_encode($data ){
        return rtrim( strtr( base64_encode( $data ), '+/', '-_'), '=');
    }

    /**
     * @param $data
     * @return false|string
     */
    private static function base64url_decode($data ){
        return base64_decode( strtr( $data, '-_', '+/') . str_repeat('=', 3 - ( 3 + strlen( $data )) % 4 ));
    }
}