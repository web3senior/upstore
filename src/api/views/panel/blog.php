<?php
$data = $this->data['data'];
$total = $this->data['total'];
$category = $this->category;
?>
<section>
    <div class="container-fluid">
        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '
<script>
    window.addEventListener(\'DOMContentLoaded\', (event) => {
        Swal.fire({
            position: \'top-end\',
            icon: \'success\',
            title: \'Record has been added.\',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        })
    });
</script>
';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="row xl">
            <div class="col xl-12">

<div class="alert alert--danger">
<a href="https://getemoji.com/" class="text-white" target="_blank">مشاهده ایموجی</a>
</div>
            <div class="card">
                    <div class="card-header">
                      لیست خبرها
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <caption><?= $this->title ?> list [<?= (new Paging)->getTotal($total); ?>-page]</caption>
                                <thead>
                                    <th class="text-center">#</th>
                                    <th>عنوان</th>
                                    <th>دسته بندی</th>
                                    <th class="text-center">عکس</th>
                                    <th>تاریخ انتشار</th>
                                    <th class="text-center">عملیات</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['blog_id'];
                                    $title = $value['title'];
                                    $img = $value['img'];
                                    $date = $value['date'];
                                    $name = $value['name'];
                                    $status = $value['status'];
                                    $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>

                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td><?= $title ?></td>
                                        <td>
                                            <span class="badge badge-purpink badge-pill shadow-purple"><?= $name ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= URL ?>uploads/images/<?= $img ?>" target="_blank">
                                                <span class="material-icons">image_search</span>
                                            </a>
                                        </td>
                                        <td>
                                            <?= $date ?>
                                        </td>
                                        <td class="text-center">
                                            <a class="text-dark" target="_blank" href="<?= URL ?>blog/p/<?= $id ?>"><?= ICON_OPEN ?></a>
                                            <a class="text-warning" href="<?= URL ?>panel/blog/info?id=<?= $id ?>"><?= ICON_EDIT ?></a>
                                            <a class="text-danger" href="<?= URL ?>panel/blog/delete?id=<?= $id ?>" role="delete"><?= ICON_DELETE ?></a>
                                            <a href="<?= URL ?>panel/blog/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>"><?= $status_text ?></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                        <?= (new Paging)->show(PATH_ADMIN . '/blog', $total, $this->pg); ?>
                    </div>
                </div>
            </div>

            <div class="col xl-8">
                <div class="card">
                    <div class="card-header">
                       اضافه و ویرایش خبر
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/blog/insert" method="post" enctype="multipart/form-data">
                            <input id="txtId" type="hidden" value="" name="id">
                            <div class="form-group">
                                <label for="question">دسته بندی <a href="<?= URL ?>panel/category" class=" badge badge-danger">ویرایش</a></label>
                                <select class="form-control" name="category_id" id="category_id">
                                    <?php
                                    foreach ($category as $key => $value) {
                                    ?>
                                        <option value="<?= $value["category_id"] ?>"><?= $value["name"] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="question">عنوان</label>
                                <input class="form-control" name="title" id="title" rows="4" required></input>
                            </div>
                            <div class="form-group">
                                <label for="tip">عکس</label>
                                <input class="form-control" type="file" name="img" id="img" required>
                            </div>
                            <div class="form-group">
                                <label for="answer">محتوا</label>
                                <textarea class="form-control" name="content" id="content" rows="10" cols="80" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="answer">tag</label>
                                <input class="form-control" type="text" name="tag" id="tag" placeholder="tag1+tag2+...">
                                <span class="input-hint">Please use atleast eight tags.</span>
                            </div>
                            <div class="form-group">
                                <label for="tip">تاریخ</label>
                                <input class="form-control" type="date" name="date" id="date" value="<?= date("Y-m-d") ?>" required>
                                <span class="input-hint">today's date is default.</span>
                            </div>
                            <input id="btnSubmit" type="submit" class="btn btn-primary mb-2" value="اضافه">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col xl-4">
                <div class="card">
                    <ul class="list-group list-group-flush">

                        <?php
                        foreach ($category as $key => $value) {
                        ?>
                            <li class="list-group-item">
                                <?= $value["name"] ?>
                                <span class="badge badge-primary right">
                                    <?= ICON_OPEN ?>
                                </span>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<script type="text/javascript">
    window.addEventListener('load', function() {
        CKeditor('content', '#4e4e50');
    });

    function editRow(id) {
        superagent
            .get('<?= URL ?>panel/blog/edit')
            .query({
                id: id
            })
            .on('progress', function(e) {
                /* console.log(e.direction,"is done",e.percent,"%");*/
                document.querySelector('#loading').classList.remove('none');
            })
            .then(res => {
                document.querySelector('#loading').classList.add('none');
                let obj = JSON.parse(res['text'])[0];
                document.querySelector('#txtId').value = obj.blog_id;
                document.querySelector('#title').value = obj.title;
                document.querySelector('#img').value = obj.img;
                document.querySelector('#content').value = obj.content;
                document.querySelector('#tag').value = obj.tag;
                document.querySelector('#date').value = obj.date;
                document.querySelector('#form').action = "<?= URL ?>panel/blog/update";
                document.querySelector('#btnSubmit').innerText = "Update";
            });
    }
</script>