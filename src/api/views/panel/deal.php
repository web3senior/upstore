<?php
$data = $this->data['data']['data'];
$total = $this->data['data']['total'];
$expert = $this->data['expert'];
$department = $this->data['department'];
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
                            <button appearance="accent" type="submit" id="btn-update">جستجو</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <a href="javascript: " onclick="location.replace(location.pathname)">
                            <i class="ms-Icon ms-Icon--Refresh" aria-hidden="true"></i>
                        </a>
                        <?= $this->title ?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-blue table-alternate">
                                <caption></caption>
                                <thead>
                                    <th>ردیف</th>
                                    <th>شماره</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th>تاریخ عقد</th>
                                    <th>مدت قرارداد</th>
                                    <th>امتیاز داده شده</th>
                                    <th>جمع کل</th>
                                    <th>قرارداد</th>
                                    <th>دپارتمان</th>
                                    <th>تخفیف</th>
                                    <th>تاریخ پایان</th>
                                    <th width="20%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td class="text-center"><?= $value['id'] ?></td>
                                        <td><?= $value['expert_fullname'] ?></td>
                                        <td class="text-center"><?= $value['dt']  ?></td>
                                        <td class="text-center"><?= $value['period']  ?></td>
                                        <td class="text-center"><?= $value['score']  ?></td>
                                        <td class="text-center"><?= number_format($value['total'])  ?> ريال</td>
                                        <td class="text-center">
                                            <?php if ((empty($value['file']) || !file_exists('upload/doc/' . $value['file']))) : ?>
                                                <span class="badge badge-dark badge-pill">بدون فایل</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/doc/<?= $value['file'] ?>">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="bage badge-pill badge-warning border border-warning"><?= $value['department_name'] ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="bage badge-pill badge-danger border border-danger"><?= $value['discount'] ?> %</span>
                                        </td>
                                        <td class="text-center">
                                            <?= $value['end_dt'] ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/view?id=<?= $id ?>">
                                                    <span class="bage badge-pill badge-primary border border-primary"><?= ICON_PREVIEW ?></span>
                                                </a>
                                                <a href="javascript: " onclick="handleEdit(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_SMS ?></a>
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
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div>
                            مهندس: <a href="<?= URL . "panel/expert" ?>"><span class="badge badge-pill badge-primary">ویرایش</span></a>
                               
                                <input type="text" list="province" name="expert_id" title="انتخاب کارمند" required/>
                                <datalist id="province">
                                    <?php
                                    foreach ($expert as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?=$value['fullname']?></option>
                                    <?php
                                    }
                                    ?>
                                </datalist>
                            </div>
                            <div>
                                ارجاع به:
                                <select title="انتخاب دپارتمان" name="department_id">
                                    <?php
                                    foreach ($department as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>">#<?= $value['id'] . ' > ' . $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="">تاریخ عقد قرارداد</label>
                                <fluent-text-field appearance="filled" name="dt" dir="ltr" value="<?= jdate('Y-m-d', time(), '', '', 'en') ?>"></fluent-text-field>
                            </div>
                            <div>
                                <label for="">مدت قرارداد <small>بر اساس ماه</small></label>
                                <fluent-number-field appearance="filled" name="period" dir="ltr"></fluent-text-field>
                            </div>
                            <div>
                                <label for="">امتیاز داده شده</label>
                                <fluent-number-field appearance="filled" name="score" dir="ltr"></fluent-text-field>
                            </div>
                            <div>
                                <fluent-number-field appearance="filled" name="total">جمع کل مبلغ قرارداد</fluent-text-field>
                            </div>
                            <div>
                                فایل قرارداد
                                <input type="hidden" name="file_hidden" value="">
                                <input class="form-control" type="file" name="file" id="file">
                            </div>

                            <div class="mt-30">
                                <textarea name="description"></textarea>
                            </div>
                            <div>
                                <label for="">درصد تخفیف</label>
                                <fluent-number-field appearance="filled" name="discount" dir="ltr"></fluent-text-field>
                            </div>
                            <div>
                                <label for="">تاریخ پایان</label>
                                <fluent-text-field appearance="filled" name="end_dt" dir="ltr" value="<?= jdate('Y-m-d', time(), '', '', 'en') ?>"></fluent-text-field>
                            </div>

                            <div class="mt-30">
                                <textarea name="content"></textarea>
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
    ClassicEditor
        .create(document.querySelector('[name="description"]'), {
            placeholder: 'توضیحات اضافه'
        })
        .then(editor => {
            window.editor = editor
        })
        .catch((e) => console.log)

    ClassicEditor
        .create(document.querySelector('[name="content"]'), {
            placeholder: ''
        })
        .then(editor => {
            window.contentEditor = editor
        })
        .catch((e) => console.log)

    const handleEdit = async (id) => {
        let result = await window.edit('<?= $this->endpoint ?>', id)
        document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
        document.querySelector('fluent-button').innerText = "بروز رسانی"
        document.querySelector('[name="expert_id"]').value = result.expert_id
        document.querySelector('[name="dt"]').value = result.dt
        document.querySelector('[name="total"]').value = result.total
        document.querySelector('[name="period"]').value = result.period
        document.querySelector('[name="score"]').value = result.score
        document.querySelector('[name="discount"]').value = result.discount
        document.querySelector('[name="end_dt"]').value = result.end_dt
        document.querySelector('input[name="file_hidden"]').value = result.file
        editor.setData(result.description)
        contentEditor.setData(result.content)
    }
</script>