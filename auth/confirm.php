<?php

include "ASEngine/AS.php";

if (! isset($_GET['k'])) {
    redirect('login.php');
}

$key = $_GET['k'];
$valid = app('validator')->confirmationKeyValid($key);

if ($valid) {
    app('db')->update(
        'as_users',
        array("confirmed" => "Y"),
        "`confirmation_key` = :k",
        array("k" => $key)
    );
}

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
                            <p class="text-success text-center lead mb-0">
                                <?= trans('email_confirmed') ?>!
                                <?= trans('you_can_login_now', array('link' => 'login.php')) ?>
                            </p>
                        <?php else : ?>
                            <h4 class="text-error text-center"><?= trans('user_with_key_doesnt_exist') ?></h4>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>