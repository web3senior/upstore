<?php

require __DIR__ . '/config.php';
require __DIR__ . '/util/Auth.php';
require __DIR__ . '/util/Fn.php';
require __DIR__ . '/jdf.php';
require_once __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class) {
    require_once './libs/' . $class . '.php';
});

(new Bootstrap)->init();
