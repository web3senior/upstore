<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth
{
  protected string $_privateKey = "Ilovelearningandcode";
  protected array $_payload = []; // data
  protected string $_serverName = URL;

  public function __construct()
  {
    // JWT::$leeway = 60; // $leeway in seconds
  }

  /**
   * @param Array $data
   * @return string
   */
  public function encode(array $data): string
  {
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+31 days')->getTimestamp(); // Add 60 seconds
    $this->_payload = [
      'iat' => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
      'iss' => $this->_serverName,                       // Issuer
      'nbf' => $issuedAt->getTimestamp(),         // Not before
      'exp' => $expire,                           // Expire
      'data' => $data,                     // User name
    ];
    return JWT::encode($this->_payload, $this->_privateKey, 'HS256');
  }

  /**
   * Decode/ Verify
   * @param String $toekn
   */
  public function decode(string $token): array
  {
    try {
      $decoded = JWT::decode($token, new Key($this->_privateKey, 'HS256'));
      return ([
        "result" => true,
        "response" => $decoded
      ]);
    } catch (Exception $ex) {
      return ([
        "result" => false,
        "status" => [
          "timestamp" => time(),
          "error_code" => 400,
          "error_message" => "Invalid token " . $ex,
        ]
      ]);
    }
  }
}
