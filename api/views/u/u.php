<?php
(new Buffer)->start();
$config = $this->data['config'][0];
$profile = $this->data['profile']->LSP3Profile;

// Filter links
$links = array_filter(json_decode($config['links']), function ($obj) {
    if (isset($obj->status)) {
        if (!$obj->status) return false;
    }
    return true;
});
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@<?= $this->title . ' | ' . NAME ?> </title>
    <meta name="keywords" content="<?= implode(',', $profile->tags) ?>" />
    <meta name="author" content="<?= AUTHOR ?>" />
    <meta name="robots" content="index, follow" />
    <meta name="theme-color" content="<?= json_decode($config['style'])->buttonBackgroundColor ?? '#fff' ?>" />

    <!-- OG -->
    <meta name="og:title" content="<?= $config['username'] ?>" />
    <meta name="og:description" content="<?= $profile->description ?? '' ?>" />
    <meta name="og:type" content="website" />
    <meta name="og:url" content="<?= URL . 'u/' . $config['username'] ?>" />
    <meta name="og:image" content="<?= "https://og.tailgraph.com/og?fontFamily=Roboto&title=@" . urlencode($config['username']) . "&titleTailwind=font-bold%20text-6xl%20text-pink-500&text=" . $config['wallet_addr'] . "&textTailwind=text-2xl%20mt-4%20text-cyan-500%20text-xs&logoUrl=" . "https://api.universalprofile.cloud/ipfs/" .  str_replace('ipfs://', '', $profile->profileImage[0]->url) . "&logoTailwind=h-8%20border-4%20bg-transparent%20border-rose-400&bgTailwind=bg-white&footer=universallink.me&footerTailwind=text-lightBlue-500%20bg-blueGray-800&containerTailwind=bg-transparent&t=1707768092187&refresh=1" ?>" />
    <meta name="og:image:alt" content="<?= NAME ?> Frame" />

    <!-- Profile -->
    <meta name="profile:username" content="<?= $config['username'] ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="<?= URL . 'u/' . $config['username'] ?>" />
    <meta name="twitter:title" content="<?= NAME ?>" />
    <meta name="twitter:description" content="<?= $profile->description ?? '' ?>" />
    <meta name="twitter:image" content="<?= "https://og.tailgraph.com/og?fontFamily=Roboto&title=@" . urlencode($config['username']) . "&titleTailwind=font-bold%20text-6xl%20text-pink-500&text=" . $config['wallet_addr'] . "&textTailwind=text-2xl%20mt-4%20text-cyan-500%20text-xs&logoUrl=" . "https://api.universalprofile.cloud/ipfs/" .  str_replace('ipfs://', '', $profile->profileImage[0]->url) . "&logoTailwind=h-8%20border-4%20bg-transparent%20border-rose-400&bgTailwind=bg-white&footer=universallink.me&footerTailwind=text-lightBlue-500%20bg-blueGray-800&containerTailwind=bg-transparent&t=1707768092187&refresh=1" ?>" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,1,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;300;500;700;900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?= URL ?>dist/u.min.css?v=1">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root {
            --color-background: <?= json_decode($config['style'])->backgroundColor ?? '#F9F9FB' ?>;
            --color-text: <?= json_decode($config['style'])->textColor ?? '#212121' ?>;
            --color-button-background: <?= json_decode($config['style'])->buttonBackgroundColor ?? '#fff' ?>;
            --color-shadow: <?= json_decode($config['style'])->shadowColor ?? 'rgba(0, 0, 0, 0.25)' ?>;
            --border-radius: <?= json_decode($config['style'])->borderRadius ?? '0' ?>;
        }
    </style>

    <script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "kz33l4w9mr");
    </script>
</head>

