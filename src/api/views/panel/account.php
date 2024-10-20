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
                        <form>
                            <input type="number" name="id" placeholder="Account Number"
                                   value="<?= (isset($_GET['id']) ? $_GET['id'] : null) ?>">
                            <input type="submit" value="Search">
                        </form>
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered">
                                <thead>
                                <th>ID</th>
                                <th>Owner Type</th>
                                <th>Account Number</th>
                                <th>IBAN</th>
                                <th>card number</th>
                                <th>bank info</th>
                                <th>account type</th>
                                <th>unit name</th>
                                <th>client info</th>
                                <th>card image</th>
                                <th>description</th>
                                <th>Actions</th>
                                </thead>
                              <?php
                              foreach ($this->data['data'] as $key => $value) {
                                $id = $value['account_id'];
                                $status = $value["account_status"];
                                $status_text = ($status) ? ICON_ACTIVE : ICON_DEACTIVE;
                                ?>
                                  <tr>
                                      <td>#<?= $id ?></td>
                                      <td class="text-center">
                                        <?= ($value['owner_type'] == "own") ? "<span class='badge badge-success'>" . $value["owner_type"] . "</span>" : "<span class='badge badge-danger'>" . $value["owner_type"] . "</span>" ?>
                                      </td>
                                      <td><?= $value['account_number'] ?></td>
                                      <td><?= $value['iban'] ?></td>
                                      <td><?= $value['card_number'] ?></td>
                                      <td>
                                          <img class="v-m"
                                               src="<?= URL ?>upload/images/<?= $value['logo'] ?>" alt="logo"
                                               style="width:20px;height: 20px">
                                        <?= $value['bank_name'] ?>
                                      </td>
                                      <td><?= $value['account_type'] ?></td>
                                      <td><?= $value['unit_name'] ?></td>
                                      <td>
                                          #<?= $value['client_id'] . ' ' . $value['firstname'] . ' ' . $value['lastname'] ?></td>
                                      <td class="text-center">
                                        <?php if ((empty($value['card_image']) || !file_exists('upload/images/' . $value['card_image']))) : ?>
                                            <img class="img-responsive img-thumbnail rounded"
                                                 src="<?= URL ?>public/images/apple-touch-icon.png" alt="default"
                                                 style="height:30px;">
                                        <?php else : ?>
                                            <a data-fancybox="gallery" data-type="image"
                                               data-caption="<?= $value['name'] ?>"
                                               href="<?= URL; ?>uploads/images/<?= $value['card_image'] ?>"
                                               class="btn-more">
                                                <img class="img-responsive rounded"
                                                     src="<?= URL ?>upload/images/<?= $value['card_image'] ?>"
                                                     alt="<?= $value['name'] ?>"
                                                     style="height:30px;">
                                            </a>
                                        <?php endif; ?>
                                      </td>
                                      <td><?= $value['description'] ?></td>
                                      <td>
                                          <div class="d-flex justify-content-between">
                                              <a href="javascript: "
                                                 onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                              <a href="<?= URL ?>panel/account/status?id=<?= $id ?>&val=<?= ($status) ? '0' : '1' ?>">
                                                <?= $status_text ?>
                                              </a>
                                              <a href="<?= URL ?>panel/account/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
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
                        <form action="<?= URL ?>panel/account/insert" method="post" enctype="multipart/form-data"
                              autocomplete="off">
                            <div class="ms-Grid-row">
                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="owner_type" data-required="on">Owner Type</label>
                                        <select class="form-control" name="owner_type">
                                            <option value="own">Own</option>
                                            <option value="third-party">Third Party</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="name" data-required="on">Account Number</label>
                                        <input class="form-control" name="account_number">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="iban" data-required="on">IBAN</label>
                                        <input class="form-control" name="iban">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="card_number" data-required="on">Card Number</label>
                                        <input class="form-control" name="card_number">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="bank_id" data-required="on">Bank</label>
                                        <select class="form-control" name="bank_id">
                                          <?php
                                          foreach ($this->bank as $val) {
                                            ?>
                                              <option value="<?= $val["bank_id"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="account_type" data-required="on">Account Type</label>
                                        <select class="form-control" name="account_type">
                                            <option value="personal">Personal</option>
                                            <option value="company">Company</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="unit_id" data-required="on">unit name</label>
                                        <select class="form-control" name="unit_id">
                                          <?php
                                          foreach ($this->unit as $val) {
                                            ?>
                                              <option value="<?= $val["unit_id"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="client_id" data-required="on">client</label>
                                        <select class="form-control" name="client_id">
                                          <?php
                                          foreach ($this->client as $val) {
                                            ?>
                                              <option value="<?= $val["client_id"] ?>"><?= $val["firstname"] . ' ' . $val["lastname"] . ' (#' . $val["client_id"] . ')' ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md4">
                                    <div class="form-group">
                                        <label for="card_image">Card Image</label>
                                        <input type="file" class="form-control" name="card_image">
                                        <input type="hidden" name="card_image_hidden">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description"></textarea>
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
        return await fetch('<?= URL ?>panel/account/info/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                    console.log(result)
                    loading(false)
                    document.querySelectorAll('form')[1].action = `<?= URL ?>panel/account/update/${id}`
                    document.querySelector('button[type="submit"]').innerText = "Update"
                    document.querySelector('select[name="owner_type"]').value = result.owner_type
                    document.querySelector('input[name="account_number"]').value = result.account_number
                    document.querySelector('input[name="iban"]').value = result.iban
                    document.querySelector('input[name="card_number"]').value = result.card_number
                    document.querySelector('select[name="bank"]').value = result.bank_id
                    document.querySelector('select[name="account_type"]').value = result.account_type
                    document.querySelector('input[name="unit_id"]').value = result.unit_id
                    document.querySelector('input[name="client_id"]').value = result.client_id
                    document.querySelector('input[name="card_image_hidden"]').value = result.card_image
                    document.querySelector('textarea[name="description"]').value = result.description
                }
            ).catch(error => error)
    }
</script>