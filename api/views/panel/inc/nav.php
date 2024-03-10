<?php
$nav = [
    ['ViewDashboard', 'dashboard', 'پیشخوان'],
    ['UserGauge', 'expert', 'مهندسین'],
    ['SchoolDataSyncLogo', 'client', 'مشتریان'],
    ['Work', 'company', 'شرکت ها'],
    ['Org', 'deal', 'قرارداد م'],
    ['Org', 'deal2', 'قرارداد پ'],
    ['CRMServices', 'service', 'خدمات'],
    ['Clock', 'timeline', 'زمانبندی'],
    ['AccessLogo', 'access', 'دسترسی'],
    ['Sections', 'department', 'دپارتمان'],
    ['AreaChart', 'report', 'گزارش گیری'],
    ['Money', 'accounting', 'حسابداری'],
    ['Compare', 'legaltype', 'حقوقی'],
    ['Teamwork', 'employee', 'کارمندان'],
    ['Lock', 'password', 'گذرواژه ها'],
    ['Settings', 'setting', 'تنظیمات'],
];
$collapse = ((new Cookie)->get('navCollapse'));
?>


<a href="<?= URL ?>" class="logo-link">
    <figure>
        <img alt='logo' src="<?= URL ?>logo.svg" draggable="false" className='animate fade' />
    </figure>
</a>

<ul>
    <?php
    foreach ($nav as $key => $value) {
    ?>
        <li className='animate blur'>
            <a id="<?= $value[1] ?>" class="nav-link" href="<?= URL . "panel/" . $value[1] ?>">
                <i class="ms-Icon ms-Icon--<?= $value[0] ?>" aria-hidden="true"></i>
                <span><?= $value[2] ?></span>
            </a>
        </li>
    <?php
    }
    ?>
</ul>

<a id="<?= $val[0] ?>" class="nav-link logout-link" href="<?= URL . "panel/logout" ?>">
    <i class="goldtext ms-Icon ms-Icon--SignOut" aria-hidden="true"></i>
    <span>خروج</span>
</a>

<figure class="support" onclick="alert(`لطفا با شرکت تماس بگیرید`)">
    <span>کمک میخوای؟</span>
 <img src="./../upload/images/davod.png">   
</figure>

<style>
    .support {
        position: absolute;
        bottom: -8rem; 
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        transition: bottom .5s, color 1s;
        text-align: center;
    }
    .support span{
        background-color: #212121;
        padding: .1rem .5rem;
        border-radius: 999px;
        font-size: 10px;
    }
</style>