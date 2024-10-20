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
                <div class="alert alert-warning">
                    <a href="https://www.tradingview.com/widget/" target="_blank">
                        <span class="v-m">Find countries' flag here.</span>
                        <svg class="v-m" width="36" height="28" viewBox="0 0 36 28" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14 22H7V11H0V4h14v18zM28 22h-8l7.5-18h8L28 22z" fill="currentColor"></path>
                            <circle cx="20" cy="8" r="4" fill="currentColor"></circle>
                        </svg>
                    </a>
                </div>
                <div class="alert alert-danger">
                    Do not forget to add class attribute with "v-m" value to the SVG file.
                    e.g.
                    <code>
                      <?= htmlspecialchars('<svg class="v-m"></svg>') ?>
                    </code>
                </div>
            </div>
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
                                $id = $value['unit_id'];
                                $name = $value['name'];
                                ?>
                                  <tr>
                                      <td><?= $name ?></td>
                                      <td>
                                          <div class="d-flex justify-content-between">
                                              <a href="javascript: "
                                                 onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                              <a href="<?= URL ?>panel/unit/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
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
                        <form action="<?= URL ?>panel/unit/insert" method="post" autocomplete="off">
                            <div class="form-group">
                                <label for="question">name</label>
                                <textarea class="form-control" name="name" required style="height: 200px"></textarea>
                            </div>

                            <div class="form-group">
                                <button id="btnSubmit" type="submit" class="btn btn-primary mb-2" id="btnInsert">
                                    Add
                                </button>
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
        return await fetch('<?= URL ?>panel/unit/info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                    console.log(result)
                    loading(false)
                    document.querySelector('textarea[name="name"]').value = result.name
                    document.querySelector('form').action = `<?= URL ?>panel/unit/update/${id}`
                    document.querySelector('button[type="submit"]').innerText = "Update"
                }
            ).catch(error => error)
    }
</script>