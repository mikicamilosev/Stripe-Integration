<?php 
    include 'templates/header.php';
?>

<div class="row">
    <?php
        $sidebarActive = 'na';
        require 'templates/sidebar.php';

        $offer_id = $_GET['offerid'];

        $stmt = $db->prepare("SELECT DATEDIFF(datetime_expires, NOW()) AS until_expires, title, description, featured_home, featured_browse, featured_facebook, units_available, TRUNCATE(asking_price,0) AS asking_price, img1, img2, img3, img4, img5, offer_id, product_locations, asin FROM offers WHERE offer_id=? AND owner_id=?");
        $stmt->bind_param("ss", $offer_id, $userid);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc(); 
    ?>
    <div class="col-lg-9 mb-4">
        <div class="form-wrapper active" id="new_offer" style="display:block;">
            <div class="card">
                <div class="card-header bg-danger" style="color:white;">
                    <i class="fa-solid fa-rotate"></i> Renew offer
                </div>
                <div class="card-body">
                    <form id="form-details" name="offer_form" action="offer_pay.php" method="POST">
                        <input type="hidden" value="<?php echo $offer_id; ?>" name="renew_field">
                        <div class="row">
                            <div class="mb-3">
                                <label><h5>Product name*</h5></label>
                                <input name="product_title" id="product_title" type="text" value="<?php echo $row['title']; ?>" class="form-control">
                                <div id="charactercount_warning_title"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5>Product description*</h5></label>
                                <pre><textarea name="product_desc" id="product_desc" rows="7" class="form-control" style="white-space: pre-line"><?php echo $row['description']; ?></textarea></pre>
                                <div id="character_count_desc"></div>
                                <div id="charactercount_warning_desc"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label><h5>Amazon ASIN</h5></label>
                                <input name="asin" type="text" value="<?php echo $row['asin']; ?>" class="form-control nospaces">
                            </div>
                            <div class="mb-3 col-4">
                                <div class="form-group">
                                    <label for="asin_country"><h5>Marketplace for this ASIN</h5></label>
                                    <select class="form-control form-select" name="asin_country" id="asin_country" required>
                                        <option value=".com" selected>United States (.com)</option>
                                        <option value=".co.uk">United Kingdom (.co.uk)</option>
                                        <option value=".ca">Canada (.ca)</option>
                                        <option value=".mx">Mexico States (.mx)</option>
                                        <option value=".de">Germany (.de)</option>
                                        <option value=".fr">France (.fr)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3 col-4">
                                <label><h5>Quantity to sell*</h5></label>
                                <input name="qna" type="text" class="form-control nospaces numbersonly" value="<?php echo $row['units_available']; ?>" required>
                            </div>
                            <div class="mb-3 col-4">
                                <label><h5>Total asking price $ (USD)*</h5></label>
                                <input name="price" type="text" class="form-control nospaces numbersonly" value="<?php echo $row['asking_price']; ?>" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3">
                                <h5>Currently stored at:</h5>
                                <div class="form-check">
                                    <h6>
                                        <label class="form-check-label" for="azon_usa">Amazon warehouse (United States)</label>
                                        <input class="form-check-input" type="checkbox" name="azon_usa" id="azon_usa" value="az_us">
                                    </h6>
                                    <h6>
                                        <label class="form-check-label" for="azon_overseas">Amazon warehouse (Outside USA)</label>
                                        <input class="form-check-input" type="checkbox" name="azon_ovs" id="azon_overseas" value="az_ovs">
                                    </h6>
                                    <h6>
                                        <label class="form-check-label" for="other_storage_usa">Other storage (United States)</label>
                                        <input class="form-check-input" type="checkbox" name="oth_usa" id="other_storage_usa" value="oth_usa">
                                    </h6>
                                    <h6>
                                        <label class="form-check-label" for="other_storage_overseas">Other storage (Outside USA)</label>
                                        <input class="form-check-input" type="checkbox" name="oth_ovs" id="other_storage_overseas" value="oth_ovs">
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col">
                            Add up to 5 image links. Use the direct image links from your Amazon listing or upload your images to a free image hosting service such as <a href="https://imgur.com" target="_blank" style="text-decoration:none;"><b>Imgur <i class="fa-solid fa-arrow-up-right-from-square"></i></b></a>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5><i class="fa-solid fa-image"></i> Image link 1*</h5></label>
                                <input name="img_link1" type="text" class="form-control nospaces" value="<?php echo $row['img1']; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5><i class="fa-solid fa-image"></i> Image link 2</h5></label>
                                <input name="img_link2" type="text" class="form-control nospaces" value="<?php echo $row['img2']; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5><i class="fa-solid fa-image"></i> Image link 3</h5></label>
                                <input name="img_link3" type="text" class="form-control nospaces" value="<?php echo $row['img3']; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5><i class="fa-solid fa-image"></i> Image link 4</h5></label>
                                <input name="img_link4" type="text" class="form-control nospaces" value="<?php echo $row['img4']; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5><i class="fa-solid fa-image"></i> Image link 5</h5></label>
                                <input name="img_link5" type="text" class="form-control nospaces" value="<?php echo $row['img5']; ?>">
                            </div>
                        </div>
                        <br>
                        <span style="float:right"><button id="next" type="button" style="min-width:120px;" class="btn btn-primary form-change">Next <i class="fa-solid fa-circle-arrow-right"></i></button></span>
                </div>
            </div>
        </div>
        <div class="form-wrapper" id="payment" style="display:none;">
            <div class="card">
                <div class="card-header bg-primary" style="color:white;">
                    <i class="fa-solid fa-credit-card"></i> Payment
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="30_day_offer"><h5><b><i class="fa-regular fa-calendar-check"></i> ($5) 30 Day Renewal</b></h5>
                                Your offer will be shown on our website continuously for 30 days or until you take it down. Day 1 begins once our team approves your offer.</label>
                                <input class="form-check-input" type="checkbox" name="30_day_offer" id="30_day_offer" checked disabled required>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="feat_offer"><h5 style="color:#FC8A17;"><b><i class="fa-solid fa-fire"></i> ($10) Add Featured Offer Banner</b></h5>
                                <h6>Your offer will appear in a highlighted card at the top of the 'Active offers' page for 7 continuous days to capture more impressions.</h6></label>
                                <input class="form-check-input" type="checkbox" name="feat_offer" id="feat_offer">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="xpost_facebook"><h5><b><i class="fa-brands fa-facebook"></i> ($5) Cross-post to our Facebook Page</b></h5>
                                <h6>We will post your offer to our private Facebook group, currently with 1,600 followers.</h6></label>
                                <input class="form-check-input" type="checkbox" name="xpost_facebook" id="xpost_facebook">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="auto_renew"><h5><b><i class="fa-solid fa-rotate"></i> ($5 monthly)Auto Renew</b></h5>
                                <h6>Avoid losing your offer. Listings without auto rewew are removed after 30 days. Renewal charges occur 2 days before the offer expiration date.</h6></label>
                                <input class="form-check-input" type="checkbox" name="auto_renew" id="auto_renew">
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row" style="line-height:1.4;">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="tac">
                                    <h5>
                                        <b><i class="fa-solid fa-circle-check"></i> Agree to our terms and conditions</b> 
                                    </h5>
                                </label>
                                <input class="form-check-input" type="checkbox" id="tac" required>
                            </div>
                        </div>
                        <div id="yes-terms">
                            <p>
                            <small>
                            1) Your offer will go live once it has been reviewed and approved by our team.
                            <br><br>
                            2) You may de-activate and re-activate your offer at any time during the 30 Day Insertion window by visiting the 'My offers' page. The 30 Day Insertion fee is non-refundable and pro-rated amounts cannot be refunded for unused days.
                            </small>
                            <br>
                            </p>
                            <button type="button" id="yes-readmore-button" class="btn btn-block btn-warning btn-sm" style="max-width:200px; margin-top:15px;"><i class="fa-solid fa-circle-plus"></i> Read full terms</button>
                            <div id="yes-readmore" style="display:none;">
                            <p>
                                <small>    
                                3) Our team does not arbitrate nor take part in any negotiations or deals. You are solely responsible for safeguarding yourself and your products. If you are not comfortable interacting with and negotiating with unknown people from around the world, do not use our services.
                                <br><br>
                                4) Negotiating the sale of your product(s) and exchanging money or products with people you have never met comes with inherent risk which you must understand and accept to use this website. People may try to scam you in many ways including but limited to: offering money or the promise of money if you send products to them. Once you send money you may not have any recourse to recover it.
                                <br><br>
                                5) You are responsible for supplying and executing any contracts and terms of sale you wish to make with another party.
                                <br><br>
                                6) You are responsible for obtaining sufficient documentation from the other party to safely transact a deal, safeguard yourself, your property, and your finances.
                                <br><br>
                                7) You agree to hold us harmless and release us from all liability related to the use of this website and all related services. In no circumstances are we liable for any financial or material loss you incur through the use of our services.  You further agree to indemnify and hold Online Selling Coach, LLC (the owner and operator of this website) from any Claims, losses, liability, or expenses (including attorneys' fees) that arise from a third party and relate to your use of this website; and be liable and responsible for any Claims we may have against your officers, directors, employees, agents, affiliates, or any other party, directly or indirectly, paid, directed or controlled by you, or acting for your benefit.
                                <br><br>
                                8) This website and all related services are provided with no guarantees or warranties. We do not guarantee any outcomes, results, or performance metrics for your offer including but not limited to: i) the number of impressions your offer will receive, ii) if you will receive any interest or contact from potential buyers, iii) if you will be successful in finding a buyer using our services.
                                <br><br>
                                9) We may, at our sole discretion, de-activate or re-activate listings without prior notice to you.
                                </small>
                            </p>
                            </div>
                            <button type="button" id="yes-showless2" class="btn btn-block btn-warning btn-sm" style="display:none; max-width:200px;"><i class="fa-solid fa-circle-minus"></i> Show less</button>
                            </p>
                        </div>
                    </div>
                    <br><br>
                    <button id="to_offer" type="button" class="btn btn-primary text-white"><i class="fa-solid fa-circle-arrow-left"></i> Back to offer</button>
                    <button id="to_payment" name="to_payment" type="submit" class="btn btn-primary text-white" style="float:right;min-width:120px;">Checkout <i class="fa-solid fa-circle-arrow-right"></i></button>
                    <input type="hidden" name="<?= ASCsrf::getTokenName() ?>" value="<?= ASCsrf::getToken() ?>">
                    </form>
                </div>
            </div>
        </div>  
    </div>
