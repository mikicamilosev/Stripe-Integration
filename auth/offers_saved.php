<?php 
include 'templates/header.php';
include 'templates/db_connect.php';
?>

<div class="row">
    <?php
        $sidebarActive = 'offers_saved';
        require 'templates/sidebar.php';
    ?>
    <div class="col-lg-9 mb-4">
        <?php if (! $currentUser->is_admin) : ?>
            <div class="alert alert-warning">
                <strong><?= trans('note') ?>! </strong>
                <?= trans('to_change_email_username') ?>
            </div>
        <?php endif; 
        $stmt = $db->prepare("SELECT *, DATEDIFF(o.datetime_expires, NOW()) as until_expires
        FROM offers o
        LEFT JOIN offers_watch w
        ON o.offer_id = w.offer_id
        WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() AND w.owner_id=?");
        $stmt->bind_param("s", $userid);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 1) { 
        ?>
        <div class="row">
            <div class="col-2">
                <h5>Sort by:</h5>
            </div>
            <div class="col-10">
                    <form id="sort_by_buttons">
                    <button id="sort_by_date" type="button" class="btn btn-primary" style="min-width:10em;margin-bottom:5px;"><i class="fa fa-calendar"></i> Date Posted</button>
                    <button id="sort_by_price" type="button" class="btn btn-primary" style="min-width:10em;margin-bottom:5px;"><i class="fa fa-tag"></i> Asking Price</button>
                    <button id="sort_by_nou" type="button" class="btn btn-primary" style="min-width:10em;margin-bottom:5px;"><i class="fa-solid fa-chart-simple"></i> Number of Units</button>
                    <button id="sort_by_ppu" type="button" class="btn btn-primary" style="min-width:10em;margin-bottom:5px;"><i class="fa-solid fa-filter-circle-dollar"></i> Price per unit</button>
                </form>
            </div>
        </div>
        <?php } ?>
        <br><br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <b><i class="fa-solid fa-bookmark"></i> My saved offers</b>
                    </div>
                    <div class="card-body">
                    <?php
                        if ($result->num_rows > 0) { 
                            $i = 0;
                            while ($row = $result->fetch_assoc()) {
                                if ($i > 0) { 
                                    echo "<hr>";
                                }
                                ++$i;
                                $asking_price_formatted = number_format($row['asking_price']);
                                ?>
                                <div class="row mb-3" id="test">
                                    <div class="col-2 text-center">
                                        <a href="offer_details.php?offerid=<?php echo $row['offer_id']; ?>"><img src="<?php echo $row['img1']; ?>" style="max-height:120px;max-width:120px;" /></a>
                                    </div>
                                    <div class="col-10">
                                        <a href="offer_details.php?offerid=<?php echo $row['offer_id']; ?>" style="text-decoration:none;">
                                            <h5><?php echo $row['title']; ?></h5>
                                        </a>
                                            <h6 class="text-muted">Asking price: <span style="color:green;">$<?php echo $asking_price_formatted; ?> ($<?php echo $row['price_per_unit']; ?> per unit)</span>
                                            <br>
                                            <span class="text-muted"><?php echo $row['units_available']; ?> units available</span>
                                            <h6 class="text-danger">Offer expires in <?php echo $row['until_expires']; ?> days</h6>
                                        <button type="button" id="<?php echo $row['offer_id']; ?>" class="btn btn-warning save_<?php echo $row['offer_id']; ?>" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:15px;display:none;width:120px;"><i class="fa-solid fa-bookmark"></i> Save again</button>
                                        <button type="button" id="<?php echo $row['offer_id']; ?>" class="btn btn-danger remove_<?php echo $row['offer_id']; ?>" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:15px;width:120px;"><i class="fa-solid fa-bookmark"></i> Remove offer</button>
                                        <button type="button" class="btn btn-primary" style="font-size:0.7em;padding:3px 5px 3px 5px;width:120px;"><i class="fa-solid fa-comments"></i> Contact seller</button>
                                    </div>
                                </div>
                      <?php } } else { ?>
                          No offers saved. <a href="offers_browse.php" style="text-decoration:none;"><b>Browse active offers</b></a> to start saving your favorites.
                     <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
<script src="assets/js/vendor/sha512.js"></script>
<?php include 'templates/footer.php'; ?>
<script src="assets/js/app/profile.js"></script>
<script>
    $("[class*=save_]").click(function(){ 
        var offerid = $(this).attr('id'),
        ownerid = "<?php echo $userid; ?>",
        saveremove = "save";

        $.ajax({
            type: 'POST', 
            url:"ajax/save_remove_offer.php",
            data:{ offerid: offerid,
                ownerid: ownerid,
                saveremove: saveremove
            },
            success: function(response) {
                $(".save_"+offerid).hide(),
                $(".remove_"+offerid).show(),
                $("#saved_count").html(response);
            }
        });
    });
</script>
<script>
    $("[class*=remove_]").click(function(){ 
        var offerid = $(this).attr('id'),
        ownerid = "<?php echo $userid; ?>",
        saveremove = "remove";
        saved_count = $('#current_saved_count').val();

        $.ajax({
            type: 'POST', 
            url:"ajax/save_remove_offer.php",
            data:{ offerid: offerid,
                ownerid: ownerid,
                saveremove: saveremove,
                saved_count: saved_count
            },
            success: function(response) {
                $(".save_"+offerid).show(),
                $(".remove_"+offerid).hide(),
                $("#saved_count").html(response);
            }
        });
    });
</script>

  </body>
</html>
