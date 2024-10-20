<?php
$data = $this->data['data']['data'];
$total = $this->data['data']['total'];
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
                            <li><?= $this->title ?></li>
                            <li>
                                <a href="javascript: " onclick="location.replace(location.pathname)">
                                    <i class="ms-Icon ms-Icon--Refresh" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="alert alert--warning">
                            موجودیت تاریخ پایان فعالیت به معنی آرشیو اطلاعات کارمند می باشد.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-blue table-alternate">
                                <caption></caption>
                                <thead>
                                    <th>نام و نام خانوادگی</th>
                                    <th>عکس</th>
                                    <th>موبایل</th>
                                    <th>تلفن</th>
                                    <th>سمت</th>
                                    <th>آدرس</th>
                                    <th>تاریخ شروع فعالیت</th>
                                    <th>تاریخ پایان فعالیت</th>
                                    <th>توضیحات</th>
                                    <th width="15%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td><?= $value['fullname'] ?></td>
                                        <td class="text-center">
                                            <?php if ((empty($value['picture']) || !file_exists('upload/images/' . $value['picture']))) : ?>
                                                <span class="badge badge-dark badge-pill">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['picture'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $value['mobile'] ?></td>
                                        <td><?= $value['tel'] ?></td>
                                        <td><?= $value['responsibility'] ?></td>
                                        <td><?= $value['address'] ?></td>
                                        <td class="text-center">
                                            <?= (!empty($value['date_start'])) ? '<span class="badge badge-warning badge-pill ltr">' . $value['date_start'] . '</span>' : '-'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= (!empty($value['date_end'])) ? '<span class="badge badge-warning badge-pill ltr">' . $value['date_end'] . '</span>' : '-'; ?>
                                        </td>
                                        <td><?= $value['description'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="javascript: " onclick="handleEdit(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>"><?= $statusText ?></a>
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

            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12" style="position:sticky;top:0">
                <div class="card">
                    <div class="card-header">عملیات</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div>
                                <fluent-text-field appearance="filled" name="fullname">نام و نام خانوادگی</fluent-text-field>
                            </div>
                            <div>
                                عکس
                                <input type="hidden" name="picture_hidden" value="">
                                <input class="form-control" type="file" name="picture" id="picture">
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="mobile">موبایل</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="tel">تلفن</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="responsibility">سمت</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="address">آدرس</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="date_start">تاریخ شروع فعالیت</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="date_end">تاریخ پایان فعالیت</fluent-text-field>
                            </div>

                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="description">توضیحات اضافه</fluent-text-area>
                            </div>

                            <fluent-button appearance="accent" type="submit" id="btn-update" class="mt-10">اضافه</fluent-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    const handleEdit = async (id) => {
        let result = await window.edit('<?= $this->endpoint ?>', id)
        document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
        document.querySelector('fluent-button').innerText = "بروز رسانی"
        document.querySelector('fluent-text-field[name="fullname"]').value = result.fullname
        document.querySelector('input[name="picture_hidden"]').value = result.picture
        document.querySelector('fluent-text-field[name="mobile"]').value = result.mobile
        document.querySelector('fluent-text-field[name="tel"]').value = result.tel
        document.querySelector('fluent-text-field[name="responsibility"]').value = result.responsibility
        document.querySelector('fluent-text-field[name="address"]').value = result.address
        document.querySelector('fluent-text-field[name="date_start"]').value = result.date_start
        document.querySelector('fluent-text-field[name="date_end"]').value = result.date_end
        document.querySelector('fluent-text-area[name="description"]').value = result.description
    }
</script>