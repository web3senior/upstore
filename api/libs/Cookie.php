<?php

class Cookie
{

  public $cookieName = null;        # @var string
  public $cookieValue = null;       # @var string
  public $cookiExpire = null;       # @var int (Timestamp)
  public $cookiePath = null;        # @var string (Default path)
  public $cookieDomain = null;      # @var string (Default domain)
  public $cookieSecure = false;     # @var bool (HTTPS)
  public $cookieHttpOnly = null;    # @var bool
  private $cookieErrorReport = "Failed to the Cookie";

  #Constructor

  /**
   *
   * @param string $name
   * @param string $value
   * @param string $day
   * @param string $path
   * @param bool $httpOnly
   */

  function __construct()
  {
  }

  # If HTTPS?

  /**
   * https
   */

  private function isSecure()
  {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
      $this->cookieSecure = true;
    }
  }

  # Set the expirations Date for the cookie

  /**
   *
   * @param int $day
   */

  private function setExpirationDate($day)
  {
    $this->cookieExpire = time() + ($day * 24 * 60 * 60);
  }

  # Set the cookie

  /**
   * set
   * @throws Exception
   */
  public function set($name, $value, $day, $path, $httpOnly)
  {
    // $this->cookieDomain = "." . $_SERVER['HTTP_HOST'];
    $this->cookieDomain = "localhost";
    $this->cookieName = $name;
    $this->cookieValue = $value;
    $this->cookiePath = $path;
    $this->cookieHttpOnly = $httpOnly || true;
    $this->isSecure();
    $this->setExpirationDate($day);
    if (!setcookie($this->cookieName, $this->cookieValue, $this->cookieExpire, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, $this->cookieHttpOnly)) {
      throw new Exception($this->cookieErrorReport);
    }
  }

  # Is the cookie set

  /**
   *
   * @param string $name
   * @return bool
   */

  public function get($name)
  {
    return (isset($_COOKIE[$name])) ? $_COOKIE[$name] : false;
  }

}
