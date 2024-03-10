<?php
$data = $this->data;
?>
<section class="dashboard" id="category">
    <div class="container-fluid">

        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '<p class="alert alert-success">Record has been added.</p>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="row xl">
            <div class="col xl-6">
                <div class="card">
                    <div class="card-header">
                        <img class="none" id="loading" src="<?= URL ?>public/images/loading.gif" alt="loading">
                        Operation
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/category/insert" method="post">
                            <input id="txtId" type="hidden" value="" name="id">
                            <div class="form-group">
                                <label for="question">Name</label>
                                <textarea class="form-control" name="name" id="name" rows="4"
                                          required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="question">Cover</label>
                                <textarea class="form-control" name="cover" id="cover" rows="4"
                                          required></textarea>
                            </div>
                            <button id="btnSubmit" type="submit" class="btn btn-primary mb-2" id="btnInsert">Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="table table-bordered table-responsive">
                                <caption>List of <?= $this->title ?></caption>
                                <thead>
                                <th class="text-center">#</th>
                                <th>name</th>
                                <th>cover</th>
                                <th>command</th>
                                </thead>
                                <?php
                                foreach ($data as $key => $value) {
                                    $id = $value['category_id'];
                                    $name = $value['name'];
                                    $cover = $value['cover'];
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= ++$key ?></td>
                                        <td><?= $name ?></td>
                                        <td>
                                            <a class="badge badge-light" target="_blank"
                                               href="<?= URL; ?>uploads/images/<?= $cover; ?>"><?=ICON_OPEN?></a>
                                        </td>
                                        <td>
                                            <a class="text-warning" href="javascript: " onclick="editRow(<?= $id ?>)"
                                            >
                                                <?= ICON_EDIT ?>
                                            </a>
                                            <a class="text-danger" href="<?= URL ?>panel/category/delete?id=<?= $id ?>">
                                                <?= ICON_DELETE ?>
                                            </a>
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
        </div>
    </div>
</section>
<script type="text/javascript">
    window.addEventListener('load', function () {
    });

    function editRow(id) {
        superagent
            .get('<?= URL ?>panel/category/info')
            .query({id: id})
            .on('progress', function (e) {
                /* console.log(e.direction,"is done",e.percent,"%");*/
                document.querySelector('#loading').classList.remove('none');
            })
            .then(res => {
                document.querySelector('#loading').classList.add('none');
                let obj = JSON.parse(res['text'])[0];
                document.querySelector('#txtId').value = obj.category_id;
                document.querySelector('#name').value = obj.name;
                document.querySelector('#cover').value = obj.cover;
                document.querySelector('#form').action = "<?= URL ?>panel/category/update";
                document.querySelector('#btnSubmit').innerText = "Update";
            });
    }
</script>