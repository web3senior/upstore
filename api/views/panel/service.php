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
                                    <th>نام</th>
                                    <th>قیمت</th>
                                    <th>مدت انجام</th>
                                    <th>شرح خدمات</th>
                                    <th>تاریخ</th>
                                    <th width="15%">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $statusText = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td><?= $value['name'] ?></td>
                                        <td class="text-center"><?= number_format($value['price']) ?></td>
                                        <td class="text-center"><?= $value['donetime'] ?> روز</td>
                                        <td><?= $value['description'] ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-warning badge-pill ltr"><?= $value['dt'] ?></span>
                                        </td>
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
                                <fluent-number-field appearance="filled" name="price">قیمت</fluent-number-field>
                                <small>بر اساس تومان وارد شود.</small>
                            </div>
                            <div>
                                <fluent-number-field appearance="filled" name="donetime">مدت انجام</fluent-number-field>
                                <small>بر اساس روز وارد شود</small>
                            </div>
                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="description">شرح خدمات</fluent-text-area>
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
        document.querySelector('fluent-number-field[name="price"]').value = result.price
        document.querySelector('fluent-number-field[name="donetime"]').value = result.donetime
        document.querySelector('fluent-text-area[name="description"]').value = result.description
    }
</script>