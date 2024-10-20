<?php
$data = $this->data["data"]["data"];
$total = $this->data["data"]["total"];
?>
<section>
    <div class="__frame" data-width="large">
    <p class="alert alert-info ">
            سایز تصاویر 800 در 500 استفاده شود
        </p>
        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '<p class="alert alert-success">Record has been added.</p>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card">
                    <div class="card-header">
                        رکوردها
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <caption>لیست <?= $this->title ?></caption>
                                <thead>
                                    <th>تصویر</th>
                                    <th>عنوان</th>
                                    <th>عکاس</th>
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if ((empty($value['url']) || !file_exists('upload/images/' . $value['url']))) : ?>
                                                <span class="badge badge-light">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['url'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $value['name'] ?></td>
                                        <td><?= $value['photographer'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>"><?= $status_text ?></a>
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
                                تصویر
                                <input type="hidden" name="url_hidden" value="">
                                <input class="form-control" type="file" name="url" id="url">
                            </div>

                            <div>
                                <fluent-text-field appearance="filled" name="name">عنوان</fluent-text-field>
                            </div>

                            <div>
                                <fluent-text-field appearance="filled" name="photographer">عکاس</fluent-text-field>
                            </div>

                            <fluent-button appearance="accent" type="submit" id="btn-update">اضافه</fluent-button>
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

    const editRow = async (id) => {
        loading(true)
        const requestOptions = {
            method: 'GET',
            redirect: 'follow'
        }
        return await fetch('<?= URL ?>panel/<?= $this->endpoint ?>/info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result)
                loading(false)
                document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
                document.querySelector('fluent-button').innerText = "بروز رسانی"
                document.querySelector('input[name="url_hidden"]').value = result.url
                document.querySelector('fluent-text-field[name="name"]').value = result.name
                document.querySelector('fluent-text-field[name="photographer"]').value = result.photographer
            }).catch(error => error)
    }
</script>