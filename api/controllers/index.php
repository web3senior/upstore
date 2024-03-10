<?php

class Index extends Controller
{
  function __construct()
  {
    parent::__construct();
    (new Session)->init();

  }

  function index()
  {
    require 'models/index_model.php';
    $this->model = new Index_Model();
  
    $this->view->title = NAME;
    $this->view->data = [];
    $this->view->render('header');
    $this->view->render('page/index');
    $this->view->render('footer');
  }
}
