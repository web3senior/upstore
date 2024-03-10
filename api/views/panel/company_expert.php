<?php
$data = $this->data['data'][0];
$expert = $this->data['expert'];
$all_expert = $this->data['all_expert'];
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
                                    <th> شناسه مهندس</th>
                                    <th>تاریخ درج</th>
                                    <th width="15%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($expert as $key => $value) {
                                    $id = $value['id'];
                                ?>
                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-warning">#<?= $value['expert_id'] ?> </span>
                                            <?= $value['expert_fullname'] ?>
                                            <span class="badge badge-info"> <?= ($value['expert_score'] === 0) ? 'دوسوم':'امتیاز آور'  ?></span>
                                        </td>
                                        <td class="text-center" dir="ltr"><?= $value['dt'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="javascript: " onclick="edit(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/expert_delete?id=<?= $id ?>&company_id=<?= $_GET['company_id'] ?>"><?= ICON_DELETE ?></a>
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
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/expert_insert?company_id=<?= $_GET['company_id'] ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="company_id" value="<?= $_GET['company_id'] ?>">
                            <select name="expert_id" id="">
                                <?php
                                foreach ($all_expert as $key => $value) {
                                ?>
                                    <option value="<?=$value['id']?>">#<?= $value['id'] ?>) <?= $value['fullname'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
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
        return await fetch('<?= URL ?>panel/<?= $this->endpoint ?>/expert_info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result)
                loading(false)
                document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/expert_update/${id}?company_id=<?= $_GET['company_id'] ?>`
                document.querySelector('fluent-button').innerText = "بروز رسانی"
                document.querySelector('[name="expert_id"]').value = result.expert_id
            }).catch(error => console.error)
    }
</script>