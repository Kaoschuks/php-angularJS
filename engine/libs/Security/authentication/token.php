<?php

class Token extends DataSecurity
{
    public static function generateToken($access = 'Guest', $key = null)
    {
        $csrf = base64_encode(bin2hex(openssl_random_pseudo_bytes(12))).$key;
        return base64_encode(parent::cryptoJsAesEncrypt(
                $key, 
                array(
                    "realm" => $_SERVER['HTTP_HOST'],
                    "ipaddress" => $_SERVER['REMOTE_ADDR'],
                    "browser" => @ $_SERVER['HTTP_USER_AGENT'],
                    "access" => $access,
                    "csrf" => $csrf,
                    "issued" => strtotime(Date('D:M:Y')),
                    "expires" => (int)strtotime(Date('D:M:Y'))+(60*60*60*24),
                )
            )        
        );
    }

    private function authenticateToken($data = null, $csrf = null)
    {
        return ($_SERVER['REMOTE_ADDR'] === $data['ipaddress'] 
                && @ $_SERVER['HTTP_USER_AGENT'] === $data['browser'] 
                && $csrf === $data['csrf'])
                    ? 200
                    : 403;
    }

    public static function verifyToken($access = 'Guest', $jwt = null, $key = null, $csrf = null)
    {
        return self::authenticateToken(parent::cryptoJsAesDecrypt($key, base64_decode($jwt)), $csrf);
    }
}

?>