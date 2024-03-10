<?php
$data = $this->data;
$total = $this->total;
$category = $this->category;
?>
<section id="unit">
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
                <div class="card" data-shadow="0">
                    <div class="card-body">
                        <a class="btn btn-light" href="<?= URL ?>panel/catquestion">
                            Category
                        </a>
                    </div>
                </div>
            </div>

            <div class="col xl-6">
                <div class="card">
                    <div class="card-header">
                        <img class="none" id="loading" src="<?= URL ?>public/images/loading.gif" alt="loading">
                        add new row
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/subcatquestion/insert" method="post">
                            <input id="txtId" type="hidden" value="" name="id">

                            <div class="form-group">
                                <label>Category (parent) <a href="<?= URL ?>panel/catquestion"
                                                            class="badge badge-danger">Edit</a></label>
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
                                <label>Sub Category Name</label>
                                <input class="form-control" name="name" id="name" required>
                            </div>

                            <input id="btnSubmit" type="submit" class="btn btn-primary mb-2" value="Add">
                        </form>
                    </div>
                </div>
            </div>

            <div class="col xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-sm">
                                <caption><?= $this->title ?> list [<?= (new Paging)->getTotal($total); ?>-page]
                                    <thead>
                                    <th class="text-center">#</th>
                                    <th>category name(parent)</th>
                                    <th>sub category name(child)</th>
                                    <th class="text-center">command</th>
                                    </thead>
                                  <?php
                                  foreach ($data as $key => $value) {
                                    $id = $value['subcategory_id'];
                                    $category_name = $value['category_name'];
                                    $subcategory_name = $value['subcategory_name'];
                                    ?>
                                      <tr>
                                          <td class="text-center"><?= ++$key ?></td>
                                          <td><?= $category_name ?></td>
                                          <td><?= $subcategory_name ?></td>
                                          <td class="d-flex justify-content-between">
                                              <a href="javascript: "
                                                 onclick="editRow(<?= $id ?>,'<?= $subcategory_name ?>')">
                                                <?= ICON_EDIT ?>
                                              </a>
                                              <a href="<?= URL ?>panel/subcatquestion/delete?id=<?= $id ?>"
                                                 role="delete">
                                                <?= ICON_DELETE ?>
                                              </a>
                                          </td>
                                      </tr>
                                    <?php
                                  }
                                  ?>
                            </table>
                        </div>
                      <?= (new Paging)->show(PATH_ADMIN . '/subcatquestion', $total, $this->pg); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    window.addEventListener('load', function () {
    });

    function editRow(id, class_name) {
        document.querySelector('#loading').classList.remove('none');

        superagent
            .get('<?= URL ?>panel/subcatquestion/info')
            .query({id: id})
            .on('progress', function (e) {
            })
            .then(res => {
                document.querySelector('#loading').classList.add('none');
                let obj = JSON.parse(res['text'])[0];
                document.querySelector('#txtId').value = obj.subcategory_id;
                $('#category_id').val(obj.category_id);
                document.querySelector('#name').value = obj.name;
                document.querySelector('#form').action = "<?= URL ?>panel/subcatquestion/update";
                document.querySelector('#btnSubmit').value = "Update";
            });
    }
</script>