<?php
    include ('../templates/db_connect.php');

    $nextsort = $_POST['nextsort'];
    $userid = $_POST['userid'];

    if ($nextsort == "sort_by_dsc_date") {
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.datetime_added DESC");
    } elseif ($nextsort == "sort_by_asc_date") {
        $orderby = "o.datetime_added ASC";
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.datetime_added ASC");
    } elseif ($nextsort == "sort_by_dsc_prce") {
        $orderby = "o.asking_price DESC";
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.asking_price DESC");
    } elseif ($nextsort == "sort_by_asc_prce") {
        $orderby = "o.asking_price ASC";
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.asking_price ASC");
    } elseif ($nextsort == "sort_by_dsc_nmou") {
        $orderby = "o.units_available DESC";
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.units_available DESC");
    } elseif ($nextsort == "sort_by_asc_nmou") {
        $orderby = "o.units_available ASC";
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.units_available ASC");
    } elseif ($nextsort == "sort_by_dsc_prpu") {
        $orderby = "o.price_per_unit DESC";
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.price_per_unit DESC");
    } elseif ($nextsort == "sort_by_asc_prpu") {
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.price_per_unit ASC");
    } else {
        $stmt = $db->prepare("SELECT DATEDIFF(o.datetime_expires, NOW()) as until_expires, w.owner_id as watching_owner, o.owner_id as owner_id, o.title, o.featured_home, o.featured_browse, o.featured_facebook, o.units_available, TRUNCATE(o.asking_price,0) AS asking_price, o.img1, o.offer_id, o.price_per_unit FROM offers o LEFT JOIN offers_watch w ON o.offer_id = w.offer_id WHERE o.datetime_added < NOW() AND o.datetime_expires > NOW() ORDER BY o.datetime_added ASC");
    }

    $stmt->execute();
    $result = $stmt->get_result(); 

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
            <h6 class="text-muted">Number available: <span style="color:black;"><?php echo $row['units_available']; ?> units</span></h6>
            <?php if ($row['until_expires'] < 11) { ?><h6 class="text-danger">Offer expires in <?php echo $row['until_expires']; ?> days</h6><?php } ?>

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