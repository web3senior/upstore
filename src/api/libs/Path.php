<?php


class Path
{
  private function currentUrl()
  {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  }

  public function fileName()
  {
    return pathinfo($this->currentUrl(), PATHINFO_FILENAME);
  }
}