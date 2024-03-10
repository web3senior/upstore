<section>
    <div class="__frame" data-width="medium">

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
                        <p class="text-warning">In this page, you can't edit password. First you need login with user information then change the password from security page.</p>
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-sm">
                                <thead>
                                <th>fullname</th>
                                <th>email</th>
                                <th>password</th>
                                <th>actions</th>
                                </thead>
                              <?php
                              foreach ($this->data as $key => $value) {
                                $id = $value['admin_id'];
                                ?>
                                  <tr>
                                      <td><?= $value['fullname'] ?></td>
                                      <td><?= $value['email'] ?></td>
                                      <td>***</td>
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
            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-header">Insert/ Update</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post"
                              enctype="multipart/form-data"
                              autocomplete="off">
                            <div class="ms-Grid-row">
                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="ceo" data-required="on">fullname</label>
                                        <input class="form-control" name="fullname">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="fax" data-required="on">email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="password" data-required="on">password</label>
                                        <input type="tel" class="form-control" name="password" required>
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
                    document.querySelector('input[name="fullname"]').value = result.fullname
                    document.querySelector('input[name="email"]').value = result.email
                document.querySelector('input[name="password"]').value = result.password
                }
            ).catch(error => error)
    }
</script>