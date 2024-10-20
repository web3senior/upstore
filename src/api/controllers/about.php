<?php

class About extends Controller
{

    function __construct()
    {
        parent::__construct();
        //echo 'we are inside help<br />';
    }

    function index()
    {
        $this->view->title = 'About Us';
        $this->view->render('header');
        $this->view->render('about/index');
        $this->view->render('footer');
    }

}
