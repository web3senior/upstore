<?php
$data = $this->data['data']['data'];
$total = $this->data['data']['total'];
$employee = $this->data['employee'];
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
                                    <th>کارمند</th>
                                    <th>دسترسی ها</th>
                                    <th>توضیحات</th>
                                    <th width="15%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['access_id'];
                                    $status = $value['access_status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td class="text-center"><span class="badge badge-danger badge-pill ltr"><?= $value['employee_id'] ?></span> <?= $value['fullname'] ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-danger badge-pill ltr"><?= $value['permitions'] ?></span>
                                        </td>
                                        <td><?= $value['access_description'] ?></td>
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
                                کارمند:
                                <select title="انتخاب کارمند" name="employee_id">
                                    <?php
                                    foreach ($employee as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>">#<?= $value['id'] . ' > ' . $value['fullname'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                دسترسی:
                                <select title="دسترسی" name="permitions">
                                    <option value="full">کامل</option>
                                    <option value="developer">توسعه دهنده</option>
                                    <option value="employee">کارمند</option>
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
        document.querySelector('select[name="employee_id"]').value = result.employee_id
        document.querySelector('select[name="permitions"]').value = result.permitions
        document.querySelector('fluent-text-area[name="description"]').value = result.description
    }
</script>