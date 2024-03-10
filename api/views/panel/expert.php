<?php
$data = $this->data['data']['data'];
$total = $this->data['data']['total'];
$education = $this->data['education'];
$field_study = $this->data['field_study'];
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
                                <fluent-text-field appearance="filled" name="q_fullname" value="<?= (isset($_GET['q_fullname']) ? $_GET['q_fullname'] : '') ?>">نام و نام خانوادگی</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="q_nationalcode" value="<?= (isset($_GET['q_nationalcode']) ? $_GET['q_nationalcode'] : '') ?>">کد ملی</fluent-text-field>
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
                        <a href="<?= URL ?>panel/<?= $this->endpoint ?>/export">
                            دریافت خروجی موبایل مهندسین
                        </a>
                        <div class="table-responsive">
                            <table class="table table-blue table-alternate">
                                <caption></caption>
                                <thead>
                                    <th>ردیف</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th>دارای امتیاز</th>
                                    <th>عکس</th>
                                    <th>موبایل</th>
                                    <th>تلفن</th>
                                    <th>ثبت کننده</th>
                                    <th width="25%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;

                                    if (!is_null($value['score'])) {
                                        $score =   ($value['score'] == 0) ? 'امتیاز آور' : "<math display='inline' style='font-size: 1.2rem'><mfrac><msup><mn>2</mn></msup><mn>3</mn></mfrac></math>";
                                    } else {
                                        $score = 'وارد نشده';
                                    }
                                ?>
                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td>
                                            <p><?= $value['fullname'] ?></p>
                                            <small>
                                                <span class="badge badge-primary badge-pill">
                                                    <?= $value['province_name'] ?> > <?= $value['city_name'] ?>
                                                </span>
                                            </small>
                                        </td>
                                        <td class="text-center"><?= $score ?></td>
                                        <td class="text-center">
                                            <?php if ((empty($value['picture']) || !file_exists('upload/images/' . $value['picture']))) : ?>
                                                <span class="badge badge-dark badge-pill">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['picture'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= $value['mobile'] ?></td>
                                        <td class="text-center"><?= $value['tel'] ?></td>
                                        <td class="text-center"><span class="badge badge-light"><?= $value['admin_fullname'] ?></span></td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/view?id=<?= $id ?>">
                                                    <span class="bage badge-pill badge-primary border border-primary"><?= ICON_PREVIEW ?></span>
                                                </a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/upload?expert_id=<?= $id ?>" class="d-flex align-items-center justify-content-center bage badge-pill badge-warning border border-warning" style="column-gap:.5rem">
                                                    <?= ICON_UPLOAD ?>
                                                    <span>آپلود</span>
                                                    <span class="bage badge-pill badge-dark"><?= $value['expert_upload_count'] ?></span>
                                                </a>
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
                        <div class="alert alert--danger border">
                            بعد از ثبت کاربر اقدام به آپلود مدارک نمایید.
                        </div>
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div>
                                <fluent-text-field appearance="filled" name="fullname">نام و نام خانوادگی</fluent-text-field>
                            </div>
                            <div>
                                <fluent-number-field appearance="filled" name="nationalcode">کد ملی</fluent-text-field>
                            </div>
                            <div>
                                مقطع تحصیلی: <a href="<?= URL . "panel/education" ?>"><span class="badge badge-pill badge-primary">ویرایش</span></a>
                                <select title="انتخاب کارمند" name="education_id">
                                    <?php
                                    foreach ($education as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>">#<?= $value['id'] . ' > ' . $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                رشته تحصیلی: <a href="<?= URL . "panel/field_study" ?>"><span class="badge badge-pill badge-primary">ویرایش</span></a>
                                <select title="انتخاب کارمند" name="field_study_id">
                                    <?php
                                    foreach ($field_study as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>">#<?= $value['id'] . ' > ' . $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                استان:
                                <input list="province" name="province_id">
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
                                شهر:
                                <input list="city" name="city_id">
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
                                <fluent-text-field appearance="filled" name="father_name">نام پدر</fluent-text-field>
                            </div>
                            <div>
                                عکس
                                <input type="hidden" name="picture_hidden" value="">
                                <input class="form-control" type="file" name="picture" id="picture">
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
                                <fluent-text-field appearance="filled" name="address">آدرس</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="office_address">آدرس محل کار</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="representitive">معرف</fluent-text-field>
                            </div>
                            <div>
                                <label for="">تاریخ وکالت</label>
                                <fluent-text-field appearance="filled" name="lawyer_date" dir="ltr" value="<?= jdate('Y-m-d', time(), '', '', 'en') ?>"></fluent-text-field>
                            </div>
                            <div>
                                بیمه:
                                <select title="ورود دو مرحله ای" name="insurance">
                                    <option value="0">ندارد</option>
                                    <option value="1">دارد</option>
                                </select>
                            </div>
                            <div>
                                <fluent-number-field appearance="filled" name="insurance_history">سوابق بیمه</fluent-number-field>
                                بر اساس ماه
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="insurance_pass">رمزبیمه</fluent-text-field>
                            </div>
                            <div>
                                سو پیشینه:
                                <select name="legal_issue">
                                    <option value="0">ندارد</option>
                                    <option value="1">دارد</option>
                                </select>
                            </div>
                            <div>
                                دارای امتیاز:
                                <select name="score">
                                    <option value="0">امتیاز آور</option>
                                    <option value="1">
                                        دو/سوم
                                    </option>
                                    <option value="2">
                                        ساده
                                    </option>
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
        document.querySelectorAll('form')[1].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
        document.querySelector('fluent-button').innerText = "بروز رسانی"
        document.querySelector('[name="fullname"]').value = result.fullname
        document.querySelector('[name="nationalcode"]').value = result.nationalcode
        document.querySelector('[name="education_id"]').value = result.education_id
        document.querySelector('[name="field_study_id"]').value = result.field_study_id
        document.querySelector('[name="province_id"]').value = result.province_id
        document.querySelector('[name="city_id"]').value = result.city_id
        document.querySelector('[name="father_name"]').value = result.father_name
        document.querySelector('[name="picture_hidden"]').value = result.picture
        document.querySelector('[name="mobile"]').value = result.mobile
        document.querySelector('[name="tel"]').value = result.tel
        document.querySelector('[name="address"]').value = result.address
        document.querySelector('[name="office_address"]').value = result.office_address
        document.querySelector('[name="representitive"]').value = result.representitive
        document.querySelector('[name="lawyer_date"]').value = result.lawyer_date
        document.querySelector('[name="insurance"]').value = result.insurance
        document.querySelector('[name="insurance_pass"]').value = result.insurance_pass
        document.querySelector('[name="legal_issue"]').value = result.legal_issue
        document.querySelector('[name="insurance_history"]').value = result.insurance_history
        document.querySelector('[name="score"]').value = result.score
        document.querySelector('[name="description"]').value = result.description

    }
</script>