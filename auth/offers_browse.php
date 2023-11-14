<?php 
include 'templates/header.php';
include 'templates/db_connect.php';
?>

<div class="row">
    <?php
        $sidebarActive = 'offers_browse';
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
            <div class="col-2">
                <h5>Sort by:</h5>
            </div>
            <div class="col-10">
                <button id="sort_by_nst_date" type="button" class="btn btn-primary default-sort" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa fa-calendar"></i> Date Posted</button>
                <button id="sort_by_asc_date" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa fa-calendar"></i> Date Posted <i class="fa-solid fa-arrow-up"></i></button>
                <button id="sort_by_dsc_date" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;"><i class="fa fa-calendar"></i> Date Posted <i class="fa-solid fa-arrow-down"></i></button>

                <button id="sort_by_nst_prce" type="button" class="btn btn-primary default-sort" style="min-width:10em;margin-bottom:5px;"><i class="fa fa-tag"></i> Asking Price</button>
                <button id="sort_by_asc_prce" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa fa-tag"></i> Asking Price <i class="fa-solid fa-arrow-up"></i></button>
                <button id="sort_by_dsc_prce" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa fa-tag"></i> Asking Price <i class="fa-solid fa-arrow-down"></i></button>

                <button id="sort_by_nst_nmou" type="button" class="btn btn-primary default-sort" style="min-width:10em;margin-bottom:5px;"><i class="fa-solid fa-chart-simple"></i> Number of Units</button>
                <button id="sort_by_asc_nmou" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa-solid fa-chart-simple"></i> Number of Units <i class="fa-solid fa-arrow-up"></i></button>
                <button id="sort_by_dsc_nmou" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa-solid fa-chart-simple"></i> Number of Units <i class="fa-solid fa-arrow-down"></i></button>

                <button id="sort_by_nst_prpu" type="button" class="btn btn-primary default-sort" style="min-width:10em;margin-bottom:5px;"><i class="fa-solid fa-filter-circle-dollar"></i> Price per unit</button>
                <button id="sort_by_asc_prpu" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa-solid fa-filter-circle-dollar"></i> Price per unit <i class="fa-solid fa-arrow-up"></i></button>
                <button id="sort_by_dsc_prpu" type="button" class="btn btn-primary sort-button" style="min-width:10em;margin-bottom:5px;display:none;"><i class="fa-solid fa-filter-circle-dollar"></i> Price per unit <i class="fa-solid fa-arrow-down"></i></button>
            </div>
        </div>
        <br><br>
        <?php

            $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() AND o.featured_browse=?");
            $stmt->bind_param("s", $featured);

            $featured = "Y";

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <b><i class="fa-solid fa-fire"></i> Featured </b>
                    </div>
                    <div class="card-body">
                        <?php

                            $i = 0;
                            while ($row = $result->fetch_assoc()) {
                                if ($i > 0) { 
                                    echo "<hr>";
                                }
                                ++$i;
                                $asking_price_formatted = number_format($row['asking_price']);
                        ?>
                            <div class="row mb-3">
                                <div class="col-2 text-center">
                                    <a href="offer_details.php?offerid=<?php echo $row['offer_id']; ?>"><img src="<?php echo $row['img1']; ?>" style="max-height:120px;max-width:120px;" /></a>
                                </div>
                                <div class="col-10">
                                    <a href="offer_details.php?offerid=<?php echo $row['offer_id']; ?>" style="text-decoration:none;">
                                        <h5><?php echo $row['title']; ?></h5>
                                    </a>
                                    <h6 class="text-muted">Asking price: <span style="color:green;">$<?php echo $asking_price_formatted; ?> ($<?php echo $row['price_per_unit']; ?> per unit)</span>
                                    <br>
                                    <span class="text-muted"><?php echo $row['units_available']; ?> units available</span></h6>
                                    <h6 class="text-danger">Offer expires in <?php echo $row['until_expires']; ?> days</h6>

                                    <?php if ($row['owner_id'] != $userid) { ?>
                                        <button type="button" id="<?php echo $row['offer_id']?>" <?php if ($row['watching_owner'] == $userid) { echo ""; $display_save = "display:none;"; } else { $display_save = ""; } ?> class="btn btn-warning save_<?php echo $row['offer_id']; ?>" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:10px;width:120px;<?php echo $display_save; ?>"><i class="fa-regular fa-bookmark"></i> Save offer</button>

                                        <button type="button" id="<?php echo $row['offer_id']?>" <?php if ($row['watching_owner'] == $userid) { $display_remove = ""; } else { $display_remove = "display:none;"; } ?>" class="btn btn-danger remove_<?php echo $row['offer_id']; ?>" style="font-size:0.7em;padding:3px 5px 3px 5px;margin-right:10px;width:120px;<?php echo $display_remove; ?>"><i class="fa-solid fa-bookmark"></i> Remove offer</button>
                                        
                                        <button type="button" class="btn btn-primary" style="font-size:0.7em;padding:3px 5px 3px 5px;width:120px;"><i class="fa-solid fa-comments"></i> Contact seller</button>
                                    <?php } else { ?>
                                        <a href="offer_edit.php?offerid=<?php echo $row['offer_id']; ?>" class="btn btn-info" style="font-size:0.7em;padding:3px 5px 3px 5px;width:120px;margin-right:10px;color:white;"><i class="fa-solid fa-pen-to-square"></i> Edit offer</a>
                                    <?php } ?>
                                </div>
                            </div>
                      <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php } ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <b>Browse active offers</b>
                    </div>
                    <div class="card-body">
                        <div id="loading_results" class="text-center" style="display:none;">
                            <img src="assets/img/loading.gif" style="max-width: 70px;">
                            <h5 style="color:#6785F7;">Loading offers...</h5>
                        </div>
                        <div id="results"></div>
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
    $("#results").on("click","[class*=save_]", function(){
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
    $("#results").on("click","[class*=remove_]", function(){
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
<script>
    $("#sort_by_nst_date,#sort_by_asc_date,#sort_by_dsc_date,#sort_by_nst_prce,#sort_by_asc_prce,#sort_by_dsc_prce,#sort_by_nst_nmou,#sort_by_asc_nmou,#sort_by_dsc_nmou,#sort_by_nst_prpu,#sort_by_asc_prpu,#sort_by_dsc_prpu").click(function(){ 
        var sortby = $(this).attr('id'),
        clickend = sortby.slice(12),
        clickendunderscore = sortby.slice(11),
        clickpre = sortby.substr(0,7),
        clickpreunderscore = sortby.substr(0,8),
        clickmid = sortby.substr(8,3),
        userid = <?php echo $userid; ?>;

        $("#loading_results").show();
        $("#results").hide();

        if (clickmid == 'asc') {
            var nextsort = clickpreunderscore + "dsc" + clickendunderscore +"";
            $(".sort-button").hide();
            $(".default-sort").show();
            $("#" + clickpreunderscore + "dsc" + clickendunderscore +"").show();
            $("#" + clickpreunderscore + "nst" + clickendunderscore +"").hide();
        } else if (clickmid == 'dsc') {
            var nextsort = clickpreunderscore + "nst" + clickendunderscore +"";
            $(".sort-button").hide();
            $(".default-sort").show();
            $("#" + clickpreunderscore + "asc" + clickendunderscore +"").hide();
            $("#" + clickpreunderscore + "nst" + clickendunderscore +"").show();        
        } else if (clickmid == 'nst') {
            var nextsort = clickpreunderscore + "asc" + clickendunderscore +"";
            $(".sort-button").hide();
            $(".default-sort").show();
            $("#" + clickpreunderscore + "asc" + clickendunderscore +"").show();
            $("#" + clickpreunderscore + "nst" + clickendunderscore +"").hide();        
        }

        $.ajax({
            type: 'POST', 
            url:"ajax/load_offers.php",
            data:{ 
                    nextsort: nextsort,
                    userid: userid
            },
            success: function(response) {
                $("#loading_results").delay(2500).fadeOut(0),
                $("#results").delay(2500).fadeIn(0),
                $("#results").html(response);
            }
        });
    });
</script>

  </body>
</html>
