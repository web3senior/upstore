<?php

class Hash
{

  /**
   *
   * @param string $algo The algorithm (md5, sha1, whirlpool, etc)
   * @param string $data The data to encode
   * @param string $salt The salt (This should be the same throughout the system probably)
   * @return string The hashed/salted data
   */
  public static function create($algo, $data, $salt)
  {
    $context = hash_init($algo, HASH_HMAC, $salt);
    hash_update($context, $data);
    return hash_final($context);
  }

  /**
   *
   * @return string
   */
  public static function token()
  {
    $hexStr = '';
    for ($i = 0; $i < 4; $i++) {
      $hexStr .= dechex(time() + rand(0, 15)) . '-';
    }
    return rtrim($hexStr, "-");
  }

  /**
   * http://php.net/manual/en/function.password-hash.php
   * @param string $data
   * @return string
   */
  public static function bcrypt($password)
  {
    $algo = PASSWORD_DEFAULT; /* PASSWORD_DEFAULT or PASSWORD_BCRYPT or PASSWORD_ARGON2I */
    $options = [
      'cost' => 12,
    ];
    return password_hash($password, $algo);
    /*
      if (password_verify('rasmuslerdorf', $hash)) {
      echo 'Password is valid!';
      } else {
      echo 'Invalid password.';
      }
     */
  }

}