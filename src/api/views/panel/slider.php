<?php
$data = $this->data;
$total = count($data);
?>
<section class="dashboard" id="category">
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
            <div class="col xl-6">
                <div class="card">
                    <div class="card-header">
                        <img class="none" id="loading" src="<?= URL ?>public/images/loading.gif" alt="loading">
                        Operation
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/category/insert" method="post">
                            <input id="txtId" type="hidden" value="" name="id">

                            <div class="form-group">
                                <label for="question">Alt</label>
                                <textarea class="form-control" name="alt" id="alt" rows="4"
                                          required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="question">Url(image)</label>
                                <textarea class="form-control" name="url" id="url" rows="4"></textarea>
                            </div>

                            <button id="btnSubmit" type="submit" class="btn btn-primary mb-2" id="btnInsert">Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <caption><?= $this->title ?> list [<?= (new Paging)->getTotal($total); ?>-page]
                                    <thead class="text-uppercase">
                                    <th>alt</th>
                                    <th>url</th>
                                    <th>command</th>
                                    </thead>
                                  <?php
                                  foreach ($data as $key => $value) {
                                    $id = $value['slider_id'];
                                    $alt = $value['alt'];
                                    $url = $value['url'];
                                    ?>
                                      <tr>
                                          <td><?= $alt ?></td>
                                          <td class="text-center">
                                            <?php
                                            if (!empty($url)) {
                                              ?>
                                                <a href="<?= URL ?>uploads/images/<?= $url ?>" target="_blank">
                                                    <img class="img-thumbnail" src="<?= $url ?>"
                                                         style="width:80px">
                                                </a>
                                              <?php
                                            }
                                            ?>
                                          </td>
                                          <td>
                                              <div class="d-flex justify-content-between">
                                                  <a href="javascript: " onclick="editRow(<?= $id ?>)"
                                                  >
                                                    <?= ICON_EDIT ?>
                                                  </a>
                                                  <a role="delete" href="<?= URL ?>panel/slider/delete?id=<?= $id ?>">
                                                    <?= ICON_DELETE ?>
                                                  </a>
                                              </div>
                                          </td>
                                      </tr>
                                    <?php
                                  }
                                  ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    window.addEventListener('load', function () {
    });

    function editRow(id) {
        document.querySelector('#loading').classList.remove('none');
        superagent
            .get('<?= URL ?>panel/slider/info')
            .query({id: id})
            .on('progress', function (e) {
            })
            .then(res => {
                document.querySelector('#loading').classList.add('none');
                let obj = JSON.parse(res['text'])[0];
                document.querySelector('#txtId').value = obj.slider_id;
                document.querySelector('#alt').value = obj.alt;
                document.querySelector('#url').value = obj.url;
                document.querySelector('#form').action = "<?= URL ?>panel/slider/update";
                document.querySelector('#btnSubmit').innerText = "Update";
            });
    }
</script>