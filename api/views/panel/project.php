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
                                <th>نام</th>
                                <th>تصویر</th>
                                    <th>مجری</th>
                                    <th>تاریخ شروع</th>
                                    <th>تاریخ پایان</th>
                                    <th>بودجه</th>
                                    <th>توضیحات</th>
                                    <th>درصد پیشرفت</th>
                                    <th>وضعیت پروژه</th>
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>
                                    <td><?= $value['name'] ?></td>

                                        <td class="text-center">
                                            <?php if ((empty($value['image']) || !file_exists('upload/images/' . $value['image']))) : ?>
                                                <span class="badge badge-light">بدون تصویر</span>
                                            <?php else : ?>
                                                <a target="_blank" href="<?= URL ?>upload/images/<?= $value['image'] ?>" class="btn-more">
                                                    <?= ICON_OPEN ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $value['executive'] ?></td>
                                        <td><?= $value['start_date'] ?></td>
                                        <td><?= $value['end_date'] ?></td>
                                           <td><?= $value['budget'] ?></td>
                                        <td><?= $value['description'] ?></td>
                                          <td><?= $value['progress'] ?></td>
                                           <td><?= $value['state'] ?></td>
                                        <td>

                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>"><?= $status_text ?></a>
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
                        <p>در حال اجرا</p>
                        <p> کامل شده</p>
                         <p>شروع نشده </p>
                         <br/>
این سه مورد می تواند درصد وضعیت را نمایش دهد
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                           
                            <div>
                                <fluent-text-field appearance="filled" name="name">نام</fluent-text-field>
                            </div>
                            <div>
                                تصویر
                                <input type="hidden" name="image_hidden" value="">
                                <input class="form-control" type="file" name="image" id="image">
                            </div>
                               
                            <div>
                                <fluent-text-field appearance="filled" name="executive">مجری</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="start_date">تاریخ شروع</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="end_date">تاریخ پایان</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="budget">میزان بودجه</fluent-text-field>
                            </div>
                            <div>
                                <fluent-text-area appearance="filled" placeholder="" name="description">توضیحات</fluent-text-area>
                            </div>
                            <div>
                                <fluent-text-field appearance="filled" name="progress">درصد پیشرفت </fluent-text-field>
                            </div>
                            <div>
                            <fluent-text-field appearance="filled" name="state"> وضعیت </fluent-text-field>
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
                document.querySelector('fluent-text-field[name="name"]').value = result.name
                document.querySelector('input[name="image_hidden"]').value = result.image
                document.querySelector('fluent-text-field[name="executive"]').value = result.executive
                document.querySelector('fluent-text-field[name="start_date"]').value = result.start_date
                document.querySelector('fluent-text-field[name="end_date"]').value = result.end_date
                document.querySelector('fluent-text-field[name="budget"]').value = result.budget
                document.querySelector('fluent-text-area[name="description"]').value = result.description
                document.querySelector('fluent-text-field[name="progress"]').value = result.progress
                document.querySelector('fluent-text-field[name="state"]').value = result.state

            }).catch(error => error)
    }
</script>