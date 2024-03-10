<?php
$data = $this->data['data'][0];
$upload = $this->data['upload'];
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
                            <li>اطلاعات شرکت</li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <ul class="d-flex flex-column justify-content-between align-items-start">
                            <li>
                                شناسه: <span class="badge badge-pill badge-danger"><?= $data['id'] ?></span>
                            </li>
                            <li>
                                نام شرکت: <?= $data['name'] ?>
                            </li>
                            <li>
                                تلفن: <?= $data['tel'] ?>
                            </li>
                            <li>
                                موبایل: <?= $data['mobile'] ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li><?= $this->title ?></li>
                            <li>
                                <a href="javascript: " onclick="location.replace(location.href)">
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
                                    <th>نام فایل</th>
                                    <th>مشاهده</th>
                                    <th>تاریخ درج</th>
                                    <th width="15%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($upload as $key => $value) {
                                    $id = $value['id'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td class="text-center"><?= $value['name'] ?></td>
                                        <td class="text-center">
                                            <a href="<?= URL ?>upload/doc/<?= $value['url'] ?>" target="_blank"><?= ICON_OPEN ?></a>
                                        </td>
                                        <td class="text-center" dir="ltr"><?= $value['dt'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="javascript: " onclick="edit(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/upload_delete?id=<?= $id ?>&company_id=<?= $_GET['company_id'] ?>"><?= ICON_DELETE ?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12" style="position:sticky;top:0">
                <div class="card">
                    <div class="card-header">عملیات</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/upload_insert?company_id=<?= $_GET['company_id'] ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="company_id" value="<?= $_GET['company_id'] ?>">
                            <div>
                                <fluent-text-field appearance="filled" name="name">نام فایل</fluent-text-field>
                            </div>

                            <div>
                                انتخاب فایل:
                                <input type="file" name="url">
                                <input type="hidden" name="url_hidden" value="">
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
    const loading = (state = false) => {
        document.querySelector("#loading").style.opacity = state ? "1" : "0"
        document.querySelector("#loading").style.visibility = state ? "visible" : "hidden"
    }

    const edit = async (id) => {
        loading(true)
        const requestOptions = {
            method: 'GET',
            redirect: 'follow'
        }
        return await fetch('<?= URL ?>panel/<?= $this->endpoint ?>/upload_info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result)
                loading(false)
                document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/upload_update/${id}?company_id=<?= $_GET['company_id'] ?>`
                document.querySelector('fluent-button').innerText = "بروز رسانی"
                document.querySelector('[name="name"]').value = result.name
                document.querySelector('[name="url_hidden"]').value = result.url
            }).catch(error => console.error)
    }
</script>