<?php

include "ASEngine/AS.php";

if (app('login')->isLoggedIn()) {
    redirect('index.php');
}

$token = app('register')->socialToken();
ASSession::set('as_social_token', $token);
app('register')->botProtection();
?>
<!doctype html>
<html lang="<?= ASLang::getLanguage() ?>">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Advanced Security - PHP MySQL Register/Login System">
        <title>Login | Rid My Inventory</title>

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css" media="all" />
        <link rel="stylesheet" href="assets/css/app.css" type="text/css" media="all" />
    </head>
    <body>
        <div class="container">
            <div class="col-md-7 col-lg-5 col-xl-4 mx-auto" style="margin-top: 8%; margin-bottom: 10%;">
                
                <h3 class="text-center">
                    <img src="assets/img/navbar-logo.png" style="max-width: 120px;"/>
                </h3>

                <div class="card mt-5">
                    <div class="card-body pt-4">

                        <div class="px-4 pb-3">
                            <!-- start: Login Form -->
                            <div class="form-wrapper active" id="login">
                                <h6 class="text-uppercase mb-5 mt-3">
                                    <?= trans('login') ?>
                                </h6>
                                <form class="form-horizontal">
                                    <!-- start: Username -->
                                    <div class="mb-3">
                                        <label for="login-username">
                                            <?= trans('username') ?>
                                        </label>
                                        <input type="text" name="username" class="form-control">
                                    </div>
                                    <!-- end: Username -->

                                    <!-- start: Password -->
                                    <div class="mb-3">
                                        <label>
                                            <?= trans('password') ?>
                                        </label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <!-- end: Password -->

                                    <div>
                                        <a href="#forgot" class="form-change text-decoration-none">
                                            <?= trans('forgot_password') ?>
                                        </a>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit"
                                                id="btn-login"
                                                class="btn btn-success mt-5 btn-lg"
                                                data-loading-text="<?= trans('logging_in') ?>">
                                            <?= trans('login') ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end: Login Form -->

                            <!-- start: Registration Form -->
                            <div class="form-wrapper" id="create">
                                <h6 class="text-uppercase mb-5 mt-3">
                                    <?= trans('create_account') ?>
                                </h6>

                                <form>
                                    <div class="mb-3">
                                        <label>
                                            <?= trans('email') ?> <span class="required">*</span>
                                        </label>
                                        <input type="text" name="email" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>
                                            <?= trans('username') ?> <span class="required">*</span>
                                        </label>
                                        <input type="text" name="username" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>
                                            <?= trans('password') ?> <span class="required">*</span>
                                        </label>
                                        <input type="password" name="password" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>
                                            <?= trans('repeat_password') ?> <span class="required">*</span>
                                        </label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>
                                            <?= ASSession::get("bot_first_number") ?> +
                                            <?= ASSession::get("bot_second_number") ?>
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" name="bot_protection" class="form-control">
                                    </div>

                                    <div class="d-grid">
                                        <button id="btn-register"
                                                type="submit"
                                                class="btn btn-success mt-4 btn-block btn-lg"
                                                data-loading-text="<?= trans('creating_account') ?>">
                                            <?= trans('create_account') ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end: Registration Form -->

                            <!-- start: Forgot Password Form -->
                            <div class="form-wrapper" id="forgot">
                                <h6 class="text-uppercase mb-5 mt-3">
                                    <?= trans('forgot_password') ?>
                                </h6>

                                <form id="forgot-pass-form">
                                    <div class="mb-3">
                                        <label>
                                            <?= trans('your_email') ?>
                                        </label>
                                        <input type="email" name="email" class="form-control">
                                    </div>

                                    <div class="d-grid">
                                        <button id="btn-forgot-password"
                                                type="submit"
                                                class="btn btn-success mt-4 btn-block btn-lg">
                                            <?= trans('reset_password') ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end: Forgot Password Form -->

                            <?php if (FACEBOOK_ENABLED || TWITTER_ENABLED || GOOGLE_ENABLED) : ?>
                                <small class="text-center text-muted mt-3 d-block">
                                    <?= trans('or_connect_with') ?>
                                </small>

                                <!-- start: Social Login Buttons -->
                                <div class="text-center d-flex justify-content-around mt-3 col-md-6 mx-auto">
                                    <?php if (FACEBOOK_ENABLED) : ?>
                                        <a href="socialauth.php?p=facebook&token=<?= $token ?>"
                                           class="btn-social btn-facebook">
                                            <i class="fab fa-facebook-square fa-2x"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (TWITTER_ENABLED) : ?>
                                        <a href="socialauth.php?p=twitter&token=<?= $token ?>"
                                           class="btn-social btn-twitter">
                                            <i class="fab fa-twitter-square fa-2x"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (GOOGLE_ENABLED) : ?>
                                        <a href="socialauth.php?p=google&token=<?= $token ?>"
                                           class="btn-social btn-google">
                                            <i class="fab fa-google fa-2x"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <!-- end: Social Login Buttons -->
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-center text-muted mt-3 sign-up-controls">
                    <?= trans('dont_have_an_account') ?>
                    <a href="#create" class="form-change"><?= trans('sign_up') ?></a>
                </div>
                <div class="text-center text-muted mt-3 sign-in-controls" style="display: none;">
                    <?= trans('already_have_an_account') ?>
                    <a href="#login" class="form-change"><?= trans('login') ?></a>
                </div>
            </div>

        </div>

        <!-- Common Scripts -->
        <script src="assets/js/vendor/sha512.js"></script>
        <script src="assets/js/vendor/jquery.min.js"></script>
        <script src="assets/js/vendor/jquery-validate/jquery.validate.min.js"></script>
        <script src="assets/js/app/bootstrap.php"></script>
        <script src="assets/js/app/common.js"></script>
        <?php if (ASLang::getLanguage() != DEFAULT_LANGUAGE) : ?>
        <script src="assets/js/vendor/jquery-validate/localization/messages_<?= ASLang::getLanguage() ?>.js"></script>
        <?php endif; ?>

        <!-- Page Scripts -->
        <script type="text/javascript" src="assets/js/app/login.js"></script>
        <script type="text/javascript" src="assets/js/app/register.js"></script>
        <script type="text/javascript" src="assets/js/app/passwordreset.js"></script>
    </body>
</html>