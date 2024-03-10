<?php

class Session
{

  static function init()
  {
    @session_start();
  }

  static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  static function get($key)
  {
    if (isset($_SESSION[$key]))
      return $_SESSION[$key];
  }

  static function destroy($name = false)
  {
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', time() - 86400, '/');
    }

    if ($name == null || $name = "" || empty($name)) {
      session_destroy();
      session_write_close();
      session_unset();
      $_SESSION = array();
    } else {
      unset($_SESSION['$name']);
    }
  }

}