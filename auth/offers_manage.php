<?php 
include 'templates/header.php';
include 'templates/db_connect.php';

$currentUser = app('current_user');
?>

<div class="row">
    <?php
        $sidebarActive = 'offers_manage';
        require 'templates/sidebar.php';
    ?>
    <div class="col-lg-9 mb-4">
        <?php if (! $currentUser->is_admin) : ?>
            <div class="alert alert-warning">
                <strong><?= trans('note') ?>! </strong>
                <?= trans('to_change_email_username') ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-4">
                <a href="offer_create.php"><button id="offer_create.php" type="button" class="btn btn-primary" style="margin-bottom:5px;"><i class="fa-solid fa-circle-plus"></i> Create offer</button></a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        My offers
                    </div>
                    <div class="card-body">
                        <?php
                            $stmt = $db->prepare("SELECT *, DATEDIFF(datetime_expires, NOW()) as until_expires, TRUNCATE(asking_price,0) as asking_price FROM offers where owner_id=?");
                            $stmt->bind_param("s", $userid);
                            
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) { 
                                $i = 0;
                                while ($row = $result->fetch_assoc()) {
                                    if ($i > 0) { 
                                        echo "<hr>";
                                    }
                                ++$i;

                                if ($row['until_expires'] <= '0') { $expiration_time = "Offer expired"; } else { $expiration_time = "Offer expires in ".$row['until_expires']." days"; }
                        ?>
                            <div class="row mb-3">
                                <div class="col-2 text-center">
                                    <a href="offer_details.php?offerid=<?php echo $row['offer_id'] ?>" style="text-decoration:none;">
                                        <img src="<?php echo $row['img1']; ?>" style="max-height:120px;max-width:120px;" />
                                    </a>
                                </div>
                                <div class="col-10">
                                    <a href="offer_details.php?offerid=<?php echo $row['offer_id'] ?>" style="text-decoration:none;">
                                    <h6>Asking price: $<?php echo $row['asking_price']; ?> <span class="text-muted">(Price per unit: $<?php $ppu = $row['asking_price']/$row['units_available']; echo sprintf('%0.2f', $ppu) ?>)</span></h6>
                                    <h5><?php echo $row['title']; ?></h5>
                                    <span class="text-muted"><?php echo $row['units_available']; ?> units available</span>
                                    <h6 class="text-danger"><?php echo $expiration_time ?></h6>
                                    <?php if ($expiration_time == "Offer expired") { ?>
                                        <a href="offer_renew.php?offerid=<?php echo $row['offer_id']; ?>" class="btn btn-danger" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:15px;width:120px;margin-right:10px;color:white;"><i class="fa-solid fa-rotate"></i> Renew offer</a>
                                    <?php } else { ?>
                                        <a href="offer_edit.php?offerid=<?php echo $row['offer_id']; ?>" class="btn btn-info" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:15px;width:120px;margin-right:10px;color:white;"><i class="fa-solid fa-pen-to-square"></i> Edit offer</a>
                                    <?php } ?>
                                </div>
                            </div>                
                        <?php } } else { ?>
                            <div class="mb-3">Nothing listed yet, get started!</div>
                        <?php } ?>
                    </div>
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
