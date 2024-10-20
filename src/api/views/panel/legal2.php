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
                                    <th>ردیف</th>
                                    <th>موضوع</th>
                                    <th>شماره پرونده</th>

                                    <th>خواهان</th>
                                    <th>خوانده</th>
                                    <th>خواسته</th>
                                    <th>شرکت مربوطه</th>

                                    <th>تاریخ درخواست اجرای رای داوری</th>
                                    <th>تاریخ صدور اجرائیه</th>
                                    <th>تاریخ ارسال به اجرای احکام</th>
                                    <th width="15%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td><?= ++$key ?></td>
                                        <td><?= $value['title'] ?></td>
                                        <td><?= $value['case_number'] ?></td>

                                        <td><?= $value['khahan'] ?></td>
                                        <td><?= $value['khandeh'] ?></td>
                                        <td><?= $value['khasteh'] ?></td>
                                        <td><?= $value['company_name'] ?></td>

                                        <td><?= $value['request_dt'] ?></td>
                                        <td><?= $value['execution_dt'] ?></td>
                                        <td class="text-center"><span class="badge badge-danger badge-pill ltr"><?= $value['send_dt'] ?></span></td>
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
                                <fluent-text-field appearance="filled" name="title">موضوع</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="case_number">شماره پرونده</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="request_dt" value="<?= jdate('Y-m-d', time()) ?>">تاریخ درخواست اجرای رای داوری</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="khahan">خواهان</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="khandeh">خوانده</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="khasteh">خواسته</fluent-text-field>
                            </div>
                            <div>
                                شرکت: <a href="<?= URL . "panel/company" ?>"><span class="badge badge-pill badge-primary">ویرایش</span></a>
                                <select id="company" name="company_id">
                                    <?php
                                    foreach ($company as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="court">دادگاه</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="execution_dt">تاریخ صدور اجرائیه</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="send_dt">تاریخ ارسال به اجرای احکام</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="follow">پیگیری اجرای احکام</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="proceedings">اقدامات اجرای احکام</fluent-text-field>
                            </div>
                            <div>
                                <textarea appearance="filled" name="content"></textarea>
                            </div>
                            <div>
                                <fluent-text-area appearance="filled" name="description">توضیحات</fluent-text-area>
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
        .create(document.querySelector('[name="content"]'), {
            placeholder: 'متن درخواست'
        })
        .then(editor => {
            window.contentEditor = editor
        })
        .catch((e) => console.log)

    const handleEdit = async (id) => {
        let result = await window.edit('<?= $this->endpoint ?>', id)
        document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
        document.querySelector('fluent-button').innerText = "بروز رسانی"
        document.querySelector('[name="title"]').value = result.title
        document.querySelector('[name="case_number"]').value = result.case_number
        document.querySelector('[name="description"]').value = result.description
        document.querySelector('[name="khahan"]').value = result.khahan
        document.querySelector('[name="khasteh"]').value = result.khasteh
        document.querySelector('[name="request_dt"]').value = result.request_dt
        document.querySelector('[name="court"]').value = result.court
        document.querySelector('[name="execution_dt"]').value = result.execution_dt
        document.querySelector('[name="send_dt"]').value = result.send_dt
        document.querySelector('[name="follow"]').value = result.follow
        document.querySelector('[name="proceedings"]').value = result.proceedings
        contentEditor.setData(result.content)
    }
</script>