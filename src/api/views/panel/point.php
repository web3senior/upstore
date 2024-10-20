<?php
$data = $this->data["data"]["data"];
$total = $this->data["data"]["total"];
$sublayer = $this->data["sublayer"];
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
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg6" style="position:sticky;top:0">
                <div class="card">
                    <div class="card-header">جستجو</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>" method="get" enctype="multipart/form-data" autocomplete="off">
                        <div>
                                <fluent-text-field appearance="filled" name="t" value="<?= (isset($_GET['t'])? $_GET['t']:'')?>">عنوان</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="id" value="<?= (isset($_GET['id'])? $_GET['id']:'')?>">کد</fluent-text-field>
                            </div>

                            <div>
                            <select autocomplete="both" placeholder="لایه دوم" name="sublayer_id">
                                <option value="">انتخاب کنید</option>
                                    <?php
                                    foreach ($sublayer as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>

                                </select>
                            </div>

                            <button appearance="accent" type="submit" id="btn-update">جستجو</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ms-Grid-col ms-sm12 ms-md12 ms-lg6" style="position:sticky;top:0">
                <div class="card">
                    <div class="card-header">عملیات</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">

                            <div>
                                لایه دوم:
                                <select placeholder="لایه دوم" name="sublayer_id">
                                    <?php
                                    foreach ($sublayer as $key => $value) {
                                    ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php
                                    }
                                    ?>

                                </select>
                            </div>

                            <div>
                                <fluent-text-field appearance="filled" name="title">عنوان</fluent-text-field>
                            </div>

                            <div>
                                <fluent-text-field appearance="filled" name="phone" dir='ltr'>موبایل</fluent-text-field>
                            </div>

                            <div>
                                تصویر
                                <input type="hidden" name="img_hidden" value="">
                                <input class="form-control" type="file" name="img" id="img">
                            </div>

                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="area">موقعیت جغرافیایی</fluent-text-area>
                            </div>
                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="description">توضیحات</fluent-text-area>
                            </div>

                            <fluent-button appearance="accent" type="submit" id="btn-update">اضافه</fluent-button>
                        </form>
                    </div>
                </div>
            </div>
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
                                    <th>لایه سطح دوم</th>
                                    <th>عنوان</th>
                                    <th>موقعیت جغرافیایی</th>
                                    <th>موبایل</th>
                                    <th>تصویر</th>
                                    <th>توضیحات</th>
                                    <th>آخرین بروزرسانی</th>
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                        <td><?=$id?></td>
                                        <td><?= $value['sublayer_name'] ?></td>
                                        <td><?= $value['title'] ?></td>
                                        <td>
                                            <?= $value['area'] ?>
                                            <a href="http://www.google.com/maps/place/<?= json_decode($value['area'])[0] ?>,<?= json_decode($value['area'])[1] ?>">مشاهده در گوگل مپ</a>
                                        </td>
                                        <td dir="ltr"><?= $value['phone'] ?></td>
                                        <td class="text-center">
                                            <?php if ((empty($value['gallery'][0]) || !file_exists('upload/images/' . $value['gallery'][0]))) : ?>
                                                <span class="badge badge-light">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['gallery'][0] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $value['description'] ?></td>
                                        <td><?= $value['at'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>"><?= $status_text ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <?= (new Paging)->show(PATH_ADMIN . $this->endpoint, $total, $this->pg, ((isset($_GET['t']) && !empty($_GET['t'])) ? "?t=".$_GET['t']:false )); ?>
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
                document.querySelectorAll('form')[1].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
                document.querySelector('fluent-button').innerText = "بروز رسانی"
                document.querySelectorAll('select[name="sublayer_id"]')[1].value = result.sublayer_id
                document.querySelector('fluent-text-field[name="title"]').value = result.title
                document.querySelector('fluent-text-field[name="phone"]').value = result.phone
                document.querySelector('input[name="img_hidden"]').value = result.gallery
                document.querySelector('fluent-text-area[name="description"]').value = result.description
                document.querySelector('fluent-text-area[name="area"]').value = result.area

            }).catch(error => error)
    }
</script>