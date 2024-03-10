<?php

class View
{
  public string $title;
  public $data;
  public string $endpoint;
  public string $pg;
  public string $token;

  public function render($name, $noInclude = false)
  {
    require './views/' . $name . '.php';
  }
}