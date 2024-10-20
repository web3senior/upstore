<?php
$data = $this->data['expert'][0];
$expertInCompany = $this->data['expertInCompany'];
?>
<section>
    <div class="__frame" data-width="large">
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
                                <span>دارای امتیاز:</span>
                                <span>
                                    <?php
                                    if (!is_null($data['score'])) {
                                        echo $score =   ($data['score'] == 0) ? 'امتیاز آور' : "<math display='inline' style='font-size: 1.2rem'><mfrac><msup><mn>2</mn></msup><mn>3</mn></mfrac></math>";
                                    } else {
                                        echo $score = 'وارد نشده';
                                    }
                                    ?>
                                </span>
                            </li>
                            <li>
                                <span>معرف:</span>
                                <span><?= $data['representitive'] ?></span>
                            </li>


                            <li>
                                <span>استان:</span>
                                <span><?= $data['province_name'] ?></span>
                            </li>
                            <li>
                                <span>شهر:</span>
                                <span><?= $data['province_name'] ?></span>
                            </li>


                            <li>
                                <span>تاریخ وکالت:</span>
                                <span dir="ltr"><?= $data['lawyer_date'] ?></span>
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
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        شرکت های متصل
                    </div>
                    <div class="card-body">
                        <ul class="d-flex flex-column align-items-center justify-content-center">
                            <?php
                            foreach ($expertInCompany as $key => $value) {
                            ?>
                                <li class="d-flex flex-row align-items-center justify-content-between" style="width:100%;">
                                    <span>#<?= $value['company_id'] ?>) <?= $value['name'] ?></span>
                                </li>

                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>