</div>
    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>
    <script>
    $(function() {
        $("form[name='offer_form']").validate({
            rules: {
                product_title: {
                    required: true,
                    minlength: 10,
                    maxlength: 100
                },
                product_desc: {
                    required: true,
                    minlength: 20,
                    maxlength: 1000
                },
                qna: {
                    required: true,
                    min: 1,
                    number: true
                },
                price: {
                    required: true,
                    min: 1,
                    number: true
                },
                img_link1: {
                    required: true,
                    url: true
                },
                img_link2: {
                    url: true
                },
                img_link3: {
                    url: true
                },
                img_link4: {
                    url: true
                },
                img_link5: {
                    url: true
                }
            },
            messages: {
                product_title: {
                    required: "Enter your product title",
                    minlength: "At least 10 characters",
                    maxlength: "No more than 100 characters"
                },
                product_desc: {
                    required: "Enter your product description",
                    minlength: "At least 20 characters",
                    maxlength: "No more than 1,000 characters"
                },
                qna: {
                    required: "Enter number of units to offer",
                    min: "Offer at least 1 unit",
                    number: "Must be a number"
                },
                price: {
                    required: "Enter your asking price for all units",
                    min: "Asking price must be at least 1",
                    number: "Must be a number, no $ sign"
                },
                img_link1: {
                    required: "Add at least one image link for your product",
                    url: "Must be a valid URL such as http://yourimage.com/image.jpg"
                },
                img_link2: {
                    url: "Must be a valid URL such as http://yourimage.com/image.jpg"
                },
                img_link3: {
                    url: "Must be a valid URL such as http://yourimage.com/image.jpg"
                },
                img_link4: { 
                    url: "Must be a valid URL such as http://yourimage.com/image.jpg"
                },
                img_link5: {
                    url: "Must be a valid URL such as http://yourimage.com/image.jpg"
                }
            },
            submitHandler: function(form) {
            form.submit();
            }
        });
    });
    </script>
    <script>
    $(document).on('click', '#next', function(){ 
        $("#new_offer").hide();
        $("#payment").show();
    });
    </script>
        <script>
    $(document).on('click', '#to_offer', function(){ 
        $("#new_offer").show();
        $("#payment").hide();
    });
    </script>
    <script>
$(document).ready(function(){
     $('#yes-readmore-button').click(function(){
        $('#yes-readmore').slideDown(200),
        $('#yes-readmore-button').hide(),
        $('#yes-showless1').show()
        $('#yes-showless2').show()
    });
    $('#yes-showless1').click(function(){
        $('#yes-readmore').slideUp(200),
        $('#yes-readmore-button').show()
        $('#yes-showless1').hide()
        $('#yes-showless2').hide()
    });
    $('#yes-showless2').click(function(){
        $('#yes-readmore').slideUp(200),
        $('#yes-readmore-button').show()
        $('#yes-showless1').hide()
        $('#yes-showless2').hide()
    });
});
</script>
<script>
    $("#product_title").validate();
</script>
<script>
$('.nospaces').keypress(function( e ) {
    if(e.which === 32) 
        return false;
});
</script>
<script>
$('.numbersonly').keypress(function (e) {    
    var charCode = (e.which) ? e.which : event.keyCode    
    if (String.fromCharCode(charCode).match(/[^0-9]/g))    
        return false;                        
});      
</script>
<script>
$('#product_desc').on('keyup keydown', updateCount);
function updateCount() {
    $('#character_count_desc').text($(this).val().length);
}
</script>
  </body>
</html>