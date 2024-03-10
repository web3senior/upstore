<?php
$data = $this->data;
$total = $this->total;
$menu = $this->menu;
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
                        <a class="btn btn-inverse-warning" href="<?= URL ?>panel/menu">
                            root menu
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
                        <form id="form" action="<?= URL ?>panel/submenu/insert" method="post">
                            <input id="txtId" type="hidden" value="" name="id">

                            <div class="form-group">
                                <label>menu (parent) <a href="<?= URL ?>panel/menu" class="badge badge-danger">Edit</a></label>
                                <select class="form-control" name="menu_id" id="menu_id">
                                  <?php
                                  foreach ($menu as $key => $value) {
                                    ?>
                                      <option value="<?= $value["menu_id"] ?>"><?= $value["name"] ?></option>
                                    <?php
                                  }
                                  ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question">name</label>
                                <textarea class="form-control" name="name" id="name" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="question">link</label>
                                <textarea class="form-control" name="link" id="link"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="question">priority (e.g. 1,2,3,...)</label>
                                <input type="number" class="form-control" name="priority" id="priority">
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
                                    <th>menu name(parent)</th>
                                    <th>sub menu name(child)</th>
                                    <th>link</th>
                                    <th>priority</th>
                                    <th class="text-center">command</th>
                                    </thead>
                                  <?php
                                  foreach ($data as $key => $value) {
                                    $id = $value['submenu_id'];
                                    $name = $value['name'];
                                    $submenu_link = $value['submenu_link'];
                                    $submenu_priority = $value['submenu_priority'];
                                    $submenu_name = $value['submenu_name'];
                                    ?>
                                      <tr>
                                          <td class="text-center"><?= ++$key ?></td>
                                          <td><?= $name ?></td>
                                          <td><?= $submenu_name ?></td>
                                          <td>
                                              <a class="badge badge-light" target="_blank"
                                                 href="<?= $submenu_link ?>"><?= ICON_LINK ?></a>
                                          </td>
                                          <td>
                                              <span class="text-danger"><?= $submenu_priority ?></span>
                                          </td>
                                          <td class="d-flex justify-content-between">
                                              <a href="javascript: " onclick="editRow(<?= $id ?>)">
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
                      <?= (new Paging)->show(PATH_ADMIN . '/submenu', $total, $this->pg); ?>
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
            .get('<?= URL ?>panel/submenu/info')
            .query({id: id})
            .on('progress', function (e) {
            })
            .then(res => {
                document.querySelector('#loading').classList.add('none');
                let obj = JSON.parse(res['text'])[0];
                document.querySelector('#txtId').value = obj.submenu_id;
                $('#menu_id').val(obj.menu_id);
                document.querySelector('#name').value = obj.name;
                document.querySelector('#link').value = obj.link;
                document.querySelector('#priority').value = obj.priority;
                document.querySelector('#form').action = "<?= URL ?>panel/submenu/update";
                document.querySelector('#btnSubmit').value = "Update";
            });
    }
</script>