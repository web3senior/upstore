<section>
    <div class="__frame" data-width="xlarge">

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
                                <caption>List of <?= $this->title ?></caption>
                                <thead>
                                <th>ID/Name</th>
                                <th>Logo</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Branch Code</th>
                                <th>Type</th>
                                <th>Actions</th>
                                </thead>
                              <?php
                              foreach ($this->data['data'] as $key => $value) {
                                $id = $value['bank_id'];
                                ?>
                                  <tr>
                                      <td>
                                          #<?= $id ?>) <?= $value['name'] ?>
                                      </td>
                                      <td class="text-center">
                                        <?php if ((empty($value['logo']) || !file_exists('upload/images/' . $value['logo']))) : ?>
                                            <img class="img-responsive img-thumbnail rounded"
                                                 src="<?= URL ?>public/images/apple-touch-icon.png" alt="default"
                                                 style="height:70px;">
                                        <?php else : ?>
                                            <a data-fancybox="gallery" data-type="image"
                                               data-caption="<?= $value['name'] ?>"
                                               href="<?= URL; ?>uploads/images/<?= $value['logo'] ?>" class="btn-more">
                                                <img class="img-responsive img-thumbnail rounded"
                                                     src="<?= URL ?>upload/images/<?= $value['logo'] ?>"
                                                     alt="<?= $value['name'] ?>"
                                                     style="height:70px;">
                                            </a>
                                        <?php endif; ?>
                                      </td>
                                      <td><?= $value['country_name'] ?></td>
                                      <td><?= $value['state_name'] ?></td>
                                      <td><?= $value['city'] ?></td>
                                      <td><?= $value['code'] ?></td>
                                      <td><span class="badge badge-danger"><?= $value['type'] ?></span></td>
                                      <td>
                                          <div class="d-flex justify-content-between">
                                              <a href="javascript: "
                                                 onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                              <a href="<?= URL ?>panel/bank/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
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
                        <form action="<?= URL ?>panel/bank/insert" method="post" enctype="multipart/form-data"
                              autocomplete="off">
                            <div class="ms-Grid-row">
                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="name">Name<span
                                                    class="text-danger">*</span></label>
                                        <input class="form-control" name="name">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="logo">Logo</label>
                                        <input type="file" class="form-control" name="logo">
                                        <input type="hidden" name="logo_hidden">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="type">
                                            type
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control" name="type">
                                            <option value="private">Private</option>
                                            <option value="governmental">Governmental</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="code">Branch Code<span
                                                    class="text-danger">*</span></label>
                                        <input class="form-control" name="code">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="country_id">Country<span class="text-danger">*</span></label>
                                        <select class="form-control" name="country_id">
                                          <?php
                                          foreach ($this->country as $val) {
                                            ?>
                                              <option value="<?= $val["country_id"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="state_id">State<span class="text-danger">*</span></label>
                                        <select class="form-control" name="state_id">
                                          <?php
                                          foreach ($this->state as $val) {
                                            ?>
                                              <option value="<?= $val["state_id"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input class="form-control" name="city" list="cities">
                                        <datalist id="cities">
                                          <?php
                                          foreach ($this->city as $val) {
                                            ?>
                                              <option value="<?= $val["name"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                          }
                                          ?>
                                        </datalist>
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
        return await fetch('<?= URL ?>panel/bank/info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                    console.log(result)
                    loading(false)
                    document.querySelector('form').action = `<?= URL ?>panel/bank/update/${id}`
                    document.querySelector('button[type="submit"]').innerText = "Update"
                    document.querySelector('input[name="name"]').value = result.name
                    document.querySelector('input[name="code"]').value = result.code
                    document.querySelector('input[name="logo_hidden"]').value = result.logo
                    document.querySelector('select[name="country_id"]').value = result.country_id
                    document.querySelector('input[name="state_id"]').value = result.state_id
                    document.querySelector('input[name="city"]').value = result.city

                }
            ).catch(error => error)
    }
</script>