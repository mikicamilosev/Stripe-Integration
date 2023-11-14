<?php

include 'templates/header.php';

if (! app('current_user')->is_admin) {
    redirect("index.php");
}

// Admin user have role id equal to 3,
// and we will omit admin from this result.
$users = app('db')->select(
    "SELECT `as_users`.*, `as_user_roles`.`role` as role_name 
    FROM `as_users` 
    INNER JOIN `as_user_roles` ON `as_users`.`user_role` = `as_user_roles`.`role_id`
    WHERE `as_users`.`user_role` != '3' 
    ORDER BY `as_users`.`register_date` DESC"
);

$roles = app('db')->select("SELECT * FROM `as_user_roles` WHERE `role_id` != '3'");

?>

<link rel="stylesheet" href="assets/css/dataTables.bootstrap.css"/>

<div class="row">
    <?php
        $sidebarActive = 'users';
        require 'templates/sidebar.php';
    ?>

    <div class="col-lg-9 mb-4">
        <div>
            <a class="btn btn-success d-block d-sm-inline-block"
               id="btn-show-user-modal"
               href="#modal-add-edit-user"
               data-bs-toggle="modal">
                <i class="fa fa-plus"></i>
                <?= trans('add_user') ?>
            </a>
        </div>

        <div class="ajax-loading d-flex flex-column align-items-center pt-4 pb-4" id="loading-users">
            <i class="fa fa-2x fa-spinner fa-spin"></i>
            <div class="mt-2"><?= trans('loading') ?></div>
        </div>

        <div class="mt-5">
            <table class="table table-striped w-100" id="users-list" style="display: none;">
                <thead>
                    <tr>
                        <th><?= trans('username') ?></th>
                        <th><?= trans('email') ?></th>
                        <th><?= trans('register_date') ?></th>
                        <th><?= trans('confirmed') ?></th>
                        <th><?= trans('action') ?></th>
                    </tr>
                </thead>
                <?php foreach ($users as $user) : ?>
                    <tr class="user-row">
                        <td><?= e($user['username']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= $user['register_date'] ?></td>
                        <td>
                            <?php if ($user['confirmed'] == 'Y') : ?>
                                <p class="text-success"><?= trans('yes') ?></p>
                            <?php else : ?>
                                <p class="text-danger"><?= trans('no') ?></p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn change-role btn-sm
                                        btn-<?= $user['banned'] == 'Y' ? 'danger' : 'primary'; ?>"
                                        data-user="<?= $user['user_id'] ?>"
                                        data-role="<?= $user['user_role'] ?>"
                                        data-bs-toggle="modal"
                                        href="#modal-change-role">
                                    <i class="fa fa-user-secret"></i>
                                    <span class="user-role"><?= ucfirst($user['role_name']); ?></span>
                                </a>

                                <button data-bs-toggle="dropdown"
                                        class="btn btn-<?= $user['banned'] == 'Y' ? 'danger' : 'primary' ?>
                                        btn-sm dropdown-toggle">
                                    <span class="caret"></span>
                                </button>

                                <div class="dropdown-menu">
                                    <a href="#modal-add-edit-user"
                                       class="dropdown-item edit-user"
                                       data-bs-toggle="modal"
                                       data-user="<?= $user['user_id'] ?>">
                                        <i class="fa fa-edit me-1"></i>
                                        <?= trans('edit') ?>
                                    </a>
                                    <a href="#modal-user-details"
                                       class="dropdown-item user-details"
                                       data-bs-toggle="modal"
                                       data-user="<?= $user['user_id'] ?>">
                                        <i class="fa fa-list-alt me-1"></i>
                                        <?= trans('details') ?>
                                    </a>

                                    <a href="javascript:;"
                                       class="dropdown-item unban-user <?= $user['banned'] == 'Y' ? '' : 'd-none' ?>"
                                       data-user="<?= $user['user_id'] ?>">
                                        <i class="fa fa-ban me-1"></i>
                                        <span><?= trans('unban') ?></span>
                                    </a>

                                    <a href="javascript:;"
                                       class="dropdown-item ban-user <?= $user['banned'] == 'Y' ? 'd-none' : '' ?>"
                                       data-user="<?= $user['user_id'] ?>">
                                        <i class="fa fa-ban me-1"></i>
                                        <span><?= trans('ban') ?></span>
                                    </a>

                                    <a href="javascript:;"
                                       class="dropdown-item delete-user"
                                       data-user="<?= $user['user_id'] ?>">
                                        <i class="fa fa-trash me-1"></i>
                                        <?= trans('delete'); ?>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a href="#modal-change-role"
                                       class="dropdown-item change-role"
                                       data-bs-toggle="modal"
                                       data-user="<?= $user['user_id'] ?>"
                                       data-role="<?= $user['user_role'] ?>">
                                        <?= trans('change_role') ?>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

        <div class="modal fade" id="modal-user-details">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <?= trans('loading'); ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <dl class="dl-horizontal">
                            <dt title="<?= trans('email') ?>"><?= trans('email') ?></dt>
                            <dd id="modal-details--email"></dd>
                            <dt title="<?= trans('first_name') ?>"><?= trans('first_name') ?></dt>
                            <dd id="modal-details--first-name"></dd>
                            <dt title="<?= trans('last_name') ?>"><?= trans('last_name') ?></dt>
                            <dd id="modal-details--last-name"></dd>
                            <dt title="<?= trans('address') ?>"><?= trans('address') ?></dt>
                            <dd id="modal-details--address"></dd>
                            <dt title="<?= trans('phone') ?>"><?= trans('phone') ?></dt>
                            <dd id="modal-details--phone"></dd>
                            <dt title="<?= trans('last_login') ?>"><?= trans('last_login') ?></dt>
                            <dd id="modal-details--last-login"></dd>
                        </dl>
                    </div>

                    <div class="text-center py-4 ajax-loading">
                        <i class="fa fa-2x fa-spinner fa-spin"></i>
                    </div>

                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-dismiss="modal" aria-hidden="true">
                            <?= trans('ok'); ?>
                        </a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="modal-change-role">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-username">
                            <?= trans('pick_user_role') ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="details-body">
                        <?php if (count($roles) > 0) : ?>
                            <label><?= trans('select_role') ?></label>
                            <select id="select-user-role" class="form-control">
                                <?php foreach ($roles as $role) : ?>
                                    <option value="<?= $role['role_id'] ?>">
                                        <?= e(ucfirst($role['role'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-default" data-bs-dismiss="modal" aria-hidden="true">
                            <?= trans('cancel'); ?>
                        </a>
                        <a href="javascript:;" class="btn btn-primary"
                           id="change-role-button" data-bs-dismiss="modal" aria-hidden="true">
                            <?= trans('ok'); ?>
                        </a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="modal-add-edit-user" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-username">
                            <?= trans('add_user'); ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="p-2" id="add-user-form">
                        <div class="modal-body" id="details-body">
                            <div class="mb-3">
                                <label>
                                    <?= trans('email') ?>
                                </label>
                                <input name="email" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>
                                    <?= trans('username') ?>
                                </label>
                                <input name="username" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>
                                    <?= trans('password') ?>
                                </label>
                                <input name="password" type="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>
                                    <?= trans('repeat_password') ?>
                                </label>
                                <input name="password_confirmation" type="password" class="form-control">
                            </div>

                            <hr class="mt-4 mb-4">

                            <div class="mb-3">
                                <label>
                                    <?= trans('first_name'); ?>
                                </label>
                                <input name="first_name" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>
                                    <?= trans('last_name'); ?>
                                </label>
                                <input name="last_name" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>
                                    <?= trans('address'); ?>
                                </label>
                                <input name="address" type="text" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="phone">
                                    <?= trans('phone'); ?>
                                </label>
                                <input name="phone" type="text" class="form-control">
                            </div>
                        </div>

                        <div align="center" class="ajax-loading" style="display: none;">
                            <i class="fa fa-2x fa-spinner fa-spin my-5"></i>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-hidden="true">
                                <?= trans('cancel') ?>
                            </button>

                            <button type="submit" id="btn-add-user" name="button" class="btn btn-primary">
                                <?= trans('add') ?>
                            </button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script src="assets/js/vendor/sha512.js"></script>
        <?php include 'templates/footer.php'; ?>
        <script src="assets/js/vendor/jquery.dataTables.min.js"></script>
        <script src="assets/js/vendor/dataTables.bootstrap5.js"></script>
        <script src="assets/js/app/users.js"></script>
    </body>
</html>