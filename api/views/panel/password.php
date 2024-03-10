<?php
$data = $this->data['data']['data'];
$total = $this->data['data']['total'];
$company = $this->data['company'];
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
                        <div class="table-responsive">
                            <table class="table table-blue table-alternate">
                                <caption></caption>
                                <thead>
                                    <th>نام</th>
                                    <th>شرکت</th>
                                    <th>آدرس اینترنتی</th>
                                    <th>نام کاربری</th>
                                    <th>کلمه عبور</th>
                                    <th>ورود دو مرحله ای</th>
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
                                        <td class="text-center"><?= $value['name'] ?></td>
                                        <td class="text-center"><?= $value['company_name'] ?></td>
                                        <td class="text-center">
                                            <a href="<?= $value['url'] ?>" target="_blank"><?= $value['url'] ?></a>
                                        </td>
                                        <td class="text-center">
                                            <?= (!empty($value['username'])) ? '<span class="badge badge-warning badge-pill ltr">' . $value['username'] . '</span>' : '-'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= (!empty($value['password'])) ? '<span class="badge badge-warning badge-pill ltr">' . $value['password'] . '</span>' : '-'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= ($value['otp']) ? '<span class="badge badge-primary badge-pill ltr">دارد</span>' : '<span class="badge badge-pill badge-danger ltr">ندارد</span>'; ?>
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
                                <fluent-text-field appearance="filled" name="name">نام</fluent-text-field>
                            </div>
                            <div>
                                شرکت: <a href="<?= URL . "panel/company" ?>"><span class="badge badge-pill badge-primary">ویرایش</span></a>
                                <select title="انتخاب کارمند" name="company_id">
                                    <?php
                                    foreach ($company as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>">#<?= $value['id'] . ' > ' . $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="url">آدرس اینترنتی</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="username">نام کاربری</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="password"> کلمه عبور</fluent-text-field>
                            </div>
                            <div>
                                نیاز به ورود دو مرحله ای دارد؟
                                <select title="ورود دو مرحله ای" name="otp">
                                    <option value="0">ندارد</option>
                                    <option value="1">دارد</option>
                                </select>
                            </div>
                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="description">توضیحات</fluent-text-area>
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
        document.querySelector('fluent-text-field[name="name"]').value = result.name
        document.querySelector('select[name="company_id"]').value = result.company_id
        document.querySelector('fluent-text-field[name="url"]').value = result.url
        document.querySelector('fluent-text-field[name="username"]').value = result.username
        document.querySelector('fluent-text-field[name="password"]').value = result.password
        document.querySelector('select[name="otp"]').value = result.otp
        document.querySelector('fluent-text-area[name="description"]').value = result.description
    }
</script>