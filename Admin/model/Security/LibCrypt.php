<?php

class Unsafe
{
    const METHOD = 'aes-256-ctr';

    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */
    protected function encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    protected function decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Decryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}

    
    // Hashing algorithm
    
    /**
     * Generates a secure, pseudo-random password with a safe fallback.
     */
    
    function pseudo_rand($length) 
    {
        if (function_exists('openssl_random_pseudo_bytes')) 
        {
            $is_strong = false;
            $rand = openssl_random_pseudo_bytes($length, $is_strong);
            if ($is_strong === true) return $rand;
        }
        $rand = '';
        $sha = '';
        for ($i = 0; $i < $length; $i++) {
            $sha = hash('sha256', $sha . mt_rand());
            $chr = mt_rand(0, 62);
            $rand .= chr(hexdec($sha[$chr] . $sha[$chr + 1]));
        }
        return $rand;
    }

    /**
     * Creates a very secure hash. Uses blowfish by default with a fallback on SHA512.
     */
    
    function create_hash($string, &$salt = '', $stretch_cost = 10) 
    {
        $salt = @ pseudo_rand(128);
        $salt = substr(str_replace('+', '.', base64_encode($salt)), 0, 22);
            if (function_exists('hash') && in_array($hash_method, hash_algos()) )
            {
                return crypt($string, '$2a$' . $stretch_cost . '$' . $salt);
            }
        return _create_hash($string, $salt);
    }
            
    /**
     * Fall-back SHA512 hashing algorithm with stretching.
     */
            
    function _create_hash($password, $salt) 
    {
        $hash = '';
        for ($i = 0; $i < 20000; $i++) {
            $hash = hash('sha512', $hash . $salt . $password);
        }
        return $hash;
    }


?>