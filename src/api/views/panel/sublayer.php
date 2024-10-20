<?php
$data = $this->data["data"]["data"];
$total = $this->data["data"]["total"];
$layer = $this->data["layer"];
?>
<section>
    <div class="__frame" data-width="large">

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
                                    <th>#</th>
                                    <th>لایه اول</th>
                                    <th>نام</th>
                                    <th>مارکر</th>
                                     <th>تصویر</th>
                                    <th>توضیحات</th>
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                    <td><?= $id ?></td>
                                    <td><?= $value['sublayer_name'] ?></td>
                                        <td><?= $value['name'] ?></td>
                                        <td class="text-center">
                                            <?php if ((empty($value['marker']) || !file_exists('upload/images/' . $value['marker']))) : ?>
                                                <span class="badge badge-light">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['marker'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ((empty($value['icon']) || !file_exists('upload/images/' . $value['icon']))) : ?>
                                                <span class="badge badge-light">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['icon'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $value['description'] ?><?= $status ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?><?= (isset($_GET['page'])) ? '&page='.$_GET['page'] :null ?>"><?= $status_text ?></a>
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
                                <select autocomplete="both" placeholder="لایه اول" name="layer_id">
                                    <?php
                                    foreach ($layer as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>

                                </select>
                            </div>

                            <div>
                                <fluent-text-field appearance="filled" name="name">نام</fluent-text-field>
                            </div>

                            <div>
                                تصویر
                                <input type="hidden" name="icon_hidden" value="">
                                <input class="form-control" type="file" name="icon" id="icon">
                            </div>

                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="description">توضیحات</fluent-text-area>
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
                document.querySelector('select[name="layer_id"]').value = result.layer_id
                document.querySelector('fluent-text-field[name="name"]').value = result.name
                document.querySelector('input[name="icon_hidden"]').value = result.icon
                document.querySelector('fluent-text-area[name="description"]').value = result.description

            }).catch(error => error)
    }
</script>