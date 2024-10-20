<?php
$data = $this->data[0];
$category = $this->category;
?>
<section>
    <div class="container-fluid">
        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '<p class="alert alert-success">Record has been added.</p>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="row xl">
            <div class="col xl-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">ID: <span class="badge badge-danger"><?=$data["blog_id"]?></span></li>
                            <li class="list-group-item">Image: <img style="width:200px;" src="<?=URL?>uploads/images/<?=$data["img"]?>" alt="img" class="img-thumbnail"></li>
                            <li class="list-group-item"><a class="text-danger" href="<?= URL ?>panel/blog/delete?id=<?= $data["blog_id"] ?>">Delete Post</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col xl-12">
                <div class="card">
                    <div class="card-header">
                        <img class="none" id="loading" src="<?= URL ?>public/images/loading.gif" alt="loading">
                        update
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/blog/update" method="post"
                              enctype="multipart/form-data">
                            <input id="txtId" type="hidden" value="<?=$data["blog_id"]?>" name="id">
                            <div class="form-group">
                                <label for="question">Category Id</label>
                                <select class="form-control" name="category_id" id="category_id">
                                    <?php
                                    foreach ($category as $key => $value) {
                                        ?>
                                        <option value="<?= $value["category_id"] ?>" <?=($value["category_id"] == $data["category_id"]) ? 'selected':'';?>><?= $value["name"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="question">Title</label>
                                <input class="form-control" name="title" id="title" rows="4" value="<?=$data["title"]?>"
                                          required></input>
                            </div>
                            <div class="form-group">
                                <label for="tip">image</label>
                                <input type="hidden" name="img_hidden" value="<?=$data["img"]?>">
                                <input class="form-control" type="file" name="img" id="img">
                            </div>
                            <div class="form-group">
                                <label for="answer">content</label>
                                <textarea class="form-control" name="content" id="content" rows="10" cols="80"
                                          required><?=$data["content"]?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="answer">tag</label>
                                <input class="form-control" type="text" name="tag" id="tag" placeholder="tag1+tag2+..." value="<?=$data["tag"]?>">
                            </div>
                            <div class="form-group">
                                <label for="tip">date</label>
                                <input class="form-control" type="date" name="date" id="date"
                                       value="<?= date("Y-m-d") ?>" required value="<?=$data["date"]?>">
                            </div>
                            <input id="btnSubmit" type="submit" class="btn btn-success mb-2" value="Update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    window.addEventListener('load', function () {

        CKEDITOR.replace('content', {
            language: 'en',
            uiColor: '#e5a968',
            toolbarCanCollapse: true
        });

    });

    function editRow(id) {
        superagent
            .get('<?= URL ?>panel/blog/edit')
            .query({id: id})
            .on('progress', function (e) {
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