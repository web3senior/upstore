<?php
$data = $this->data[0];
$about = $data['about'];
$content = $data['contact'];
$faq = $data['faq'];
$goodreturn = $data['goodreturn'];
$howtouse = $data['howtouse'];
$privacy = $data['privacy'];
$job = $data['job'];
?>
<section>
    <div class="container-fluid">
      <?php
      if (isset($_GET['update'])) {
        $update = $_GET['update'];
        if ($update == 1)
          echo '<p class="alert alert-success">Record has been updated.</p>';
        else
          echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
      }
      ?>
        <div class="row xl">
            <div class="col xl-12">
                <div class="card">
                    <div class="card-header">
                        update rows
                    </div>
                    <div class="card-body">
                        <form id="form" action="<?= URL ?>panel/page/update"
                              method="post"
                              enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="about">about</label>
                                <textarea class="form-control rtl text-right" name="about" id="about"
                                          required><?= $about ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">contact</label>
                                <textarea class="form-control" name="contact" id="econtact" rows="10" cols="80"
                                          required><?= $content ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">faq</label>
                                <textarea class="form-control" name="faq" id="faq" rows="10" cols="80"
                                          required><?= $faq ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">goodreturn</label>
                                <textarea class="form-control" name="goodreturn" id="goodreturn" rows="10" cols="80"
                                          required><?= $goodreturn ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">howtouse</label>
                                <textarea class="form-control" name="howtouse" id="howtouse" rows="10" cols="80"
                                          required><?= $howtouse ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">privacy</label>
                                <textarea class="form-control" name="privacy" id="privacy" rows="10" cols="80"
                                          required><?= $privacy ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="answer">job</label>
                                <textarea class="form-control" name="job" id="job" rows="10" cols="80"
                                          required><?= $job ?></textarea>
                            </div>
                            <input id="btnSubmit" type="submit" class="btn btn-warning mb-2" value="Update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    window.onload = () => {
        let pages = ['about', 'econtact', 'faq', 'goodreturn', 'howtouse', 'privacy', 'job'];
        pages.forEach(i => {
            CKEDITOR.replace(i,
                {
                    language: 'fa',
                    uiColor: '#25abe8'
                });
        })
    }


    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script>