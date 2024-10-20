<section class="dashboard" id="category">
    <div class="container-fluid">

      <?php
      if (isset($_GET['update'])) {
        $update = $_GET['update'];
        if ($update)
          echo '
<script>
    window.addEventListener(\'DOMContentLoaded\', (event) => {
        Swal.fire({
            position: \'top-end\',
            icon: \'success\',
            title: \'Password has been updated.\',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        })
    });
</script>
';
        else
          echo '
<script>
    window.addEventListener(\'DOMContentLoaded\', (event) => {
        Swal.fire({
            position: \'top-end\',
            icon: \'error\',
            title: "' . $_GET['msg'] . '",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        })
    });
</script>
';
      }
      ?>
        <div class="row sm">
            <div class="col xl-12">
                <div class="card">
                    <div class="card-header">
                        <span class="material-icons text-success">امنیت</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span><?= json_decode($_COOKIE['admin_info'])->email ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col xl-12">
                <div class="card">
                    <div class="card-header">
                        <p class="alert alert-secondary">
                        حدس زدن رمزهای عبور خوب سخت است. از کلمات نامتعارف یا جوک های درونی، حروف بزرگ غیر استاندارد، املای خلاقانه، و اعداد و نمادهای غیر آشکار استفاده کنید.
                        </p>
                    </div>
                    <div class="card-body">
                        <form class="form" action="<?= URL; ?>panel/security?mod=update" method="post"
                              name="form">

                            <input id="txtId" type="hidden" value="<?= json_decode($_COOKIE['admin_info'])->email ?>"
                                   name="email" id="email">

                            <div class="form-group">
                                <label for="question">گذرواژه جاری</label>
                                <input class="form-control" type="password" name="password" id="password"
                                       pattern=".{3,}" required>
                            </div>

                            <div class="form-group">
                                <label for="question">گذرواژه جدید</label>
                                <input class="form-control" type="text" name="newpassword" id="newpassword"
                                       pattern=".{3,}" required>
                            </div>

                            <div class="form-group">
                                <label for="question">گذرواژه جدید</label>
                                <input class="form-control" type="text" name="repassword" id="repassword"
                                       pattern=".{3,}" required>
                            </div>

                            <button id="btnSubmit" type="submit" class="btn btn-warning mb-2" id="btnUpdate">
                                بروزرسانی
                            </button>
                        </form>
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
        document.querySelector('#loading').classList.remove('none');

        superagent
            .get('<?= URL ?>panel/slider/info')
            .query({id: id})
            .on('progress', function (e) {
            })
            .then(res => {
                document.querySelector('#loading').classList.add('none');
                let obj = JSON.parse(res['text'])[0];
                document.querySelector('#txtId').value = obj.slider_id;
                document.querySelector('#alt').value = obj.alt;
                document.querySelector('#url').value = obj.url;
                document.querySelector('#form').action = "<?= URL ?>panel/slider/update";
                document.querySelector('#btnSubmit').innerText = "Update";
            });
    }
</script>