<?php
$title = $this->title;
$d = $this->admin[0];
$fullname = $d["fullname"];
$email = $d["email"];
$permission = json_decode($d["permission"]);
$avatar = $d["avatar"];
$purpose = $d["purpose"];
$role = $d["role"];
?>
<style>
    figure.avatar:after {
        content: '🧿';
    }
</style>
<section id="configuration">
    <div class="__frame" data-width="medium">
        <div class="ms-Grid">
            <div class="ms-Grid-row">

                <div class="ms-Grid-col ms-sm12 ms-md12">
                    <fluent-card class="custom">
                        <div class="card-body text-center">
                            <figure class="avatar">
                                <img src="<?= URL ?>upload/images/<?= $avatar ?>" alt="avatar" class="img-thumbnail rounded-circle ms-depth-64" style="max-height: 200px" />
                            </figure>
                            <h4 class="mt-10">
                                <?= $fullname ?>
                                <span class="badge badge-primary badge-pill"><?= $role ?></span>
                                <span class="badge badge-warning badge-pill"><?= $email ?></span>
                            </h4>
                        </div>
                    </fluent-card>
                </div>

                <div class="ms-Grid-col ms-sm12 ms-md12 mt-20">
                    <div class="card">
                        <div class="card-header">
                            <i class="ms-Icon ms-Icon--DatabaseSync" aria-hidden="true"></i>
                            <span>بروزرسانی</span>
                        </div>
                        <div class="card-body">
                            <form class="form" action="<?= URL ?>panel/admin?mod=update" method="post" name="form" enctype="multipart/form-data">


                                <div>
                                    <fluent-text-field appearance="filled" value="<?= $fullname ?>" name="fullname">نام و نام خانوادگی</fluent-text-field>
                                </div>
                                
                                <div>
                                    <fluent-text-area appearance="filled" placeholder="Describe your experience" value="<?= $purpose ?>" name="purpose">هدف شما</fluent-text-area>
                                </div>

                                <div class="form-group">
                                    <label for="avatar">عکس پروفایل</label>
                                    <input type="hidden" value="<?= $avatar ?>" name="oldavatar" />
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                </div>

                                <fluent-tooltip id="tooltip" anchor="btn-update">
                                    فقط امکان بروزرسانی وجود دارد
                                </fluent-tooltip>
                                <fluent-button appearance="accent" type="submit" id="btn-update">بروزرسانی</fluent-button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>