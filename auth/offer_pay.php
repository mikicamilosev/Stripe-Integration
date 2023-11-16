<?php 
    include 'templates/header.php';
    include 'templates/db_connect.php';
    
        $_SESSION['offer_id'] = $_GET['offerid'];
        $_SESSION['title'] = $_POST['product_title'];
        $_SESSION['desc'] = $_POST['product_desc'];
        $_SESSION['asin'] = $_POST['asin'];
        $_SESSION['asin_country'] = $_POST['asin_country'];
        $_SESSION['qts'] = $_POST['qts'];
        $_SESSION['price'] = $_POST['price'];
        $production_location = '';
        if (isset($_POST['azon_usa'])) { $production_location = $production_location . " az_us"; }
        if (isset($_POST['azon_ovs'])) { $production_location = $production_location . "az_ovs"; }
        if (isset($_POST['oth_usa'])) { $production_location = $production_location . "oth_usa"; }
        if (isset($_POST['oth_ovs'])) { $production_location = $production_location . "oth_ovs"; }
        $_SESSION['production_location'] = $production_location;
        $_SESSION['img_link1'] = $_POST['img_link1'];
        $_SESSION['img_link2'] = $_POST['img_link2'];
        $_SESSION['img_link3'] = $_POST['img_link3'];
        $_SESSION['img_link4'] = $_POST['img_link4'];
        $_SESSION['img_link5'] = $_POST['img_link5'];

        $rateName = "30_day_insert";
        $query = "SELECT * FROM rates_standards WHERE rate_name = '$rateName'";
        $result = $db->query($query);
        $firstRow = mysqli_fetch_assoc($result);
        $_SESSION['30_day_insert'] = $firstRow['rate_amount_cents'] / 100;

        if (isset($_POST['feat_offer'])) {
            $rateName = "featured_offer";
            $query = "SELECT * FROM rates_standards WHERE rate_name = '$rateName'";
            $result = $db->query($query);
            $firstRow = mysqli_fetch_assoc($result);
            $_SESSION['feat_offer_charge'] = $firstRow['rate_amount_cents'] / 100;
        } else {
            $_SESSION['feat_offer_charge'] = 0;
        }
        if (isset($_POST['xpost_facebook'])) { 
            $rateName = "xpost_facebook";
            $query = "SELECT * FROM rates_standards WHERE rate_name = '$rateName'";
            $result = $db->query($query);
            $firstRow = mysqli_fetch_assoc($result);
            $_SESSION['xpost_facebook'] = $firstRow['rate_amount_cents'] / 100;
        } else {
            $_SESSION['xpost_facebook'] = 0;
        }
        if (isset($_POST['auto_renew'])) { 
            $rateName = "auto_renew";
            $query = "SELECT * FROM rates_standards WHERE rate_name = '$rateName'";
            $result = $db->query($query);
            $firstRow = mysqli_fetch_assoc($result);
            $_SESSION['auto_renew'] = $firstRow['rate_amount_cents'] / 100;
        } else {
            $_SESSION['auto_renew'] = 0;
        }

        $_SESSION['sum_charge'] = $_SESSION['30_day_insert'] + $_SESSION['feat_offer_charge'] + $_SESSION['xpost_facebook'] + $_SESSION['auto_renew'];
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
                                    <h5>30 Day Renewal</h5>
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
                        <div class="col-md-6" id="checkout">
                          
                        </div>
                    </div>
                </div>
                    <d  iv class="card-footer">
                      <?php if (isset($_POST['renew_field'])) { $back_link = "offer_renew.php?offerid=".$_POST['renew_field']; } else { $back_link = "offer_create.php"; } ?>
                      <a href="<?php echo $back_link; ?>" id="back_to_offer" type="button" class="btn btn-primary text-white" style="left:15px;"><i class="fa-solid fa-circle-arrow-left"></i> Back to offer</a>
                  </d>
            </div>
        </div>  
    </div>
</div>

    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>
<script>
  const stripe = Stripe("pk_test_TYooMQauvdEDq54NiTphI7jx");
  
  initialize();
  
  // Create a Checkout Session as soon as the page loads
  async function initialize() {
    var offer_info = {
        offer_id: '<?= $_SESSION['offer_id'] ?>',
        title: '<?= $_SESSION['title'] ?>',
        desc: '<?= $_SESSION['desc'] ?>',
        asin: '<?= $_SESSION['asin'] ?>',
        asin_country: '<?= $_SESSION['asin_country'] ?>',
        qts: '<?= $_SESSION['qts'] ?>',
        price: '<?= $_SESSION['price'] ?>',
        product_location: '<?= $_SESSION['production_location'] ?>',
        img_link1: '<?= $_SESSION['img_link1'] ?>',
        img_link2: '<?= $_SESSION['img_link2'] ?>',
        img_link3: '<?= $_SESSION['img_link3'] ?>',
        img_link4: '<?= $_SESSION['img_link4'] ?>',
        img_link5: '<?= $_SESSION['img_link5'] ?>',
        month_charge: '<?= $_SESSION['30_day_insert'] ?>',
        feat_offer: '<?= $_SESSION['feat_offer_charge'] ?>',
        xpost_facebook: '<?= $_SESSION['xpost_facebook'] ?>',
        auto_renew: '<?= $_SESSION['auto_renew'] ?>',
        tac: '',
        owner_id: '<?= $currentUser->id ?>',
        owner_name: '<?= $currentUser->username ?>',
    }
    const response = await fetch("./templates/checkout/offer_create.php", {
        method: "POST",
        headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
        body: Object.entries(offer_info).map(([k,v])=>{return k+'='+v}).join('&')
    });

    const { clientSecret } = await response.json();
    
    const checkout = await stripe.initEmbeddedCheckout({
      clientSecret,
    });
    
    // Mount Checkout
    checkout.mount('#checkout');
  }

//   fetch('https://api.myhelcim.com/v2/helcim-pay/initialize', payload)
//     .then(response => console.log(response))
//     .catch(err => console.error(err));
</script>

  </body> 
</html>