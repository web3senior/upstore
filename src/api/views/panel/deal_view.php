<?php
$data = $this->data['deal'][0];
?>
<section>
    <div class="__frame" data-width="large">
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card infoCard">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li>اطلاعات قرارداد</li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <ul class="d-flex flex-column justify-content-between align-items-start" style="row-gap: 1rem">
                            <li>
                                شناسه: <span class="badge badge-pill badge-danger"><?= $data['id'] ?></span>
                            </li>
                            <li>
                                <span>مهندس:</span>
                                <a href="<?= URL ?>panel/expert/view?id=<?= $data['expert_id'] ?>" target="_blank">مشاهده اطلاعات مهندس با شناسه: <?= $data['expert_id'] ?></a>
                            </li>
                            <li>
                                <span>تاریخ عقد:</span>
                                <span> <?= $data['dt'] ?></span>
                            </li>
                            <li>
                                <span>مبلغ کل:</span>
                                <span><?= $data['total'] ?></span>
                            </li>
                            <li>
                                <span>فایل قرارداد:</span>
                                <?php if ((empty($data['file']) || !file_exists('upload/doc/' . $data['file']))) : ?>
                                    <span class="badge badge-dark badge-pill">درج نشده</span>
                                <?php else : ?>
                                    <a target="_blank" href="<?= URL ?>upload/doc/<?= $data['file'] ?>">
                                        <?= ICON_OPEN ?>
                                    </a>
                                <?php endif; ?>
                            </li>
                            <li>
                                <span>توضیحات:</span>
                                <span><?= $data['description'] ?></span>
                            </li>
                        </ul>
                    </div>
                </div>


                <div class="card printable">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li>متن قرارداد</li>
                            <li>پرینت</li>
                        </ul>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">

                        <div class="print-content">
                            <div class="d-flex flex-column align-items-start justify-content-start">
                                <span>تاریخ: <?= $data['dt'] ?></span>
                                <span>شماره قرارداد: <?= jdate('Y', time()) . '/' . $data['id'] ?></span>
                            </div>
                           
                            <?=$data['content']?>

                            <div style="
                            position: absolute;
                            bottom: 1rem;
                            left: 0;
                            padding: 0 1rem;
                            direction: ltr;
                            ">
                                <i class="ms-Icon ms-Icon--Phone" aria-hidden="true" style="color:var(--primary-color)"></i>
                                <span>
                                    09143521190
                                    09146654404
                                    04533780910
                                </span>
                            </div>

                            <div style="
                            position: absolute;
                            top: -.5rem;
                            left: 0;
                            padding: 0 ;
                            direction: ltr;
                            background: var(--primary-color);
                            border-bottom-right-radius: 9px;
                            padding: 0.4rem .5rem 0 .5rem;
                            font-family: sans-serif;
                            color: #fff;
                            ">
                                <span>www.kartopco.ir</span>
                            </div>

                            <p style="
                            position: absolute;
                            bottom: 1rem;
                            left: 0;
                            right: 0;
                            padding: 0 1rem;
                            ">
                                <i class="ms-Icon ms-Icon--Pinned" aria-hidden="true" style="color:var(--primary-color)"></i>
                                اردبیل،کارشناسان،میدان وصال ساختمان سرو طبقه 9 واحد C
                            </p>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </div>
</section>

<style>
    @page {
  size: A4;
}

@page :left {
  margin-left: 1cm;
}

@page :right {
  margin-left: 1cm;
}

@media print {
  header,nav,button,
  .infoCard,
  .printable .card-header {
    display: none !important;
  }
}

    .print-content {
        position: relative;
        width: 210mm;
        height: 297mm;
        line-height: 25px;
        text-align: justify;
        padding: .5rem 1rem;
        border: 2px solid var(--primary-color);
        border-radius: var(--global-border-radius);
        overflow: hidden;
    }

    .print-content::after {
        content: '';
        position: absolute;
        inset: 0;
        opacity: .1;
        background: url('<?= URL ?>logo.svg');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 80%;
    }


    ul {
        column-gap: 1rem;
    }

    .print-content ul li {
        direction: ltr;
        text-align: center;
        flex: 1;
        padding: 1rem 0;
        background-color: #fafafa;
    }
</style>