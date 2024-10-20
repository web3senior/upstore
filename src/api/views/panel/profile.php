<?php
$data = $this->data[0];
$fullname = $data['fullname'];
$email = $data['email'];
$pass = $data['pass'];
$gender = $data['gender'];
$avatar = $data['avatar'];
$purpose = $data['purpose'];
?>

<section class="profile">
    <div class="container">
        <div class="row xl">
            <div class="col xl-3">
                <p class="font-bold size-xs"><?= $email ?></p>
                <a class="text-danger" href="<?= URL ?>user/logout">Logout</a>

                <div class=" mt-40">
                    Options:
                    <ul class="d-flex flex-column mt-10">
                        <li>
                            <a href="<?= URL; ?>user/profile">
                                Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="<?= URL; ?>user/account">
                                Edit Accounts
                            </a>
                        </li>
                        <li>
                            <a href="<?= URL; ?>user/setting">
                                Notifications
                            </a>
                        </li>
                        <li>
                            <a href="<?= URL; ?>user/setting">
                                Privacy
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col xl-9">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="<?= URL; ?>user/profile/update" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Email:</label>
                                <input class="form-control" type="text" value="<?= $email; ?>" placeholder="Email"
                                       readonly>
                            </div>
                            <div class="form-group">
                                <label>Full name:</label>
                                <input type="text" class="form-control" name="fullname" id="fullname"
                                       value="<?= $fullname; ?>" placeholder="">
                            </div>
                            <div class="form-group">
                                <label>Gender:</label>
                                <input type="radio" name="gender" value="1" <?= ($gender == 1) ? 'checked' : ''; ?>>
                                Male
                                <input type="radio" name="gender" value="0" <?= ($gender == 0) ? 'checked' : ''; ?>>
                                Female
                                <input type="radio" name="gender" value="2" <?= ($gender == 2) ? 'checked' : ''; ?>>
                                Other
                            </div>
                            <div class="form-group">
                                <label>Purpose:</label>
                                <textarea class="form-control" style="height: 180px"><?= $purpose; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Avatar:</label>
                                <input name="avatar" id="avatar" class="form-control" type="file" accept="image/*"/>
                                <input type="hidden" name="avatarHidden" value="<?= $avatar; ?>"/>
                            </div>
                            <input class="btn btn-success" type="submit" name="sbm" value="update">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>