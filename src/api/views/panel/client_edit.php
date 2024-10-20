<?php
$data = $this->data[0];
?>
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
        <div class="ms-Grid">
            <div class="ms-Grid-row">
                <div class="ms-Grid-col ms-sm12 ms-md10">
                    <div class="card">
                        <div class="card-header">update</div>
                        <div class="card-body">
                            <form id="form" action="<?= URL ?>panel/client/update" method="post"
                                  enctype="multipart/form-data" autocomplete="off">
                                <input id="txtId" type="hidden" value="<?= $data["client_id"] ?>" name="client_id">

                                <div class="ms-Grid-row">
                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="company_id">Company Code<span
                                                        class="text-danger">*</span></label>
                                            <select class="form-control" name="citizenship_id">
                                                <option value="">Empty</option>
                                              <?php
                                              foreach ($this->company as $val) {
                                                ?>
                                                  <option value="<?= $val["company_id"] ?>" <?= ($val["company_id"] == $data["company_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
                                                <?php
                                              }
                                              ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="account_type">Account Type<span
                                                        class="text-danger">*</span></label>
                                            <select class="form-control" name="account_type">
                                                <option value="0" <?= ($data["account_type"] == 0) ? 'selected' : null; ?>>
                                                    Customer
                                                </option>
                                                <option value="1" <?= ($data["account_type"] == 1) ? 'selected' : null; ?>>
                                                    Third Party
                                                </option>
                                                <option value="2" <?= ($data["account_type"] == 2) ? 'selected' : null; ?>>
                                                    Colleague
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="nationalcode">National Code<span
                                                        class="text-danger">*</span></label>
                                            <input class="form-control" name="nationalcode"
                                                   value="<?= $data["nationalcode"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="gender">Gender<span class="text-danger">*</span></label>

                                            <select class="form-control" name="gender">
                                                <option <?= ($data["gender"] === 0) ? 'selected' : null; ?>>Male
                                                </option>
                                                <option <?= ($data["gender"] === 1) ? 'selected' : null; ?>>Female
                                                </option>
                                                <option <?= ($data["gender"] === 2) ? 'selected' : null; ?>> Unknown
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="firstname">First Name<span class="text-danger">*</span></label>
                                            <input class="form-control" name="firstname" id="fullname"
                                                   value="<?= $data["firstname"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="lastname">Last Name<span class="text-danger">*</span></label>
                                            <input class="form-control" name="lastname" id="lastname"
                                                   value="<?= $data["lastname"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="citizenship_id">Citizenship Status<span
                                                        class="text-danger">*</span><a
                                                        href="<?= URL ?>panel/citizenship" class="badge badge-primary">Manage</a></label>
                                            <select class="form-control" name="citizenship_id">
                                              <?php
                                              foreach ($this->citizenship as $val) {
                                                ?>
                                                  <option value="<?= $val["citizenship_id"] ?>" <?= ($val["citizenship_id"] == $data["citizenship_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
                                                <?php
                                              }
                                              ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="tel">Tel<span class="text-danger">*</span></label>
                                            <input class="form-control" name="tel" value="<?= $data["tel"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="phone">Phone<span class="text-danger">*</span></label>
                                            <input class="form-control" name="phone" value="<?= $data["phone"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="email">Email<span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email"
                                                   value="<?= $data["email"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="occupation_id">Occupation<span
                                                        class="text-danger">*</span></label>
                                            <select class="form-control" name="occupation_id">
                                              <?php
                                              foreach ($this->occupation as $val) {
                                                ?>
                                                  <option value="<?= $val["occupation_id"] ?>" <?= ($val["occupation_id"] == $data["occupation_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
                                                <?php
                                              }
                                              ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="country_id">Country<span class="text-danger">*</span></label>
                                            <select class="form-control" name="country_id">
                                              <?php
                                              foreach ($this->country as $val) {
                                                ?>
                                                  <option value="<?= $val["country_id"] ?>" <?= ($val["country_id"] == $data["country_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
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
                                                  <option value="<?= $val["state_id"] ?>" <?= ($val["state_id"] == $data["state_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
                                                <?php
                                              }
                                              ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input class="form-control" name="city" value="<?= $data["city"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md12">
                                        <div class="form-group">
                                            <label for="address">Address<span class="text-danger">*</span></label>
                                            <textarea class="form-control"
                                                      name="address"><?= $data["address"] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="zipcode">Zip Code<span class="text-danger">*</span></label>
                                            <input class="form-control" name="zipcode" value="<?= $data["zipcode"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="education_id">Education Level<span class="text-danger">*</span>
                                                <a
                                                        href="<?= URL ?>panel/education"
                                                        class="badge badge-primary">Manage</a></label>
                                            <select class="form-control" name="education_id">
                                              <?php
                                              foreach ($this->education as $val) {
                                                ?>
                                                  <option value="<?= $val["education_id"] ?>" <?= ($val["education_id"] == $data["education_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
                                                <?php
                                              }
                                              ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="idn">Identification document number<span
                                                        class="text-danger">*</span></label>
                                            <input class="form-control" name="idn" value="<?= $data["idn"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="idp">Identification document photo<span
                                                        class="text-danger">*</span></label>
                                            <input type="file" class="form-control" name="idp">
                                            <input type="hidden" name="idp_hidden" value="<?= $data["idp"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="icd">Identification Created Date<span
                                                        class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="icd"
                                                   value="<?= $data["icd"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="ded">Document expiration date<span
                                                        class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="ded"
                                                   value="<?= $data["ded"] ?>">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="exporting">Exporting country<span
                                                        class="text-danger">*</span></label>
                                            <select class="form-control" name="exporting">
                                              <?php
                                              foreach ($this->country as $val) {
                                                ?>
                                                  <option value="<?= $val["country_id"] ?>" <?= ($val["country_id"] == $data["country_id"]) ? 'selected' : null; ?>><?= $val["name"] ?></option>
                                                <?php
                                              }
                                              ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md4">
                                        <div class="form-group">
                                            <label for="picture">Picture</label>
                                            <input type="hidden" name="picture_hidden" value="<?= $data["picture"] ?>">
                                            <input type="file" class="form-control" name="picture">
                                            <a href="<?= URL . 'upload/images/' . $data["picture"] ?>" target="_blank">View</a>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control"
                                                      name="description"><?= $data["description"] ?></textarea>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md12">
                                        <div class="form-group">
                                            <input class="form-control" type="submit" value="Submit">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="ms-Grid-col ms-sm12 ms-md2">
                    <ul class="list-group">
                        <li class="list-group-item">
                            Client ID:
                            <span class="badge badge-danger">#<?= $data["client_id"] ?></span>
                        </li>
                        <li class="list-group-item">
                            <img style="width:200px;"
                                 src="<?= URL ?>upload/images/<?= $data["picture"] ?>"
                                 alt="Picture" class="img-thumbnail">
                        </li>
                        <li class="list-group-item">
                            <img style="width:200px;"
                                 src="<?= URL ?>upload/images/<?= $data["idp"] ?>"
                                 alt="Identification document photo" class="img-thumbnail">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
</section>

<script type="text/javascript">
    window.addEventListener('load', function () {

        CKEDITOR.replace('content', {
            language: 'en',
            uiColor: '#e5a968',
            toolbarCanCollapse: true
        });

    });

    function editRow(id) {
        superagent
            .get('<?= URL ?>panel/blog/edit')
            .query({
                id: id
            })
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