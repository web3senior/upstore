<?php ?>
<section>
    <div class="container-fluid">
        <div class="row xl">
            <div class="col xl-6">
                <div class="inner">
                    <div class="card">
                        <div class="card-header">
                            <span class="material-icons">add_photo_alternate</span>
                        </div>
                        <div class="card-body">
                            <form class="form f-tahoma" action="<?= URL; ?>panel/upload?mod=up"
                                  method="post" name="form" enctype="multipart/form-data">

                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <span class="badge badge-pill badge-primary">.jpg</span>
                                        <span class="badge badge-pill badge-primary">.gif</span>
                                        <span class="badge badge-pill badge-primary">.bmp</span>
                                        <span class="badge badge-pill badge-primary">.png</span>
                                        <span class="badge badge-pill badge-warning">.webp</span>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-group">
                                            <label class="badge badge-danger mb-10" for="avatar">Choose Your
                                                File:</label>

                                            <input type="file" class="form-control-file" id="fileup" name="fileup">
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <button type="submit" class="btn btn-success">Upload and get URL</button>
                                    </li>
                                  <?php
                                  (new Session)->init();
                                  $fileName = (new Session)->get('upload');
                                  if (!empty($fileName)) {
                                    ?>
                                      <li class="list-group-item">
                                          <div class="alert alert-success text-left">
                                              <div class="text-center">
                                                  <img class="shadow" alt=""
                                                       src="<?= URL; ?>upload/images/<?= $fileName; ?>"
                                                       style="max-width:200px;max-height:200px;border: 3px solid #f1b0b7"/>

                                                  <br/><br/>
                                                  <label class="badge badge-danger">این آدرس و نام اصلی فایل آپلود شده
                                                      می
                                                      باشد:</label>
                                                  <br/><br/>
                                                  <span class="text-danger">Name:</span>
                                                  <input class="f-latoBold ltr" type="text" onclick="this.select()"
                                                         value="<?= $fileName; ?>"/>
                                                  <br/><br/>
                                                  <span class="text-danger">URL:</span>
                                                  <input class="f-latoBold ltr" type="text" onclick="this.select()"
                                                         value="<?= URL . 'upload/images/' . $fileName; ?>"/>
                                              </div>
                                          </div>
                                      </li>
                                    <?php
                                  }
                                  ?>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script language="JavaScript" type="text/javascript">
    loadprovince();

    $(".province").change(function () {
        $(this).closest('div').find('.city').addClass("tmp");
        loadCity($(this).val());
    });

    onloadInfo();

    //--------------
    function onloadInfo() {
        console.log(1);

        $(".province").val("<?php echo $state; ?>");


        $(".province").closest('div').find('.city').addClass("tmp");
        loadCity($(".province").val());

        $(".city").val("<?php echo $city; ?>");


        $("#job").val("<?php echo $job; ?>");

        /*$('#education option:nth-child(<?php echo $education; ?>)').attr('selected', true);
             $('#age option:nth-child(<?php echo $age; ?>)').attr('selected', true);
             $('#city option:nth-child(<?php echo $city; ?>)').attr('selected', true);*/
    }
</script>
