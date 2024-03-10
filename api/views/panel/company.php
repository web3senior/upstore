<?php
$data = $this->data['data']['data'];
$total = $this->data['data']['total'];
$province = $this->data['province'];
$city = $this->data['city'];
?>
<section>
    <div class="__frame" data-width="large">

        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1) {
        ?>
                <script>
                    Swal.fire({
                        title: 'با موفقیت ثبت شد',
                        icon: 'success',
                        confirmButtonText: 'بستن',
                        showCloseButton: true
                    })
                </script>
        <?php
            } else {
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
            }
        }
        ?>
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
            <div class="card">
                    <div class="card-header">جستجو</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>" method="get" autocomplete="off">
                        <div>
                                <fluent-text-field appearance="filled" name="q_name" value="<?= (isset($_GET['q_name']) ? $_GET['q_name'] : '') ?>">نام شرکت</fluent-text-field>
                            </div>
                            <button appearance="accent" type="submit" id="btn-update">جستجو</button>
                        </form>
                    </div>
                </div>
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
                                    <th>ردیف</th>
                                    <th>نام</th>
                                    <th>شماره ثبت</th>
                                    <th>کد اقتصادی</th>
                                    <th>موبایل</th>
                                    <th>ثبت کننده</th>
                                    <th width="45%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td><?= $value['name'] ?></td>
                                        <td class="text-center"><?= $value['registration_number'] ? '<span class="badge badge-dark">' . $value['registration_number'] . '</span>' : '' ?></td>
                                        <td><?= $value['tax_id'] ?></td>
                                        <td class="text-center"><?= $value['mobile'] ?></td>
                                        <td class="text-center"><span class="badge badge-light"><?= $value['admin_fullname'] ?></span></td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/upload?company_id=<?= $id ?>" class="d-flex align-items-center justify-content-center bage badge-pill badge-warning border border-warning" style="column-gap:.5rem">
                                                    <?= ICON_UPLOAD ?>
                                                    <span>آپلود</span>
                                                </a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/member?company_id=<?= $id ?>" class="d-flex align-items-center justify-content-center bage badge-pill badge-danger border border-danger" style="column-gap:.5rem">
                                                    <?= ICON_USER ?>
                                                    <span>اعضا</span>
                                                </a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/rate?company_id=<?= $id ?>" class="d-flex align-items-center justify-content-center bage badge-pill badge-info border border-info" style="column-gap:.5rem">
                                                    <?= ICON_USER ?>
                                                    <span>رتبه</span>
                                                </a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/expert?company_id=<?= $id ?>" class="d-flex align-items-center justify-content-center bage badge-pill badge-primary border border-primary" style="column-gap:.5rem">
                                                    <?= ICON_USER ?>
                                                    <span>درج مهندس</span>
                                                </a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/view?id=<?= $id ?>"><?= ICON_PREVIEW ?> </a>
                                                <a href="javascript: " onclick="handleEdit(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>"><?= $statusText ?></a>
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

            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12" style="position:sticky;top:0">
                <div class="card">
                    <div class="card-header">عملیات</div>
                    <div class="card-body">
                        <div class="alert alert--primary">
                            بعد از ثبت شرکت، می توانید اعضای شرکت را تعریف نمایید.
                        </div>
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div>
                                <fluent-text-field appearance="filled" name="name">نام شرکت</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="registration_number">شماره ثبت</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="tax_id">کد اقتصادی</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="registration_date">تاریخ ثبت شرکت</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="last_edition">تاریخ آخرین ویرایش</fluent-text-field>
                            </div>
                            <div>
                                <label for="">موبایل</label>
                                <fluent-text-field appearance="filled" name="mobile" dir="ltr"></fluent-text-field>
                            </div>
                            <div>
                                <label for="">تلفن</label>
                                <fluent-text-field appearance="filled" name="tel" dir="ltr"></fluent-text-field>
                            </div>
                            <div>
                                استان <span style="color:red">*</span>:
                                <input type="text" list="province" name="province_id" />
                                <datalist id="province">
                                    <?php
                                    foreach ($province as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div>
                                شهر <span style="color:red">*</span>:
                                <input type="text" list="city" name="city_id" />
                                <datalist id="city">
                                    <?php
                                    foreach ($city as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="address">آدرس</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="postalcode">کد پستی</fluent-text-field>
                            </div>

                            <div>
                                مهر:
                                <input type="hidden" name="stamp_hidden" value="">
                                <input class="form-control" type="file" name="stamp" id="stamp">
                            </div>

                            <div>
                                لوگو:
                                <input type="hidden" name="logo_hidden" value="">
                                <input class="form-control" type="file" name="logo" id="logo">
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
        document.querySelectorAll('form')[1].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
        document.querySelector('fluent-button').innerText = "بروز رسانی"
        document.querySelector('fluent-text-field[name="name"]').value = result.name
        document.querySelector('fluent-text-field[name="registration_number"]').value = result.registration_number
        document.querySelector('fluent-text-field[name="tax_id"]').value = result.tax_id
        document.querySelector('fluent-text-field[name="registration_date"]').value = result.registration_date
        document.querySelector('fluent-text-field[name="last_edition"]').value = result.last_edition
        document.querySelector('fluent-text-field[name="mobile"]').value = result.mobile
        document.querySelector('fluent-text-field[name="tel"]').value = result.tel

        document.querySelector('input[name="province_id"]').value = result.province_id
        document.querySelector('input[name="city_id"]').value = result.city_id

        document.querySelector('fluent-text-field[name="address"]').value = result.address
        document.querySelector('fluent-text-field[name="postalcode"]').value = result.postalcode
        document.querySelector('input[name="stamp_hidden"]').value = result.stamp
        document.querySelector('input[name="logo"]').value = result.logo
        document.querySelector('fluent-text-area[name="description"]').value = result.description
    }
</script>