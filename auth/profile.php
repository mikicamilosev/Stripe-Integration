<?php 
include 'templates/header.php';

$currentUser = app('current_user');
?>

<div class="row">
    <?php
        $sidebarActive = 'profile';
        require 'templates/sidebar.php';
    ?>

    <div class="col-lg-9 mb-4">
                <?php if (! $currentUser->is_admin) : ?>
                    <div class="alert alert-warning">
                        <strong><?= trans('note') ?>! </strong>
                        <?= trans('to_change_email_username') ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <i class="fa-solid fa-user"></i>
                        <b><?= trans('your_details') ?></b>
                    </div>
                    <div class="card-body">
                        <form id="form-details" class="no-submit">
                            <!-- Text input-->
                            <div class="mb-3">
                                <label>
                                    <?= trans('first_name'); ?>
                                </label>
                                <input name="first_name" type="text"
                                    value="<?= e($currentUser->first_name); ?>"
                                    class="form-control">
                            </div>

                            <!-- Text input-->
                            <div class="mb-3">
                                <label>
                                    <?= trans('last_name'); ?>
                                </label>
                                <input name="last_name" type="text"
                                    value="<?= e($currentUser->last_name); ?>"
                                    class="form-control">
                            </div>

                            <!-- Text input-->
                            <div class="mb-3">
                                <label>
                                    <?= trans('address'); ?>
                                </label>
                                <input name="address" type="text"
                                    value="<?= e($currentUser->address); ?>"
                                    class="form-control">
                            </div>

                            <!-- Text input-->
                            <div class="mb-3">
                                <label>
                                    <?= trans('phone'); ?>
                                </label>
                                <input name="phone" type="text"
                                    value="<?= e($currentUser->phone); ?>"
                                    class="form-control">
                            </div>

                            <button id="update_details" type="submit" class="btn btn-primary">
                                <?= trans('update'); ?>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card mb-4 mt-4">
                    <div class="card-header">
                        <i class="fa-solid fa-lock"></i>
                        <b><?= trans('change_password') ?></b>
                    </div>
                    <div class="card-body">
                        <form id="change-password-form">
                            <div class="mb-3">
                                <label>
                                    <?= trans('old_password') ?>
                                </label>
                                <input name="old_password" type="password" class="form-control">
                            </div>

                            <!-- Password input-->
                            <div class="mb-3">
                                <label>
                                    <?= trans('new_password') ?>
                                </label>
                                <input name="new_password" type="password" class="form-control">
                            </div>

                            <!-- Password input-->
                            <div class="mb-3">
                                <label>
                                    <?= trans('confirm_new_password') ?>
                                </label>
                                <input name="new_password_confirmation" type="password" class="form-control">
                            </div>

                            <button id="change_password" type="submit" class="btn btn-primary">
                                <?= trans('update') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>


    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/app/profile.js"></script>
  </body>
</html>
