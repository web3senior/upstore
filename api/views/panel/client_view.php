<?php
$data = $this->data['client'][0];
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
                            <li>اطلاعات کاربر</li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <ul class="d-flex flex-column justify-content-between align-items-start" style="row-gap: 1rem">
                            <li>
                                شناسه: <span class="badge badge-pill badge-danger"><?= $data['id'] ?></span>
                            </li>
                            <li>
                                <span>نام و نام خانوادگی:</span>
                                <span> <?= $data['fullname'] ?></span>
                            </li>
                            <li>
                                <span>نام پدر:</span>
                                <span> <?= $data['father_name'] ?></span>
                            </li>
                            <li>
                                <span>تلفن:</span>
                                <span><?= $data['tel'] ?></span>
                            </li>
                            <li>
                                <span>موبایل:</span>
                                <span><?= $data['mobile'] ?></span>
                            </li>
                            <li>
                                <span>آدرس:</span>
                                <span><?= $data['address'] ?></span>
                            </li>
                            <li>
                                <span>معرف:</span>
                                <span><?= $data['representitive'] ?></span>
                            </li>
                            <li>
                                <span>تاریخ درج در سیستم:</span>
                                <span dir="ltr"><?= jdate('Y-m-d H:i:s', strtotime($data['dt']), '', '', 'en') ?></span>
                            </li>
                            <li>
                                <span>بیمه:</span>
                                <span><?= ($data['insurance']) ? 'دارد' : 'ندارد' ?></span>
                            </li>
                            <li>
                                <span>سوابق بیمه:</span>
                                <span>
                                    <?= $data['insurance_history'] ?>
                                    <span class="badge badge-info">ماه</span>
                                </span>
                            </li>
                            <li>
                                <span>سو پیشینه:</span>
                                <span><?= ($data['legal_issue']) ? 'دارد' : 'ندارد' ?></span>
                            </li>
                            <li>
                                <span>توضیحات:</span>
                                <span><?= ($data['description']) ? $data['description'] : 'ندارد' ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>