<?php

class Token
{
    public function __construct()
    {
    }

    function make()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
