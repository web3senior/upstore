<section>
    <div class="__frame" data-width="xlarge">
        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '
              <script>
                  window.addEventListener(\'DOMContentLoaded\', (event) => {
                      Swal.fire({
                          position: \'top-end\',
                          icon: \'success\',
                          title: \'Record has been added.\',
                          showConfirmButton: false,
                          timer: 1500,
                          timerProgressBar: true,
                      })
                  });
              </script>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <input type="number" name="id" placeholder="Exchange ID" value="<?= (isset($_GET['id']) ? $_GET['id'] : null) ?>">
                            <input type="submit" value="Search">
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <th>Trans. ID(Auto)</th>
                                    <th>Exch. ID</th>
                                    <th>type</th>
                                    <th>create at</th>
                                    <th>Source</th>
                                    <th>amount</th>
                                    <th>unit name</th>
                                    <th>proof</th>
                                    <th>Actions</th>
                                </thead>
                                <?php
                                // جمع دریافتی
                                $get = 0;
                                //جمع واریزی
                                $send = 0;
                                // موقع انجام معامله کاربر گفته من تومان قراره بدم تا تسویه کنم پس نمیتونه از منابع مختلف واریز کنه اینو میشه هنگام ثبت تراکنش چک کرد
                                if (!empty($this->data['data'])) {
                                    foreach ($this->data['data'] as $key => $value) {
                                        $id = $value['transaction_id'];
                                ?>
                                        <tr>
                                            <td>#<?= $id ?></td>
                                            <td>#<?= $value['exchange_id'] ?></td>
                                            <td><?= ($value['type']) ? 'واریزی <i class="ms-Icon ms-Icon--ChevronUp badge badge-danger" aria-hidden="true"></i>' : 'دریافتی <i class="ms-Icon ms-Icon--ChevronDown badge badge-success" aria-hidden="true"></i>' ?></td>
                                            <td><span class="badge badge-warning"><?= $value['trans_createdAt'] ?></span></td>
                                            <td>
                                                <?php
                                                if ($value['type']) {
                                                    $send += $value['trans_amount'];
                                                ?>
                                                    <?= $value['name'] ?><br />
                                                    <a class="text-primary ms-depth-4" href="./resource?id=<?= $value['resource_id'] ?>">show the resource</a>
                                                <?php

                                                } else {
                                                    $get += $value['trans_amount'];
                                                ?>
                                                    <?= $value['account_number'] ?><br />
                                                    <a class="text-secondary ms-depth-4" href="./account?id=<?= $value['account_id'] ?>">show the client account</a>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <td><?= number_format($value['trans_amount'], 2, '.', ',') ?></td>
                                            <td><?= $value['unit_name'] ?></td>
                                            <td class="text-center">
                                                <?php if ((empty($value['proof']) || !file_exists('upload/images/' . $value['proof']))) : ?>
                                                    <span class="badge badge-light">EMPTY</span>
                                                <?php else : ?>
                                                    <a target="_blank" href="<?= URL ?>upload/images/<?= $value['proof'] ?>" class="btn-more">
                                                        <?= ICON_OPEN ?>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <a href="javascript: " onclick="editRow(<?= $id ?>,<?= ($value['type']) ? 1 : 2; ?>)" hidden><?= ICON_EDIT ?></a>
                                                    <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="9" class="ms-fontSize-18 ms-fontWeight-bold text-center">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    مبلغی که مشتری باید پرداخت کند:
                                                    <?= number_format($value['total_customer'], 2, '.', ',') ?>
                                                </li>
                                                <li class="list-group-item">
                                                    مبلغی که صرافی باید به مشتری پرداخت کند:
                                                    <?= number_format($value['exchange_amount'], 2, '.', ',') ?>
                                                </li>
                                                <li class="list-group-item list-group-item-success">
                                                    جمع دریافتی:
                                                    <?= number_format($get, 2, '.', ',') ?>
                                                </li>
                                                <li class="list-group-item list-group-item-danger">
                                                    جمع واریزی:
                                                    <?= number_format($send, 2, '.', ',') ?>
                                                </li>
                                                <li class="list-group-item list-group-item-info">
                                                    مانده حساب دریافتی:
                                                    <?= number_format(($value['total_customer'] - $get), 2, '.', ',') ?>
                                                </li>
                                                <li class="list-group-item list-group-item-info">
                                                    مانده حساب واریزی:
                                                    <?= number_format(($value['exchange_amount'] - $send), 2, '.', ',') ?>
                                                </li>
                                            </ul>

                                            <p class="alert alert-secondary">
                                                بستن معامله زمانی امکان پذیر است که هر دو مانده حساب بالا صفر شود
                                            </p>
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

            <div class="ms-Grid-col ms-sm12 ms-md12">
                <div class="card">
                    <div class="card-header">Exchange information</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exchange_id" data-required="on">exchange ID</label>
                            <select class="form-control" name="exchange_id" required autocomplete="off">
                                <option value="">--Select exchange ID--</option>
                                <?php
                                foreach ($this->exchange as $val) {
                                ?>
                                    <option value="<?= $val["exchange_id"] ?>"><?= $val["exchange_id"] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div id="reportUserInfo" class="text-danger"></div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="ms-Grid-col ms-sm12 ms-md6">
                <div class="card">
                    <div class="card-header">
                        Insert/ Update
                        |
                        <mark>From office</mark>
                    </div>
                    <div class="card-body">
                        <a class="badge badge-pill badge-dark" href="./resource">view all resources</a>
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert?type=1" method="post" enctype="multipart/form-data" autocomplete="off">
                            <!-- مشخص کردن واریزی و دریافتی -->
                            <input type="hidden" name="type" value="1">
                            <input type="hidden" name="exchange_id" value="">
                            <div class="ms-Grid-row">
                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <label for="resource_id" data-required="on">Resource ID</label>
                                        <select class="form-control" name="resource_id" size="6" required>
                                            <option value="">--Select Resource--</option>
                                            <?php
                                            foreach ($this->resource as $val) {
                                            ?>
                                                <option data-unit-id="<?= $val['unit_id'] ?>" value="<?= $val['resource_id'] ?>"><?= "#" . $val['resource_id'] . ' | ' . $val['name'] . ' | Primary Amount:' . $val['primary_amount'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div id="reportResourceCount" class="text-info"></div>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <label for="unit_id_select_office" data-required="on">unit name</label>
                                        <select class="form-control" name="unit_id_select_office" disabled>
                                            <option value="">--Waiting for exchange ID--</option>
                                            <?php
                                            foreach ($this->unit as $val) {
                                            ?>
                                                <option value="<?= $val["unit_id"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" id="unit_id_office" name="unit_id">
                                        <span class="input-hint">This field is read-only thus you can't change it.</span>
                                        <span id="reportAUnitId" class="text-info"></span>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md6">
                                    <div class="form-group">
                                        <label for="amount" data-required="on">Amount</label>
                                        <input class="form-control" name="amount" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md6">
                                    <div class="form-group">
                                        <label for="proof">Proof</label>
                                        <input type="file" class="form-control" name="proof">
                                        <input type="hidden" name="proof_hidden">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md6">
                                    <div class="form-group">
                                        <label for="createdAt" data-required="on">created at</label>
                                        <span class="badge badge-primary" style="cursor: pointer" onclick="setNowDateTime(0)">NOW</span>
                                        <input type="datetime-local" class="form-control" name="createdAt" required>
                                        <span class="validity"></span>
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
            <div class="ms-Grid-col ms-sm12 ms-md6">
                <div class="card">
                    <div class="card-header">Insert/ Update | <mark>From client</mark></div>
                    <div class="card-body">
                        <a class="badge badge-pill badge-dark" href="./account">view all accounts</a>
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert?type=0" method="post" enctype="multipart/form-data" autocomplete="off">
                            <!-- مشخص کردن واریزی و دریافتی -->
                            <input type="hidden" name="type" value="0">
                            <input type="hidden" name="exchange_id" value="">
                            <div class="ms-Grid-row">
                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <label for="account_id" data-required="on">Account id</label>
                                        <select class="form-control" name="account_id" size="6" required>
                                            <option value="">--Select exchange ID--</option>
                                        </select>
                                        <div id="reportAccountCount" class="text-info"></div>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <label for="unit_id_select_client" data-required="on">unit name</label>
                                        <select class="form-control" name="unit_id_select_client" disabled>
                                            <option value="">--Waiting for exchange ID--</option>
                                            <?php
                                            foreach ($this->unit as $val) {
                                            ?>
                                                <option value="<?= $val["unit_id"] ?>"><?= $val["name"] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" id="unit_id_client" name="unit_id">
                                        <span class="input-hint">This field is read-only thus you can't change it.</span>
                                        <span id="reportAUnitId" class="text-info"></span>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md6">
                                    <div class="form-group">
                                        <label for="amount" data-required="on">Amount</label>
                                        <input class="form-control" name="amount" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md6">
                                    <div class="form-group">
                                        <label for="proof">Proof</label>
                                        <input type="file" class="form-control" name="proof">
                                        <input type="hidden" name="proof_hidden">
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md6">
                                    <div class="form-group">
                                        <label for="createdAt" data-required="on">created at</label>
                                        <span class="badge badge-primary" style="cursor: pointer" onclick="setNowDateTime(1)">NOW</span>
                                        <input type="datetime-local" class="form-control" name="createdAt" required>
                                        <span class="validity"></span>
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
    window.addEventListener("load", () => {
        document.querySelector('select[name="exchange_id"]').value = <?= (isset($_GET['id']) ? $_GET['id'] : '""'); ?>;
        exchangeChanged(document.querySelector('select[name="exchange_id"]').value)
    })




    document.querySelector('select[name="exchange_id"]').addEventListener('change', async (event) => {
        exchangeChanged(event.target.value)
    })

    const exchangeChanged = async (value) => {
        console.log(value)
        if (value !== '') {
            document.querySelectorAll('input[name="exchange_id"]')[0].value = value
            document.querySelectorAll('input[name="exchange_id"]')[1].value = value
            loading(true)
            await fetch('<?= URL ?>panel/exchange/fetchUserBank/' + value)
                .then(response => response.json())
                .then((response) => {
                    document.querySelector('#reportUserInfo').innerHTML =
                        `
This exchange has been created for <span class="badge badge-danger">${response[0].firstname} ${response[0].lastname} </span> with client ID #${response[0].client_id}
<p class="text-secondary">⚠️Check the all information one more time to make sure that you are submiting the transaction to the right customer because it will affect your cashbox & resources amount.</p>
                    `
                    document.querySelector('#reportAccountCount').innerHTML = `<span class="badge badge-info">${response.length}</span> accounts founded.`
                    document.querySelector('select[name="account_id"]').options.length = 0;
                    response.forEach((account) => {
                        let opt = document.createElement('option');
                        opt.value = account.account_id;
                        opt.setAttribute('data-unit-id', account.unit_id)
                        opt.innerHTML = '#' + account.account_id + ') ' + account.account_number + ' | ' + account.name + '(' + account.unit_id + ')';
                        document.querySelector('select[name="account_id"]').appendChild(opt);
                    })
                })
                .then(() => loading(false))
        }
    }

    document.querySelector('select[name="account_id"]').addEventListener('change', (event) => {
        let unitID = event.target.options[event.target.selectedIndex].getAttribute('data-unit-id')
        console.log(unitID)
        if (unitID !== null) {
            document.querySelector('select[name="unit_id_select_client"]').value = unitID
            document.querySelector('#unit_id_client').value = unitID
        }
    })

    document.querySelector('select[name="resource_id"]').addEventListener('change', (event) => {
        let unitID = event.target.options[event.target.selectedIndex].getAttribute('data-unit-id')
        console.log(unitID)
        if (unitID !== null) {
            document.querySelector('select[name="unit_id_select_office"]').value = unitID
            document.querySelector('#unit_id_office').value = unitID
        }
    })

    const setNowDateTime = (el_id) => {
        let dateTime = '<?php echo date('Y-m-d H:i:00', time()) ?>';
        document.querySelectorAll('input[name="createdAt"]')[el_id].value = dateTime.toString()
    }

    const loading = (state = false) => {
        document.querySelector("#loading").style.opacity = state ? "1" : "0"
        document.querySelector("#loading").style.visibility = state ? "visible" : "hidden"
    }

    const editRow = async (id, frmID) => {
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
                document.querySelectorAll('form')[frmID].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
                document.querySelector('button[type="submit"]').innerText = "Update"
                document.querySelector('input[name="name"]').value = result.name
                document.querySelector('input[name="code"]').value = result.code
                document.querySelector('input[name="logo_hidden"]').value = result.logo
                document.querySelector('select[name="country_id"]').value = result.country_id
                document.querySelector('input[name="state_id"]').value = result.state_id
                document.querySelector('input[name="city"]').value = result.city
            }).catch(error => error)
    }

    document.querySelectorAll('form').forEach((frm, index) => {
        if (index > 0) {
            frm.addEventListener('submit', (e) => {
                e.preventDefault();
                if (document.querySelector('select[name="exchange_id"]').value) frm.submit()
                else {
                    alert('First, please opt the exchange ID.')
                    document.querySelector('select[name="exchange_id"]').focus()
                }
            })
        }
    })
</script>