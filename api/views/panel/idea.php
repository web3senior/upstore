<?php
$data = $this->data["data"]["data"];
$total = $this->data["data"]["total"];
?>
<section>
    <div class="__frame" data-width="large">

        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '<p class="alert alert-success">Record has been added.</p>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="ms-Grid-row">

            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card">
                    <div class="card-header">
                        رکوردها
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <caption>لیست <?= $this->title ?></caption>
                                <thead>
                                      <th> دسته ایده</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th>موبایل</th>
                                    <th>عنوان</th>
                                    <th>توضیحات</th>
                                    <th>تاریخ ثبت</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                ?>
                                    <tr>
                                         <td><?= $value['ideaCategoryName'] ?></td>
                                        <td><?= $value['fullname'] ?></td>
                                        <td><?= $value['phone'] ?></td>
                                        <td><?= $value['title'] ?></td>
                                        <td><?= $value['description'] ?></td>
                                        <td><?= $value['at'] ?></td>
                                        <td><?= $value['status'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <?= (new Paging)->show(PATH_ADMIN . $this->endpoint, $total, $this->pg); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>