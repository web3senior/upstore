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
                        <div class="tabs">
                            <?php
                            foreach ($this->data['group_name'] as $key => $val) {
                                $group_name = $val['group_name'];
                            ?>

                                <div class="tab">
                                    <input type="radio" id="tab-<?= $key + 1 ?>" name="tab-group" <?= ($key === 0) ? 'checked' : null; ?>>
                                    <label for="tab-<?= $key + 1 ?>"><?= $group_name ?></label>
                                    <div class="content">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped">
                                                <thead>
                                                    <th>ID</th>
                                                    <th>unit</th>
                                                    <th>quantity</th>
                                                    <th>totalline</th>
                                                    <th>Actions</th>
                                                </thead>
                                                <?php
                                                foreach ($this->data['data'] as $key => $value) {
                                                    $id = $value['cashbox_id'];
                                                    if ($value['group_name'] === $val['group_name']) {
                                                ?>
                                                        <tr>
                                                            <td><?= $id ?></td>
                                                            <td class="text-center">
                                                                <?php if ((empty($value['unit']) || !file_exists('upload/images/' . $value['unit']))) : ?>
                                                                    <img class="img-responsive img-thumbnail rounded" src="<?= URL ?>public/images/apple-touch-icon.png" alt="default" style="height:40px;">
                                                                <?php else : ?>
                                                                    <a data-fancybox="gallery" data-type="image" data-caption="<?= $value['unit'] ?>" href="<?= URL; ?>uploads/images/<?= $value['unit'] ?>" class="btn-more">
                                                                        <img class="img-responsive img-thumbnail rounded" src="<?= URL ?>upload/images/<?= $value['unit'] ?>" alt="<?= $value['unit'] ?>" style="height:40px;">
                                                                    </a>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?= $value['quantity'] ?></td>
                                                            <td><?= number_format($value['totalline'], 2, '.', ',') ?></td>
                                                            <td>
                                                                <div class="d-flex justify-content-between">
                                                                    <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                                    <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <span class="badge badge-info ms-fontSize-18">Total: <?= number_format($val['total'], 2, '.', ',') ?>$</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            <?php
                            }
                            ?>
                        </div>
                        <div class="text text-primary mb-40 ms-fontWeight-bold ms-fontSize-18 mt-40 text-center">
                            Total: <?= number_format(($this->data['sum'] ? $this->data['sum'] : 0), 2, '.', ',') ?>$
                        </div>
                    </div>
                </div>

                <div class="ms-Grid-col ms-sm12">
                    <div class="card">
                        <div class="card-header">Insert & Update</div>
                        <div class="card-body">
                            <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                                <div class="ms-Grid-row">
                                    <div class="ms-Grid-col ms-sm12 ms-md3">
                                        <div class="form-group">
                                            <label for="unit" data-required="on">unit</label>
                                            <input type="file" class="form-control" name="unit">
                                            <input type="hidden" name="unit_hidden">
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md3">
                                        <div class="form-group">
                                            <label for="quantity" data-required="on">quantity</label>
                                            <input type="number" class="form-control" name="quantity" required>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md3">
                                        <div class="form-group">
                                            <label for="totalline" data-required="on">totalline</label>
                                            <input type="number" class="form-control" name="totalline" required>
                                        </div>
                                    </div>

                                    <div class="ms-Grid-col ms-sm12 ms-md3">
                                        <div class="form-group">
                                            <label for="group_name" data-required="on">Group Name</label>
                                            <input class="form-control" name="group_name" required>
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
                document.querySelector('input[name="unit_hidden"]').value = result.unit
                document.querySelector('input[name="quantity"]').value = result.quantity
                document.querySelector('input[name="totalline"]').value = result.totalline
                document.querySelector('input[name="group_name"]').value = result.group_name

            }).catch(error => error)
    }
</script>