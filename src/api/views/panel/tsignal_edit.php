<?php
$data = $this->data[0];
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
                            <li class="list-group-item">ID: <span
                                        class="badge badge-danger"><?= $data["tsignal_id"] ?></span></li>
                            <li class="list-group-item">Image: <img style="width:200px;"
                                                                    src="<?= URL ?>uploads/images/<?= $data["img"] ?>"
                                                                    alt="img" class="img-thumbnail"></li>
                            <li class="list-group-item"><a class="text-danger"
                                                           href="<?= URL ?>panel/service/delete?id=<?= $id ?>">Delete</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col xl-12">
                <div class="card">
                    <div class="card-header">
                        <img class="none" id="loading" src="<?= URL ?>public/images/loading.gif" alt="loading">
                        add new row
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/tsignal/update" method="post"
                              enctype="multipart/form-data">
                            <input id="txtId" type="hidden" value="<?= $data["tsignal_id"] ?>" name="id">
                            <div class="form-group">
                                <label>type</label>
                                <select name="type">
                                    <option value="buy">Buy</option>
                                    <option value="sell">sell</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>forecast</label>
                                <input type="number" class="form-control" name="forecast" id="title"
                                       value="<?= $data['forecast'] ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="tip">image</label>
                                <input type="hidden" name="img_hidden" value="<?= $data["img"] ?>">
                                <input class="form-control" type="file" name="img" id="img">
                            </div>

                            <div class="form-group">
                                <label>content</label>
                                <textarea class="form-control" name="content" id="content"
                                          required><?= $data['content'] ?></textarea>
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

        // CKEDITOR.replace('content', {
        //     language: 'en',
        //     uiColor: '#e5a968',
        //     toolbarCanCollapse: true
        // });

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