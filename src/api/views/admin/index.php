<?php
(new Buffer)->start();
?>
<!DOCTYPE html>
<html lang="fa-IR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="" />
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="<?= GOOGLE_CLIENT_ID ?>">
    <title><?= SITE_NAME . ' | ' . 'ورود به پنل مدیریت' ?> </title>
    <link rel="stylesheet" href="<?= URL ?>dist/admin.min.css">
    <link rel="manifest" href="manifest.json">
    <link rel="shortcut icon" href="<?= URL ?>favicon.ico">
    <link rel="apple-touch-icon" href="<?= URL ?>public/images/apple-touch-icon.png">
    <link rel="stylesheet" type="text/css" href="<?= URL ?>dist/fonts.css">
    <style>
        :root {
            --logo: url("<?= URL ?>/dist/images/abidata.svg");
            --aside-background-image: url("<?= URL ?>/dist/images/hero-bg.png");
            --main-background-image: url("<?= URL ?>/public/images/bg-pattern.svg");
        }
    </style>
    <script src="./dist/admin.bundle.js" type="module"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>

</head>

<body dir="rtl" class="ms-Fabric" data-google-recaptcha-site-key="<?= GOOGLE_RECAPTCHA_SITE_KEY ?>" data-google-client-id="<?= GOOGLE_CLIENT_ID ?>" data-base-url="<?= URL ?>">
    <div id="root">
        <div class="loading"><span></span></div>
        <main>
            <div class="form-container ">
                <div>
                    <figure>
                        <img alt=" " src="<?= URL ?>logo.svg" />
                    </figure>
                    <h4>ورود به پنل مدیریت</h4>
                    <p>برای ورود لطفا ایمیل و گذرواژه خود را وارد نمایید.</p>
                </div>

                <form id='demo-form' method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" id="token" name="token" value="<?= $this->token ?>" />
                    <ul>
                        <li>
                            <output></output>
                        </li>

                        <li>
                            <input class="form-control" id="email" type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" list="emails" spellcheck="false" placeholder="ایمیل" require />
                            <datalist id="emails">
                                <option value="info@example.com">
                                <option value="info@abidata.co">
                            </datalist>
                        </li>

                        <li>
                            <input class="form-control" id="password" type="password" name="password" pattern=".{3,}" placeholder="کلمه عبور" require />
                        </li>
                        <li hidden>
                            <div class="cf-turnstile" data-sitekey="0x4AAAAAAAHocyFac0HcMpxg" data-callback="javascriptCallback"></div>
                        </li>

                        <li>
                            <button type="submit" name="btnSubmit" disabled>
                                ورود
                            </button>
                        </li>
                        <li>
                            <button class="btn-google">
                                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.99998 4.0104C11.475 4.0104 12.7958 4.51873 13.8375 5.5104L16.6917 2.65623C14.9583 1.04373 12.6958 0.052063 9.99998 0.052063C6.09165 0.052063 2.71248 2.29373 1.06665 5.5604L4.39165 8.13956C5.17915 5.76873 7.39165 4.0104 9.99998 4.0104Z" fill="#EA4335" />
                                    <path d="M19.575 10.2812C19.575 9.62704 19.5125 8.99371 19.4167 8.38538H10V12.1437H15.3917C15.15 13.377 14.45 14.427 13.4 15.1354L16.6208 17.6354C18.5 15.8937 19.575 13.3187 19.575 10.2812V10.2812Z" fill="#4285F4" />
                                    <path d="M4.3875 11.9646C4.1875 11.3604 4.07083 10.7188 4.07083 10.0521C4.07083 9.38542 4.18333 8.74376 4.3875 8.13959L1.0625 5.56042C0.383333 6.91042 0 8.43542 0 10.0521C0 11.6688 0.383333 13.1938 1.06667 14.5438L4.3875 11.9646Z" fill="#FBBC05" />
                                    <path d="M10 20.0521C12.7 20.0521 14.9708 19.1646 16.6208 17.6313L13.4 15.1313C12.5042 15.7354 11.35 16.0896 10 16.0896C7.39167 16.0896 5.17917 14.3313 4.3875 11.9604L1.0625 14.5396C2.7125 17.8104 6.09167 20.0521 10 20.0521Z" fill="#34A853" />
                                </svg>
                                ادامه با گوگل
                            </button>
                        </li>
                    </ul>
                </form>
            </div>

            <p class="copyright">
                @ 1402 کلیه حقوق محفوظ است. طراحی و پشتیبانی:
                <a href="#">علم آوران نوین</a>
            </p>
        </main>
        <aside style="background-color: #C61D22">
            <span></span>
            <span></span>
            <span></span>

            <ul class="d-flex flex-column align-items-start justify-content-between">
                <li>
                    <h4>کارتاپ</h4>
                </li>
                <li>
                    <figure>
                        <img src="<?= URL ?>upload/images/12.jpg" alt="" style="border-radius:999px">
                    </figure>
                </li>
                <li>
                    <b>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.923 20C7.21708 20 4.90565 19.0618 2.98869 17.1855C1.07175 15.3092 0.0755171 13.0136 0 10.2987H1.33634C1.42695 12.629 2.2937 14.606 3.93659 16.2297C5.57947 17.8533 7.57494 18.6652 9.923 18.6652C12.3632 18.6652 14.43 17.8115 16.1235 16.1041C17.817 14.3967 18.6637 12.3243 18.6637 9.88688C18.6637 7.49321 17.8109 5.46945 16.1053 3.8156C14.3998 2.16175 12.339 1.33483 9.923 1.33483C8.65611 1.33483 7.46132 1.61236 6.33864 2.16742C5.21595 2.72246 4.23029 3.46604 3.38166 4.39816H6.34882V5.73299H1.04421V0.441178H2.38056V3.49095C3.34846 2.40801 4.48511 1.55545 5.79051 0.933267C7.0959 0.311089 8.47339 0 9.923 0C11.3122 0 12.6168 0.259432 13.8369 0.778297C15.057 1.29714 16.1246 2.00754 17.0396 2.90951C17.9547 3.81147 18.6769 4.86577 19.2061 6.0724C19.7354 7.27903 20 8.5724 20 9.9525C20 11.3401 19.7354 12.6452 19.2061 13.8676C18.6769 15.0901 17.9547 16.1535 17.0396 17.0577C16.1246 17.9619 15.057 18.6783 13.8369 19.207C12.6168 19.7357 11.3122 20 9.923 20ZM13.8052 14.6244L9.37261 10.2443V4.05201H10.709V9.69006L14.7656 13.6652L13.8052 14.6244Z" fill="white" />
                        </svg>
                        آخرین تغییرات
                    </b>
                    <p id="changelog">در حال خواندن آخرین تغییرات...</p>
                </li>
                <li>
                    <svg width="44" height="7" viewBox="0 0 44 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="3.73218" cy="3.5" r="2.875" fill="white" />
                        <rect x="14.6072" y="0.625" width="14.7857" height="5.75" rx="2.875" fill="white" />
                        <circle cx="40.2678" cy="3.5" r="2.875" fill="white" />
                    </svg>
                </li>
            </ul>
        </aside>
    </div>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</body>

</html>
<?php
(new Buffer)->end()
?>