<?php

include 'templates/header.php';

if (! app('current_user')->is_admin) {
    redirect("index.php");
}

// Default roles have ids 1, 2 and 3, so we will exclude them
// from results we want to get from this query, since we want
// to know number of users for our custom roles only.
$roles = app('db')->select(
    "SELECT `as_user_roles`.*, COUNT(`as_users`.`user_id`) as num FROM `as_user_roles`
    LEFT JOIN `as_users` ON `as_users`.`user_role` = `as_user_roles`.`role_id` 
    WHERE `as_user_roles`.`role_id` NOT IN (1,2,3)
    GROUP BY `as_user_roles`.`role_id`"
);

?>

<div class="row">
    <?php
        $sidebarActive = 'roles';
        require 'templates/sidebar.php';
    ?>

    <div class="col-lg-9 mb-4">
        <div class="mb-4">
            <form id="add-role-form" class="d-flex align-items-start">
                <div>
                    <input type="text" class="form-control"
                           name="name" placeholder="<?= trans('role_name') ?>">
                </div>
                <button type="submit" class="btn btn-success ms-3">
                    <i class="fa fa-plus"></i>
                    <?= trans('add') ?>
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped roles-table">
                <thead>
                    <tr>
                        <th><?= trans('role_name') ?></th>
                        <th><?= trans('users_with_role') ?></th>
                        <th><?= trans('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($roles as $role) : ?>
                    <tr class="role-row">
                        <td><?= e($role['role']) ?></td>
                        <td><?= e($role['num']) ?></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete-role"
                                    data-role="<?= $role['role_id'] ?>">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    
        <?php include 'templates/footer.php'; ?>
        <script src="assets/js/app/roles.js"></script>
   	</body>
 </html>