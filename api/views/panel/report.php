<?php
$data = $this->data['data'];
$education = $this->data['education'];
$province = $this->data['province'];
$city = $this->data['city'];
?>
<section>
    <div class="__frame" data-width="small">
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li>چندین شرکت در هر رتبه وجود دارد؟</li>
                            <li>
                                <a href="javascript: " onclick="location.replace(location.pathname)">
                                    <i class="ms-Icon ms-Icon--Refresh" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <ul class="d-flex flex-column align-items-center justify-content-center">
                            <?php
                            foreach ($data as $key => $value) {
                            ?>
                                <li class="d-flex flex-row align-items-center justify-content-between" style="width:100%;">
                                    <span><?= $value['name'] ?></span>
                                    <span><?= $value['total'] ?></span>
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