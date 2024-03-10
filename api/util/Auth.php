<?php

use Auth0\SDK\Auth0;

class Auth
{
  static function handleAdminLogin()
  {
    (new Session)->init();
    $logged = $_SESSION['loggedAdmin'];
    if (!$logged) {
      session_destroy();
      header('location: ' . URL . 'admin');
      exit;
    }

    $expire_activity = (new Session)->get('expire_activity');
    if (time() > $expire_activity) {
      (new Session)->destroy();
    }
  }

  public function Auth0()
  {
    $auth0 = new Auth0([
      'domain' => AUTH_DOMAIN,
      'client_id' => AUTH_CLIENT_ID,
      'client_secret' => AUTH_CLIENT_SECRET,
      'redirect_uri' => 'http://www.mylocalhost.com/onetask/panel',
      'scope' => 'openid profile email'
    ]);
    $userInfo = $auth0->getUser();
    if (!$userInfo) {
      $auth0->login();
    } else {
      return $userInfo;
    }
  }

  private function check_login_status($name)
  {
    $chk = @md5($_SERVER['HTTP_ACCEPT_CHARSET'] . $_SERVER['HTTP_ACCEPT_ENCODING'] . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    if ($_SESSION[$name] == true && $_SESSION['key'] == $chk) {
      return true;
    }
    return false;
  }

  public function handleAdminLoggin()
  {
    @session_start();
    $logged = $_SESSION['loggedAdmin'];

    if (!$this->check_login_status("loggedAdmin")) {
      unset($_SESSION['loggedAdmin']);
      header('location: ' . URL . 'admin');
      exit;
    }

    if ($logged == false) {
      unset($_SESSION['loggedAdmin']);
      header('location: ' . URL . 'admin');
      exit;
    }
  }

  public static function handleUserLogin()
  {
    (new Session)->init();
    $logged = $_SESSION['userLogin'];
    if ($logged == false) {
      session_destroy();
      header('Location: ' . URL . 'account');
      exit;
    }
  }
}
