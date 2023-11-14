<?php
    include 'templates/db_connect.php';
    $stmt = $db->prepare("SELECT count(*) AS count_saved FROM offers_watch w LEFT JOIN offers o ON w.offer_id = o.offer_id WHERE w.owner_id=? AND o.datetime_expires >= CURDATE() AND o.offer_status=?");
    $stmt->bind_param("ss", $userid, $status);

    $status = "active";

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt2 = $db->prepare("SELECT count(*) AS count_mine FROM offers WHERE owner_id=?");
    $stmt2->bind_param("s", $userid);

    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = $result2->fetch_assoc();
?>
<div class="col-lg-3">
    <div class="list-group list-group-borderless">
        <a href="index.php"
           class="list-group-item list-group-item-action <?= $sidebarActive == 'home' ? 'active' : '' ?>">
            <i class="fa fa-home"></i>
            <?= trans('home') ?>
        </a>
        <a href="offers_browse.php"
           class="list-group-item list-group-item-action <?= $sidebarActive == 'offers_browse' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            Browse offers
        </a>
        <a href="offers_manage.php"
           class="list-group-item list-group-item-action <?= $sidebarActive == 'offers_manage' ? 'active' : '' ?>">
            <i class="fa-solid fa-hand-holding-dollar"></i>
            My offers <span id="my_count">(<?php echo $row2['count_mine']; ?>)</span>
        </a>
        <a href="offers_saved.php"
           class="list-group-item list-group-item-action <?= $sidebarActive == 'offers_saved' ? 'active' : '' ?>">
            <i class="fa-solid fa-bookmark"></i>
            Saved offers <span id="saved_count">(<?php echo $row['count_saved']; ?>)</span>
            <input type="hidden" id="current_saved_count" value="<?php echo $row['count_saved']; ?>">
        </a>
        <a href="negotiations.php"
           class="list-group-item list-group-item-action <?= $sidebarActive == 'negotiations' ? 'active' : '' ?>">
            <i class="fa-solid fa-comments"></i>
            Negotiations
        </a>
        <a href="profile.php"
           class="list-group-item list-group-item-action <?= $sidebarActive == 'profile' ? 'active' : '' ?>">
            <i class="fa fa-user"></i>
            <?= trans('my_profile') ?>
        </a>
        <?php if (app('current_user')->is_admin) : ?>
        <br><br>
        Admin:
            <a href="users.php"
               class="list-group-item list-group-item-action <?= $sidebarActive == 'users' ? 'active' : '' ?>">
                <i class="fa fa-users"></i>
                <?= trans('users') ?>
            </a>
            <a href="user_roles.php"
               class="list-group-item list-group-item-action <?= $sidebarActive == 'roles' ? 'active' : '' ?>">
                <i class="fa fa-user-secret"></i>
                <?= trans('user_roles') ?>
            </a>
        <?php endif; ?>
    </div>
</div>