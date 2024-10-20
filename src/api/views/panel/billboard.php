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
                                    <th>شروع رنگ</th>
                                    <th>پایان رنگ</th>
                                    <th>رنگ بدنه</th>
                          
                                    <th>عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['id'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                    <tr>

                                        <td>
                                            <input type="color" value="<?= $value['light_start_color'] ?>" />
                                        </td>
                                        <td>
                                            <input type="color" value="<?= $value['light_end_color'] ?>" />
                                        </td>
 <td>
                                            <input type="color" value="<?= $value['body_color'] ?>" />
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
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
                                <input type="color" name="light_start_color">شروع رنگ</input>
                            </div>
                            <div>
                                <input type="color" name="light_end_color">پایان رنگ</input>
                            </div>
                             <div>
                                <input type="color" name="body_color">رنگ بدونه</input>
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

                document.querySelector('input[name="light_start_color"]').value = result.light_start_color
                document.querySelector('input[name="light_end_color"]').value = result.light_end_color
                document.querySelector('input[name="body_color"]').value = result.body_color


            }).catch(error => error)
    }
</script>