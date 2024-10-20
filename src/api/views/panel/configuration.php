<?php
$data = $this->data[0];
$keywords_count = explode(',', $data['keyword']);
$color = json_decode($data["color"]);
$admin = $this->admin;
?>
<style>
    .configuration img {
        height: 80px;
    }
</style>
<section class="configuration">
    <div class="container-fluid">
      <?php
      if (isset($_GET['update'])) {
        $insert = $_GET['update'];
        $r = ($insert) ? '<p class="alert alert-success">Record has been updated.</p>' : '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        echo $r;
      }
      ?>
        <div class="row xl">
            <div class="col xl-12">
                <div class="card">
                    <div class="card-header">update</div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <strong>Heads up!</strong>
                            <p class="mb-1">Title Length: 70 [char]</p>
                            <p class="mb-1">Description Length: 1 [sentence]</p>
                        </div>

                        <form id="form" action="<?= URL ?>panel/configuration?mod=update" method="post"
                              enctype="multipart/form-data">
                            <input id="txtId" type="hidden" value="<?= $data["configuration_id"] ?>" name="id">
                            <div class="form-group">
                                <label for="question">
                                    description <span
                                            class="badge badge-pill badge-info text-uppercase">count: <?= strlen($data["description"]) ?> chars</span>
                                </label>
                                <input class="form-control" name="description" id="description"
                                       value="<?= $data["description"] ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="answer">
                                    keyword <span
                                            class="badge badge-pill badge-info text-uppercase">count: <?= count($keywords_count) ?></span>
                                </label>
                                <textarea class="form-control" name="keyword" id="keyword" style="height:90px"
                                          required><?= $data["keyword"] ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">meta tags <span
                                            class="badge badge-pill badge-danger text-uppercase">hot</span></label>
                                <textarea class="form-control" name="meta" id="meta" style="height:90px"
                                          required><?= $data["meta"] ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">admin page
                                    <span class="badge badge-pill badge-warning text-uppercase">new</span></label>
                                <textarea class="form-control" name="admin" id="admin" spellcheck="false" required
                                          style="height:170px"><?= $admin ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col xl-6">
                                    <div class="form-group">
                                        <label for="answer">background color:</label>
                                        <input class="form-control" type="color" name="bgcolor" id="bgcolor"
                                               value="<?= $color[0] ?>">
                                    </div>
                                </div>

                                <div class="col xl-6">
                                    <div class="form-group">
                                        <label for="answer">text color:</label>
                                        <input class="form-control" type="color" name="textcolor" id="textcolor"
                                               value="<?= $color[1] ?>">
                                    </div>
                                </div>

                                <div class="col xl-3">
                                    <div class="form-group text-center">
                                        current logo:
                                        <img src="<?= URL . 'uploads/images/' . $data['logo'] ?>">
                                        <label for="logo">logo</label>
                                        <input type="hidden" name="ex_logo" value="<?= $data["logo"] ?>">
                                        <input class="form-control-file" type="file" name="logo" id="logo">
                                    </div>
                                </div>

                                <div class="col xl-3">
                                    <div class="form-group">
                                        current favicon:
                                        <img src="<?= URL . 'uploads/images/' . $data['favicon'] ?>">
                                        <label for="tip">favicon</label>
                                        <input type="hidden" name="ex_favicon" value="<?= $data["favicon"] ?>">
                                        <input class="form-control-file" type="file" name="favicon" id="favicon">
                                    </div>
                                </div>

                                <div class="col xl-3">
                                    <div class="form-group">
                                        current large image:
                                        <img src="<?= URL . 'uploads/images/' . $data['large_img'] ?>">
                                        <label for="tip">large image</label>
                                        <input type="hidden" name="ex_limg" value="<?= $data["large_img"] ?>">
                                        <input class="form-control-file" type="file" name="limg" id="limg">
                                    </div>
                                </div>

                                <div class="col xl-3">
                                    <div class="form-group">
                                        current small image:
                                        <img src="<?= URL . 'uploads/images/' . $data['small_img'] ?>">
                                        <label for="tip">small image</label>
                                        <input type="hidden" name="ex_simg" value="<?= $data["small_img"] ?>">
                                        <input class="form-control-file" type="file" name="simg" id="simg">
                                    </div>
                                </div>

                            </div>
                            <input id="btnSubmit" type="submit" class="btn btn-warning mb-2" value="Update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    window.addEventListener('load', function () {

        /* CKEDITOR.replace('content', {
             language: 'en',
             uiColor: '#e5a968',
             toolbarCanCollapse: true
         });*/

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