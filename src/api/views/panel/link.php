<?php
$data = $this->data["data"]["data"];
$total = $this->data["data"]["total"];
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

        <p class="alert alert--info alert--border">
            درجه اولویت، خالی بیشتر از 1 در قسمت اولویت بندی می باشد.
        </p>

        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg12">
                <div class="card">
                    <div class="card-header">جستجو</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>" method="get" autocomplete="off">
                            <div>
                                <fluent-text-field appearance="filled" name="t" value="<?= (isset($_GET['t']) ? $_GET['t'] : '') ?>">عنوان</fluent-text-field>
                            </div>
                            <button appearance="accent" type="submit" id="btn-update">جستجو</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        رکوردها
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <caption>لیست <?= $this->title ?></caption>
                                <thead>
                                    <th>عنوان</th>
                                    <th>تصویر</th>
                                    <th>توضیحات</th>
                                    <th>اولویت نمایش</th>
                                    <th>کلید دسترسی</th>
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td><?= $value['title'] ?></td>
                                        <td class="text-center">
                                            <?php if ((empty($value['icon']) || !file_exists('upload/images/' . $value['icon']))) : ?>
                                                <span class="badge badge-light">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['icon'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $value['lead'] ?></td>
                                        <td><?= $value['priority'] ?></td>
                                        <td><?= $value['accesskey'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?><?= (isset($_GET['page'])) ? '&page=' . $_GET['page'] : null ?>"><?= $status_text ?></a>
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
                                <fluent-text-field appearance="filled" name="title">عنوان</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="accesskey">کلید دسترسی</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="priority">اولویت بندی</fluent-text-field>
                            </div>


                            <div>
                                <fluent-text-field appearance="filled" name="path" dir="ltr">Path</fluent-text-field>
                            </div>


                            <div>
                                <fluent-text-field appearance="filled" name="type" dir="ltr">Type</fluent-text-field>
                            </div>

                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="lead">توضیحات</fluent-text-area>
                            </div>

                            <div>
                                تصویر
                                <input type="hidden" name="icon_hidden" value="">
                                <input class="form-control" type="file" name="icon" id="icon">
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
                document.querySelectorAll('form')[1].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}<?= (isset($_GET['page'])) ? '&page=' . $_GET['page'] : null ?>`
                document.querySelector('fluent-button').innerText = "بروز رسانی"
                document.querySelector('fluent-text-field[name="title"]').value = result.title
                document.querySelector('fluent-text-field[name="priority"]').value = result.priority
                document.querySelector('fluent-text-field[name="path"]').value = result.path
                document.querySelector('fluent-text-field[name="type"]').value = result.type
                document.querySelector('fluent-text-area[name="lead"]').value = result.lead

                document.querySelector('fluent-text-field[name="accesskey"]').value = result.accesskey
                document.querySelector('input[name="icon_hidden"]').value = result.icon
            }).catch(error => error)
    }
</script>