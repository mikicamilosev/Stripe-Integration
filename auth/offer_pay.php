<?php 
    include 'templates/header.php';
    $currentUser = app('current_user');

    if (isset($_POST['to_payment'])) {
        include 'templates/db_connect.php';
        
        $_SESSION['title'] = $_POST['product_title'];
        $_SESSION['desc'] = $_POST['product_desc'];
        $_SESSION['asin'] = $_POST['asin'];
        $_SESSION['asin_country'] = $_POST['asin_country'];
        $_SESSION['asin'] = $_POST['asin'];
        $_SESSION['qts'] = $_POST['qts'];
        $_SESSION['price'] = $_POST['price'];
        if (isset($_POST['azon_usa'])) { $_SESSION['azon_usa'] = $_POST['azon_usa']; }
        if (isset($_POST['azon_ovs'])) { $_SESSION['azon_ovs'] = $_POST['azon_ovs']; }
        if (isset($_POST['oth_usa'])) { $_SESSION['oth_usa'] = $_POST['oth_usa']; }
        if (isset($_POST['oth_ovs'])) { $_SESSION['oth_ovs'] = $_POST['oth_ovs']; }
        $_SESSION['img_link1'] = $_POST['img_link1'];
        $_SESSION['img_link2'] = $_POST['img_link2'];
        $_SESSION['img_link3'] = $_POST['img_link3'];
        $_SESSION['img_link4'] = $_POST['img_link4'];
        $_SESSION['img_link5'] = $_POST['img_link5'];

        $thirty_day_offer = "30_day_insert";
        $stmt = $db->prepare("SELECT TRUNCATE(rate_amount_cents/100, 0) AS 30_day_insert FROM rates_standards WHERE rate_name=?");
        $stmt->bind_param("s", $thirty_day_offer);
        $stmt->execute();
                            
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); 
        $_SESSION['30_day_insert'] = $row['30_day_insert']; 

        if (isset($_POST['feat_offer'])) {
            $feat_offer = "featured_offer";
            $stmt = $db->prepare("SELECT TRUNCATE(rate_amount_cents/100, 0) AS feat_offer_rate FROM rates_standards WHERE rate_name=?");
            $stmt->bind_param("s", $feat_offer);
            $stmt->execute();
                                
            $result = $stmt->get_result();
            $row = $result->fetch_assoc(); 
            $_SESSION['feat_offer_charge'] = $row['feat_offer_rate']; 
        } else {
            $_SESSION['feat_offer_charge'] = 0;
        }
        if (isset($_POST['xpost_facebook'])) { 
            $xpost_facebook = "xpost_facebook";
            $stmt = $db->prepare("SELECT TRUNCATE(rate_amount_cents/100, 0) AS xpost_facebook_rate FROM rates_standards WHERE rate_name=?");
            $stmt->bind_param("s", $xpost_facebook);
            $stmt->execute();
                                
            $result = $stmt->get_result();
            $row = $result->fetch_assoc(); 
            $_SESSION['xpost_facebook'] = $row['xpost_facebook_rate']; 
        } else {
            $_SESSION['xpost_facebook'] = 0;
        }
        if (isset($_POST['auto_renew'])) { 
            $auto_renew = "auto_renew";
            $stmt = $db->prepare("SELECT TRUNCATE(rate_amount_cents/100, 0) AS renew_rate FROM rates_standards WHERE rate_name=?");
            $stmt->bind_param("s", $auto_renew);
            $stmt->execute();
                                
            $result = $stmt->get_result();
            $row = $result->fetch_assoc(); 
            $_SESSION['auto_renew'] = $row['renew_rate']; 
        } else {
            $_SESSION['auto_renew'] = 0;
        }

        $_SESSION['sum_charge'] = $_SESSION['30_day_charge'] + $_SESSION['feat_offer_charge'] + $_SESSION['xpost_facebook'] + $_SESSION['auto_renew'];
    }    
?>
<div class="row">
    <?php
        $sidebarActive = 'na';
        require 'templates/sidebar.php';
    ?>
    <div class="col-lg-9 mb-4">
        <div id="payment">
            <div class="card">
                <div class="card-header">
                    <i class="fa-solid fa-credit-card"></i> Payment
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Checkout <i class="fa-solid fa-cart-shopping"></i></h4>
                            <br>
                            <div class="row" style="color:#777;">
                                <div class="col-9">
                                    <h5>30 day insertion</h5>
                                </div>
                                <div class="col-3">
                                    <h5>$<?php echo $_SESSION['30_day_insert']; ?></h5>
                                </div>
                            </div>
                            <?php if ($_SESSION['feat_offer_charge'] > 0) { ?>
                            <div class="row" style="color:#777;">
                                <div class="col-9">
                                    <h5>Featured offer banner</h5>
                                </div>
                                <div class="col-3">
                                    <h5>$<?php echo $_SESSION['feat_offer_charge']; ?></h5>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($_SESSION['xpost_facebook'] > 0) { ?>
                            <div class="row" style="color:#777;">
                                <div class="col-9">
                                    <h5>Cross-post offer to Facebook group</h5>
                                </div>
                                <div class="col-3">
                                    <h5>$<?php echo $_SESSION['xpost_facebook']; ?></h5>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if ($_SESSION['auto_renew'] > 0) { ?>
                            <div class="row" style="color:#777;">
                                <div class="col-9">
                                    <h5>Auto renew offer (monthly charge)</h5>
                                </div>
                                <div class="col-3">
                                    <h5>$<?php echo $_SESSION['auto_renew']; ?></h5>
                                </div>
                            </div>
                            <?php } ?>
                            <br><br>
                            <div class="row" style="color:#000;">
                                <div class="col-9">
                                    <h5>Total charge</h5>
                                </div>
                                <div class="col-3">
                                    <h5>$<?php echo $_SESSION['sum_charge']; ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                          </a>
                        </div>
                    </div>
                </div>
                                    <div class="card-footer">
                        <?php if (isset($_POST['renew_field'])) { $back_link = "offer_renew.php?offerid=".$_POST['renew_field']; } else { $back_link = "offer_create.php"; } ?>
                        <a href="<?php echo $back_link; ?>" id="back_to_offer" type="button" class="btn btn-primary text-white" style="left:15px;"><i class="fa-solid fa-circle-arrow-left"></i> Back to offer</a>
                    </div>
            </div>
        </div>  
    </div>
</div>

    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>

<script>
fetch('https://api.myhelcim.com/v2/helcim-pay/initialize', payload)
  .then(response => console.log(response))
  .catch(err => console.error(err));
</script>

  </body> 
</html>