<?php
$data = $this->data;
?>
<section>
    <div class="__frame" data-width="small">
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
            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <thead>
                                <th>Name</th>
                                <th>Actions</th>
                                </thead>
                              <?php
                              foreach ($data as $key => $value) {
                                $id = $value['reason_id'];
                                $name = $value['name'];
                                ?>
                                  <tr>
                                      <td><?= $name ?></td>
                                      <td>
                                          <div class="d-flex justify-content-between">
                                              <a href="javascript: "
                                                 onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                              <a href="<?= URL ?>panel/reason/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
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
            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-header">Add & Update</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/reason/insert" method="post" autocomplete="off">
                            <div class="form-group">
                                <label for="question">name</label>
                                <input class="form-control" name="name" required>
                            </div>

                            <div class="form-group">
                                <button type="submit">Add</button>
                            </div>
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
        return await fetch('<?= URL ?>panel/reason/info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                    console.log(result)
                    loading(false)
                    document.querySelector('input[name="name"]').value = result.name
                    document.querySelector('form').action = `<?= URL ?>panel/reason/update/${id}`
                    document.querySelector('button[type="submit"]').innerText = "Update"
                }
            ).catch(error => error)
    }
</script>