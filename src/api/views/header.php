<?php
require 'models/base_model.php';
// $menu = (new Base_Model)->menu();
// $submenu = (new Base_Model)->submenu();
(new Buffer)->start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@<?= $this->title . ' | ' . NAME ?> </title>
  <meta name="keywords" content="Universal link, universal profile, lukso" />
  <meta name="author" content="<?= AUTHOR ?>" />
  <meta name="robots" content="index, follow" />
  <meta name="theme-color" content="#FD1669" />

  <!-- Disable Cache -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,1,0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;300;500;700;900&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="<?= URL ?>dist/home.min.css">
  <!-- <script src="./dist/admin.bundle.js" type="module"></script> -->
</head>
<?php flush(); ?>

<body>
  <div id="root" class="layout">
    <header class="header">
      <div class="container d-flex align-items-center justify-content-between">
        <a href="/">
          <div class="logo d-flex align-items-center">
            <figure>
              <img src="./logo.svg" alt="logo" />
            </figure>
            <b><?= NAME ?></b>
          </div>
        </a>

        <ul class="d-flex align-items-center justify-content-between">
          <li class="">
            <a href="/">
              <span>Home</span>
            </a>
          </li>
          <li class="">
            <a href="about">
              <span>About</span>
            </a>
          </li>
        </ul>

        <div class={`d-flex align-items-center`} style="column-gap: 1rem ">
          <button class="navButton" onclick="handleNavLink()">
            <span class="material-symbols-outlined">
              menu
            </span>
          </button>
        </div>
      </div>
    </header>

    <div class="cover" onclick="handleOpenNav()" />
    <nav class="nav animate" id="modal">
      <figure>
        <img src="./logo.svg" alt={`logo`} />
      </figure>
      <ul>
        <li class="">
          <a href="/">
            <span>Home</span>
          </a>
        </li>
        <li class="">
          <a href="/">
            <span>About</span>
          </a>
        </li>
      </ul>

      <small>{`Version ${import.meta.env.VITE_VERSION}`}</small>
    </nav>
  </div>
  <main>