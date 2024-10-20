<?php
$data = $this->data['data'][0];
?>
<section>
    <div class="__frame" data-width="large">
        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '<p class="alert alert-success">اضافه شد</p>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li>اطلاعات شرکت</li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <ul class="d-flex flex-column justify-content-between align-items-start" style="row-gap: 1rem">
                            <li>
                                شناسه: <span class="badge badge-pill badge-danger"><?= $data['id'] ?></span>
                            </li>
                            <li>
                                <span>نام:</span>
                                <span> <?= $data['name'] ?></span>
                            </li>
                            <li>
                                <span>شماره ثبت:</span>
                                <span><?= $data['registration_number'] ?></span>
                            </li>
                            <li>
                                <span>کد اقتصادی:</span>
                                <span><?= $data['tax_id'] ?></span>
                            </li>
                            <li>
                                <span>تاریخ ثبت شرکت:</span>
                                <span><?= $data['registration_date'] ?></span>
                            </li>
                            <li>
                                <span>استان:</span>
                                <span><?= $data['province_name'] ?></span>
                            </li>
                            <li>
                                <span>شهر:</span>
                                <span><?= $data['city_name'] ?></span>
                            </li>
                            <li>
                                <span>آدرس:</span>
                                <span><?= $data['address'] ?></span>
                            </li>
                            <li>
                                <span>کدپستی:</span>
                                <span><?= $data['postalcode'] ?></span>
                            </li>
                            <li>
                                <span>نمونه مهر:</span>

                                <?php if ((empty($data['stamp']) || !file_exists('upload/images/' . $data['stamp']))) : ?>
                                    <span class="badge badge-dark badge-pill">بدون تصویر</span>
                                <?php else : ?>
                                    <a target="_blank" href="<?= URL ?>upload/images/<?= $data['stamp'] ?>" class="btn-more">
                                        <figure>
                                            <img src="<?= URL ?>upload/images/<?= $data['stamp'] ?>" style="width: 120px;" />
                                        </figure>
                                    </a>
                                <?php endif; ?>

                            </li>
                            <li>
                                <span>لوگو:</span>

                                <?php if ((empty($data['logo']) || !file_exists('upload/images/' . $data['logo']))) : ?>
                                    <span class="badge badge-dark badge-pill">بدون تصویر</span>
                                <?php else : ?>
                                    <a target="_blank" href="<?= URL ?>upload/images/<?= $data['logo'] ?>" class="btn-more">
                                        <figure>
                                            <img src="<?= URL ?>upload/images/<?= $data['logo'] ?>" style="width: 120px;" />
                                        </figure>
                                    </a>
                                <?php endif; ?>

                            </li>
                            <li>
                                <span>تاریخ درج در سیستم:</span>
                                <span dir="ltr"><?= jdate('Y-m-d H:i:s', strtotime($data['dt']), '', '', 'en') ?></span>
                            </li>
                            <li>
                                <span>توضیحات:</span>
                                <div><?= $data['description'] ?></div>
                            </li>
                            <li>
                                <span>وضعیت:</span>
                                <span><?= $data['status'] ? 'فعال' : 'غیرفعال' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>