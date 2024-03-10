<?php
(new Session)->init();
?>
<!DOCTYPE html>
<html lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME . ' | ' . ($this->title) ?? $this->title ?></title>
    <link rel="stylesheet" href="<?= URL ?>dist/panel.min.css">
    <script type="text/javascript" src="<?= URL ?>dist/panel.bundle.js" defer></script>
    <script type="module" src="https://unpkg.com/@fluentui/web-components"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="<?= URL ?>dist/ckeditor5-39.0.1-n8a8n1shajuq/build/ckeditor.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
            window.addEventListener('load', ()=>{
                document.querySelector(':root').style.setProperty('--primary-color', localStorage.getItem('primaryColor'))
            })
    </script>
</head>

<body class="ms-Fabric" dir="rtl" data-url="<?= URL ?>">
    <div id="root">
        <div id="loading" class="loading d-inline-flex flex-column align-items-center justify-content-center ml-10"></div>

        <nav>
            <?php require_once 'nav.php' ?>
        </nav>

        <main>
            <header>
                <a href="<?= URL ?>panel/admin">
                    <figure>
                        <img src="<?= URL ?>upload/images/<?= json_decode($_COOKIE['admin_info'])->avatar ?>" alt="">
                    </figure>
                </a>
            </header>
            <main>