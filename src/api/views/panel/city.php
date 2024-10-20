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
            <div class="ms-Grid-col ms-sm6">
                <div class="card">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li><?= $this->title ?></li>
                            <li>
                                <a href="javascript: " onclick="location.replace(location.pathname)">
                                    <i class="ms-Icon ms-Icon--Refresh" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-sm">
                                <thead>
                                <th>ID</th>
                                <th>State ID</th>
                                <th>name</th>
                                <th>actions</th>
                                </thead>
                              <?php
                              foreach ($this->data as $key => $value) {
                                $id = $value['city_id'];
                                ?>
                                  <tr>
                                      <td><?= $id ?></td>
                                      <td><?= $value['state_id'] ?></td>
                                      <td><?= $value['name'] ?></td>
                                      <td>
                                          <div class="d-flex justify-content-between">
                                              <a href="javascript: "
                                                 onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                              <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
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
            <div class="ms-Grid-col ms-sm6 sticky">
                <div class="card">
                    <div class="card-header">Insert/ Update</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post"
                              enctype="multipart/form-data"
                              autocomplete="off">
                            <div class="ms-Grid-row">
                            <div class="ms-Grid-col ms-sm12">
                                    <div class="form-group">
                                        <label for="country_id" data-required="on">country id</label>
                                        <input class="form-control" name="country_id">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12">
                                    <div class="form-group">
                                        <label for="country_id" data-required="on">state id</label>
                                        <input class="form-control" name="state_id">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12">
                                    <div class="form-group">
                                        <label for="ceo" data-required="on">name</label>
                                        <input class="form-control" name="name">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <button type="submit">Add</button>
                                    </div>
                                </div>
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
        return await fetch('<?= URL ?>panel/<?= $this->endpoint ?>/info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                    console.log(result)
                    loading(false)
                    document.querySelector('form').action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
                    document.querySelector('button[type="submit"]').innerText = "Update"
                    document.querySelector('input[name="country_id"]').value = result.country_id
                    document.querySelector('input[name="name"]').value = result.name
                }
            ).catch(error => error)
    }
</script>