<body data-base-url="<?= URL ?>" data-user-wallet-address="<?= $config['wallet_addr'] ?>" data-base-url="<?= URL ?>" data-api-url="<?= URL . 'v1/' ?>">
    <?php require_once "loading.php" ?>

    <div id="root">
        <div id="page" class="page">
            <div class="__container container d-flex flex-column align-items-center justify-content-between text-center" data-width="medium">
                <header class="d-flex flex-column align-items-center justify-content-start text-center">
                    <figure class="animate__animated animate__fadeInDown">
                        <?php
                        if ($profile->profileImage[0]->url) :
                        ?>
                            <img src="https://api.universalprofile.cloud/ipfs/<?= str_replace('ipfs://', '', $profile->profileImage[0]->url) ?>">
                        <?php
                        endif;
                        ?>
                    </figure>

                    <ul class="name d-flex flex-row align-items-center justify-content-start text-center">
                        <li>
                            <h5><?= $config['username'] ?></h5>
                        </li>
                        <li title={`Verified`}>
                            <?php if ($config['username'] === 'atenyun' || $config['username'] === 'tantodefi.eth') : ?>
                                <img src="<?= URL ?>public/pink-checkmark.svg" />
                            <?php endif; ?>
                        </li>
                    </ul>

                    <p class="description"><?= $profile->description ?></p>

                    <ul class="tags d-flex flex-row align-items-center justify-content-start">
                        <?php
                        foreach ($profile->tags as $key => $value) {
                        ?>
                            <li class="animate__animated animate__fadeInUp" style="--animate-duration:<?= (++$key * 0.2) ?>s">
                                <span class="tag"><?= $value ?></span>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </header>

                <main class="d-flex flex-column align-items-center justify-content-start">
                    <div class="social">
                        <?php
                        foreach ($links as $key => $value) {
                            $title = strtolower($value->title);
                            if ($title === "tg" || $title === "telegram") :
                        ?>
                                <a href="<?= $value->url ?>" target="_blank">
                                    <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_312_449)">
                                            <path d="M13.4524 26C20.6321 26 26.4524 20.1797 26.4524 13C26.4524 5.8203 20.6321 0 13.4524 0C6.27269 0 0.452393 5.8203 0.452393 13C0.452393 20.1797 6.27269 26 13.4524 26Z" fill="url(#paint0_linear_312_449)" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.33699 12.8628C10.1268 11.2117 12.6539 10.1232 13.9183 9.59723C17.5285 8.0956 18.2787 7.83475 18.7677 7.82614C18.8752 7.82425 19.1157 7.8509 19.2714 7.97728C19.4029 8.084 19.4391 8.22816 19.4564 8.32934C19.4738 8.43052 19.4953 8.66101 19.4782 8.84111C19.2825 10.8967 18.436 15.8851 18.0053 18.1874C17.8231 19.1616 17.4643 19.4883 17.1169 19.5203C16.362 19.5897 15.7887 19.0213 15.0575 18.542C13.9134 17.792 13.267 17.3251 12.1564 16.5932C10.8729 15.7474 11.7049 15.2826 12.4364 14.5228C12.6278 14.324 15.954 11.2985 16.0184 11.0241C16.0265 10.9897 16.034 10.8618 15.9579 10.7942C15.8819 10.7267 15.7697 10.7498 15.6888 10.7681C15.574 10.7942 13.7462 12.0023 10.2053 14.3925C9.68648 14.7488 9.21655 14.9224 8.79551 14.9133C8.33134 14.9032 7.43847 14.6508 6.77471 14.4351C5.96058 14.1704 5.31353 14.0305 5.36988 13.5811C5.39922 13.347 5.7216 13.1075 6.33699 12.8628Z" fill="white" />
                                        </g>
                                        <defs>
                                            <linearGradient id="paint0_linear_312_449" x1="1300.45" y1="0" x2="1300.45" y2="2580.72" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#2AABEE" />
                                                <stop offset="1" stop-color="#229ED9" />
                                            </linearGradient>
                                            <clipPath id="clip0_312_449">
                                                <rect x="0.452393" width="26" height="26" rx="13" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                        <?php
                            endif;
                        }
                        ?>

                        <?php
                        foreach ($links as $key => $value) {
                            $title = strtolower($value->title);
                            if ($title === "cg" || $title === "common ground") :
                        ?>
                                <a href="<?= $value->url ?>" target="_blank">
                                    <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.785645" width="26" height="26" rx="13" fill="#404BBB" />
                                        <g clip-path="url(#clip0_312_455)">
                                            <path d="M14.0675 20.0182C17.732 20.0182 20.7028 17.0475 20.7028 13.3829C20.7028 9.7183 17.732 6.74756 14.0675 6.74756C10.4029 6.74756 7.43213 9.7183 7.43213 13.3829C7.43213 17.0475 10.4029 20.0182 14.0675 20.0182Z" stroke="white" stroke-width="0.956586" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9965 6.89685C10.4145 6.89685 7.51074 9.80063 7.51074 13.3826C7.51074 16.9646 10.4145 19.8684 13.9965 19.8684H14.0674V6.89685H13.9965ZM8.43348 12.9339C9.0218 12.2818 9.77086 11.748 10.6272 11.5708C11.5005 11.3901 12.4359 11.5909 13.3493 12.3194L12.8722 12.9177C12.1192 12.3173 11.412 12.1899 10.7823 12.3202C10.1357 12.454 9.52136 12.8705 9.00169 13.4465L8.43348 12.9339Z" fill="white" />
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M17.5817 11.5705C18.4381 11.7478 19.1871 12.2815 19.7755 12.9337L19.2073 13.4463C18.6876 12.8703 18.0733 12.4538 17.4267 12.32C16.797 12.1896 16.0897 12.317 15.3367 12.9174L14.8596 12.3191C15.7731 11.5907 16.7085 11.3899 17.5817 11.5705Z" fill="white" />
                                            <path d="M10.1226 13.5859L14.1116 20.4951H6.13354L10.1226 13.5859Z" fill="white" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_312_455">
                                                <rect width="15.2481" height="14.8571" fill="white" transform="translate(6.16162 5.57141)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                        <?php
                            endif;
                        }
                        ?>
                        <?php
                        foreach ($links as $key => $value) {
                            $title = strtolower($value->title);
                            if ($title === "x" || $title ===  "twitter" || $title ===  "𝕏") :
                        ?>
                                <a href="<?= $value->url ?>" target="_blank">
                                    <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="0.119141" width="26" height="26" rx="13" fill="white" />
                                        <path d="M17.3913 6.5H19.6695L14.6923 12.0067L20.5476 19.5H15.963L12.3721 14.9553L8.26338 19.5H5.98381L11.3074 13.61L5.69043 6.5H10.3914L13.6372 10.654L17.3913 6.5ZM16.5917 18.18H17.8541L9.7055 7.75067H8.35085L16.5917 18.18Z" fill="black" />
                                    </svg>
                                </a>
                        <?php
                            endif;
                        }
                        ?>
                        <div class="btn-copy" data-copy="<?= URL . 'u/' . $config['username'] ?>">
                            <svg width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.452393" width="26" height="26" rx="13" fill="white" />
                                <path d="M11.4705 17.6658C11.0606 17.6658 10.7137 17.5238 10.4297 17.2398C10.1457 16.9558 10.0036 16.6088 10.0036 16.1989V6.96089C10.0036 6.55099 10.1457 6.20404 10.4297 5.92003C10.7137 5.63602 11.0606 5.49402 11.4705 5.49402H18.2742C18.6841 5.49402 19.031 5.63602 19.315 5.92003C19.599 6.20404 19.7411 6.55099 19.7411 6.96089V16.1989C19.7411 16.6088 19.599 16.9558 19.315 17.2398C19.031 17.5238 18.6841 17.6658 18.2742 17.6658H11.4705ZM11.4705 16.4486H18.2742C18.3366 16.4486 18.3938 16.4226 18.4459 16.3706C18.4979 16.3186 18.5239 16.2614 18.5239 16.1989V6.96089C18.5239 6.89846 18.4979 6.84123 18.4459 6.7892C18.3938 6.73719 18.3366 6.71118 18.2742 6.71118H11.4705C11.4081 6.71118 11.3508 6.73719 11.2988 6.7892C11.2468 6.84123 11.2208 6.89846 11.2208 6.96089V16.1989C11.2208 16.2614 11.2468 16.3186 11.2988 16.3706C11.3508 16.4226 11.4081 16.4486 11.4705 16.4486ZM8.63042 20.5059C8.22054 20.5059 7.8736 20.3639 7.58959 20.0798C7.30558 19.7958 7.16357 19.4489 7.16357 19.039V8.5838H8.38074V19.039C8.38074 19.1014 8.40674 19.1587 8.45876 19.2107C8.51079 19.2627 8.56801 19.2887 8.63042 19.2887H16.6513V20.5059H8.63042Z" fill="black" />
                            </svg>
                        </div>
                    </div>

                    <ul class="d-flex flex-column align-items-center justify-content-center w-100">
                        <?php
                        foreach ($links as $key => $value) {
                            $title = $value->title;
                            if (array_search(strtolower($value->title), ["cg", "telegram", "x", "twitter", "𝕏", "common ground"]) === false) :
                        ?>
                                <li key={i} style="--animate-duration:<?= (++$key * 0.2) ?>s" data-username="<?= $config['username'] ?>" data-event="click" data-title="<?= $title ?>" id="link" class="d-flex flex-column align-items-center justify-content-start text-center animate__animated animate__fadeInUp">
                                    <a href="<?= $value->url ?>" class="link" target="_blank">
                                        <?= $title ?>
                                    </a>
                                </li>
                        <?php
                            endif;
                        }
                        ?>
                        <li style="--animate-duration:<?= ((count($links) + 1)  * 0.2) ?>s" class="d-flex flex-column align-items-center justify-content-start text-center animate__animated animate__fadeIn">
                            <a href="#" class="link tipLyx">4.2 $LYX</a>
                        </li>
                    </ul>

                    <?php
                    if ($config['telegramId'] !== null) :
                    ?>
                        <div id="audioHold" class="mt-40 card">
                            <b>Send voice mail to <?= $config['username'] ?></b>
                            <button class="btn-voic3-start">
                                <img src="./../public/voic3-icon.svg" alt="voic3 icon">
                            </button>
                            <div id="isRecording"></div>
                            <ul class="d-flex flex-row align-items-center justify-content-center">
                                <li>
                                    <audio controls src="" id="audioElement" class=" d-none"></audio>
                                </li>
                                <li>
                                    <button class="btn-voic3-stop  d-none btn">
                                        Stop
                                    </button>
                                </li>
                                <li>
                                    <button class="btn-voic3-send d-none btn">
                                        Send
                                    </button>
                                </li>
                            </ul>
                        </div>
                    <?php
                    endif;
                    ?>
                </main>

                <?php require_once "footer.php" ?>
            </div>
        </div>
    </div>
    <script src="<?= URL ?>dist/u.bundle.js" type="module"></script>
</body>

</html>
<?php
(new Buffer)->end()
?>