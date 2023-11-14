<?php 
include 'templates/header.php';
include 'templates/db_connect.php';

$offer_id = $_GET['offerid'];

$sql_update_views = "UPDATE offers SET count_views=count_views+1 WHERE offer_id='$offer_id'";
$db->query($sql_update_views);

?>

<div class="row">
    <?php
        $sidebarActive = 'na';
        require 'templates/sidebar.php';
    ?>
    <div class="col-lg-9">
        <?php if (! $currentUser->is_admin) : ?>
            <div class="alert alert-warning">
                <strong><?= trans('note') ?>! </strong>
                <?= trans('to_change_email_username') ?>
            </div>
        <?php endif; ?>
        <br><br>
        <?php 
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) AS until_expires, w.owner_id AS watching_owner, o.owner_id AS owner_id, o.title, o.description, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.price_per_unit, o.img1, o.img2, o.img3, o.img4, o.img5, o.offer_id, o.product_locations, o.asin FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.offer_id=?");
        $stmt->bind_param("s", $offer_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); 
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <b><i class="fa-solid fa-tag"></i> Offer detail</b>
                    </div>
                    <div class="card-body">
                    <?php if ($row['until_expires'] <= '0') { ?>
                        <div class="row" style="float:right;">
                            <div class="col-12 text-center">
                                <div class="alert alert-danger" role="alert">Offer expired!</div>
                            </div>
                        </div> 
                        <?php } elseif ($row['until_expires'] <= '10') { ?>
                        <div class="row" style="float:right;">
                            <div class="col-12 text-center">
                            Offer expires in <?php echo $row['until_expires']." days"; ?>
                            </div>
                        </div>
                    <?php } ?>
                        <div class="row">
                            <div class="col-md-5 text-left">
                                <div class="row">
                                    <div class="mt-2">
                                        <a href="<?php echo $row['img1']; ?>" data-lightbox="photos">
                                            <img src="<?php echo $row['img1']; ?>" class="mb-2" style="float:left;max-width:85%;box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 10px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px; margin-right:7px;">
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php if ($row['img2']) { ?>
                                    <div class="col-sm-3 mt-2">
                                        <a href="<?php echo $row['img2']; ?>" data-lightbox="photos">
                                            <img src="<?php echo $row['img2']; ?>" class="mb-2" style="float:left;max-width:95%;box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 10px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px; margin-right:7px;">
                                        </a>
                                    </div>
                                    <?php } if ($row['img3']) { ?>
                                    <div class="col-sm-3 mt-2">
                                        <a href="<?php echo $row['img3']; ?>" data-lightbox="photos">
                                            <img src="<?php echo $row['img3']; ?>" class="mb-2" style="float:left;max-width:95%;box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 10px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px; margin-right:7px;">
                                        </a>
                                    </div>
                                    <?php } if ($row['img4']) { ?>
                                    <div class="col-sm-3 mt-2">
                                        <a href="<?php echo $row['img4']; ?>" data-lightbox="photos">
                                            <img src="<?php echo $row['img4']; ?>" class="mb-2" style="float:left;max-width:95%;box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 10px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px; margin-right:7px;">
                                        </a>
                                    </div>
                                    <?php } if ($row['img5']) { ?>
                                    <div class="col-sm-3 mt-2">
                                        <a href="<?php echo $row['img5']; ?>" data-lightbox="photos">
                                            <img src="<?php echo $row['img5']; ?>" class="mb-2" style="float:left;max-width:95%;box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 10px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px; margin-right:7px;">
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <?php if ($row['owner_id'] != $userid) { ?>
                                    <button type="button" id="<?php echo $row['offer_id']?>" <?php if ($row['watching_owner'] == $userid) { echo ""; $display_save = "display:none;"; } else { $display_save = ""; } ?> class="btn btn-warning save_<?php echo $row['offer_id']; ?>" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:10px;width:120px;<?php echo $display_save; ?>"><i class="fa-regular fa-bookmark"></i> Save offer</button>

                                    <button type="button" id="<?php echo $row['offer_id']?>" <?php if ($row['watching_owner'] == $userid) { $display_remove = ""; } else { $display_remove = "display:none;"; } ?>" class="btn btn-danger remove_<?php echo $row['offer_id']; ?>" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:10px;width:120px;<?php echo $display_remove; ?>"><i class="fa-solid fa-bookmark"></i> Remove offer</button>

                                    <button type="button" class="btn btn-primary" style="font-size:0.7em;padding:3px 5px 3px 5px;width:120px;margin-right:10px"><i class="fa-solid fa-comments"></i> Contact seller</button>
                                    <?php } else {
                                        if ($row['until_expires'] <= '0') {
                                    ?>
                                            <a href="offer_edit.php?offerid=<?php echo $row['offer_id']; ?>" class="btn btn-danger" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:10px;width:120px;color:white;"><i class="fa-solid fa-pen-to-square"></i> Renew offer</button></a>
                                        <?php } else { ?>
                                            <a href="offer_edit.php?offerid=<?php echo $row['offer_id']; ?>" class="btn btn-info" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:10px;width:120px;color:white;"><i class="fa-solid fa-pen-to-square"></i> Edit offer</button></a>
                                    <?php } } ?>
                                <br><br>
                                <h4><?php echo $row['title']; ?></h4>
                                <br>
                                <?php if ($row['asin']) { ?>
                                    <a href="https://amazon.com/dp/<?php echo $row['asin']; ?>" class="btn" style="font-size:1.0em;padding:5px 11px 5px 11px;margin-right:15px;background-color:#ff9900;" target="_blank"><i class="fa-brands fa-amazon"></i> Amazon listing</a>
                                    <br><br>
                                <?php } ?>
                                <div class="mb-4" style="border:1px solid #c3cdd5; box-shadow:0 8px 20px 0 rgba(0,0,0,.07843); border-radius:.5rem;padding:.75rem 1rem;">
                                    <h5 style="color:#777777;">Asking price: <span style="color:#000">$<?php $asking_price_formatted = number_format($row['asking_price']); echo $asking_price_formatted; ?></span></h6>
                                    <h5 style="color:#777777;">Price per unit: <span style="color:#000">$<?php echo $row['price_per_unit']; ?></span></h6>
                                    <h5 style="color:#777777;">Units available: <span style="color:#000"><?php echo $row['units_available']; ?></span></h6>
                                </div>
                                <?php if ($row['description']) { ?>
                                <div class="mb-4" style="border:1px solid #c3cdd5; box-shadow:0 8px 20px 0 rgba(0,0,0,.07843); border-radius:.5rem;padding:.75rem 1rem;">
                                    <h7><?php echo $row['description']; ?></h7>
                                </div>
                                <?php } ?>
                                <div style="border:1px solid #c3cdd5; box-shadow:0 8px 20px 0 rgba(0,0,0,.07843); border-radius:.5rem;padding:.75rem 1rem;">
                                    <h6 style="color:#777777;">Inventory stored at:</h6>
                                    <?php 
                                    if (strpos($row['product_locations'], "az_us") !== false) {
                                            echo "<h7><i class='fa-brands fa-amazon'></i> Amazon Warehouse United States</h7><br>";
                                        }
                                    if (strpos($row['product_locations'], "az_ovs") !== false) {
                                            echo "<h7><i class='fa-brands fa-amazon'></i> Amazon Warehouse Overseas</h7><br>";
                                        }
                                    if (strpos($row['product_locations'], "oth_usa") !== false) {
                                            echo "<h7><i class='fa-solid fa-warehouse'></i></i> 3rd Party Storage United States</h7><br>";
                                        }
                                    if (strpos($row['product_locations'], "oth_ovs") !== false) {
                                            echo "<h7><i class='fa-solid fa-warehouse'></i> 3rd Party Storage Overseas</h7>";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
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