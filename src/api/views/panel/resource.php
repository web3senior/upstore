<?php
// Which resource will expire in next 5 days
$fiveDaysFromNow = date("Y-m-d", time() + (86400 * 5));
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
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12">
                <div class="alert alert-dark">
                    System will inform you which resource will expire soon(in next 5-day) with <input type="color" value="#ff0000" disabled> color.
                </div>
            </div>

            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li> <?= $this->title ?></li>
                            <li>
                                <a href="javascript: " onclick="location.replace(location.pathname)">
                                    <i class="ms-Icon ms-Icon--Refresh" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                    <form>
                            <input type="number" name="id" placeholder="Resource ID" value="<?= (isset($_GET['id']) ? $_GET['id'] : null) ?>">
                            <input type="submit" value="Search">
                        </form>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <th>ID</th>
                                    <th>unit</th>
                                    <th>created at</th>
                                    <th>creator</th>
                                    <th>owner code</th>
                                    <th>code</th>
                                    <th>name</th>
                                    <th>type</th>
                                    <th>primary amount</th>
                                    <th>created account</th>
                                    <th>maintenance cost</th>
                                    <th>maintenance amount</th>
                                    <th>expire at ⚠️</th>
                                    <th>Actions</th>
                                </thead>
                                <?php
                                foreach ($this->data['data'] as $key => $value) {
                                    $id = $value['resource_id'];
                                ?>
                                    <tr>
                                        <td>#<?= $id ?></td>
                                        <td><?= $value['unit_name'] ?></td>
                                        <td>
                                            <span class="badge badge-warning">
                                                <?= $value['createdAt'] ?>
                                            </span>
                                            <?php
                                            if (date('Y-m-d') === explode(' ', $value['createdAt'])[0]) {
                                                echo '<span class="badge badge-primary"><i class="ms-Icon ms-Icon--StatusCircleCheckmark" aria-hidden="true"></i> Today</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= $value['fullname'] ?></td>
                                        <td><?= $value['owner_code'] ?></td>
                                        <td><?= $value['code'] ?></td>
                                        <td><?= $value['name'] ?></td>
                                        <td><?= $value['type'] ?></td>
                                        <td><?= number_format($value['primary_amount'], 4, '.', '') ?></td>
                                        <td><?= $value['created_account_date'] ?></td>
                                        <td>
                                            <span class="badge badge-light"><?= $value['maintenance_cost'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light"><?= number_format($value['maintenance_amount'], 4, '.', '') ?></span>
                                        </td>
                                        <td class="text-center" style="
                                          <?php
                                            if ((!empty($value['expireAt'])) && (explode(' ', $value['expireAt'])[0] === $fiveDaysFromNow)) {
                                                echo 'color: #de4437;background-color: rgba(222,68,55,.1);';
                                            }
                                            ?>">
                                            <?= (!empty($value['expireAt'])) ? $value['expireAt'] : '<span class="ms-font-xl">∞</span>'; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
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

            <div class="ms-Grid-col ms-sm12 ms-md12">
                <?= $this->title ?> list [<?= (new Paging)->getTotal($this->data['total']); ?>-page]
                <?= (new Paging)->show(PATH_ADMIN . $this->endpoint, $this->data['total'], $this->pg); ?>
            </div>

            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-header">Insert & Update</div>
                    <div class="card-body">
                        <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div class="ms-Grid-row">

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="unit_id">unit<span class="text-danger">*</span></label>
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

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="owner_code" data-required="on">owner code</label>
                                        <input type="number" class="form-control" name="owner_code" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="code" data-required="on">code</label>
                                        <input class="form-control" name="code" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="name" data-required="on">name</label>
                                        <input class="form-control" name="name" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="type" data-required="on">type</label>
                                        <select class="form-control" name="type">
                                            <option value="bank">Bank</option>
                                            <option value="desk">Desk</option>
                                            <option value="vdest">Vdesk</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="primary_amount" data-required="on">primary amount</label>
                                        <input type="number" class="form-control" name="primary_amount" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="created_account_date" data-required="on">created account
                                            date</label>
                                        <input type="datetime-local" class="form-control" name="created_account_date" required>
                                        <span class="validity"></span>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="maintenance_cost" data-required="on">maintenance cost</label>
                                        <select class="form-control" name="maintenance_cost">
                                            <option value="monthly">Monthly</option>
                                            <option value="yearly">Yearly</option>
                                            <option value="per_transaction">Per Transaction</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="maintenance_amount" data-required="on">maintenance amount</label>
                                        <input type="number" class="form-control" name="maintenance_amount" required>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md3">
                                    <div class="form-group">
                                        <label for="expireAt">expire at</label>
                                        <input type="datetime-local" class="form-control" name="expireAt">
                                        <span class="validity"></span>
                                        <small class="input-hint">Empty = permanently</small>
                                    </div>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" style="height: 100px"></textarea>
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
        return await fetch('<?= URL ?>panel/<?= $this->endpoint ?>/fetch/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result)
                loading(false)
                document.querySelectorAll('form')[1].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
                document.querySelector('button[type="submit"]').innerText = "Update"

                document.querySelector('select[name="unit_id"]').value = result.unit_id
                document.querySelector('input[name="owner_code"]').value = result.owner_code
                document.querySelector('input[name="code"]').value = result.code
                document.querySelector('input[name="name"]').value = result.name
                document.querySelector('select[name="type"]').value = result.type
                document.querySelector('input[name="primary_amount"]').value = result.primary_amount
                document.querySelector('input[name="created_account_date"]').value = result.created_account_date
                document.querySelector('select[name="maintenance_cost"]').value = result.maintenance_cost
                document.querySelector('input[name="maintenance_amount"]').value = result.maintenance_amount
                document.querySelector('input[name="expireAt"]').value = result.expireAt
                document.querySelector('textarea[name="description"]').value = result.description
            }).catch(error => error)
    }
</script>