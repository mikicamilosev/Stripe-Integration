<?php

include "ASEngine/AS.php";

if (! isset($_GET['k'])) {
    redirect('login.php');
}

$valid = app('validator')->prKeyValid($_GET['k']);
?>
<!doctype html>
<html lang="en"> 
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Advanced Security - PHP MySQL Register/Login System">
        <meta name="author" content="Milos Stojanovic (@loshmis)">
        
        <title><?= trans('password_reset') ?> | <?= WEBSITE_NAME ?></title>

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/app.css" type="text/css" media="all" />
    </head>
    <body>
        <div class="container">
            <div class="navbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center me-2 localization-icons">
                        <?php include 'templates/languages.php'; ?>
                    </li>
                </ul>
            </div>


            <div class="col-md-8 col-lg-6 col-xl-5 mx-auto" style="margin-top: 8%; margin-bottom: 10%;">

                <h3 class="text-center">
                    <?= WEBSITE_NAME ?>
                </h3>

                <div class="card mt-5">
                    <div class="card-body pt-4">

                        <div class="px-4 pb-3">
                            <?php if ($valid) : ?>
                                <!-- start: Reset Password Form -->
                                <div class="form-wrapper active">
                                    <h6 class="text-uppercase mb-5 mt-3">
                                        <?= trans('reset_password') ?>
                                    </h6>

                                    <form id="password-reset-form">
                                        <div class="form-group">
                                            <label>
                                                <?= trans('new_password') ?>
                                            </label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>

                                        <button id="btn-reset-pass"
                                                type="submit"
                                                class="btn btn-success mt-4 btn-block btn-lg"
                                                data-loading-text="<?= trans('resetting') ?>">
                                            <?= trans('reset_password') ?>
                                        </button>
                                    </form>
                                </div>
                                <!-- end: Reset Password Form -->
                            <?php else : ?>
                                <h5 class="text-danger text-center mt-3">
                                    <?= trans('invalid_password_reset_key') ?>
                                </h5>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Common Scripts -->
        <script src="assets/js/vendor/sha512.js"></script>
        <script src="assets/js/vendor/jquery.min.js"></script>
        <script src="assets/js/vendor/popper.min.js"></script>
        <script src="assets/js/vendor/bootstrap.min.js"></script>
        <script src="assets/js/vendor/jquery-validate/jquery.validate.min.js"></script>
        <script src="assets/js/app/bootstrap.php"></script>
        <script src="assets/js/app/common.js"></script>

        <!-- Page Scripts -->
        <script type="text/javascript" src="assets/js/app/passwordreset.js"></script>
    </body>
</html>