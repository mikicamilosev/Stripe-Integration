<?php 
    include 'templates/header.php';
    include 'templates/db_connect.php';
?>

<div class="row">
    <?php
        require 'templates/sidebar.php';
    ?>
    <div class="col-lg-9 mb-4">
        <div class="form-wrapper active" id="new_offer" style="display:block;">
            <div class="card">
                <div class="card-header">
                <i class="fa-solid fa-pen-to-square"></i> Edit your offer
                </div>
                <?php 
                    $offer_id = $_GET['offerid'];

                    $stmt = $db->prepare("SELECT *, TRUNCATE(asking_price,0) AS asking_price FROM offers WHERE offer_id=?");
                    $stmt->bind_param("s", $offer_id);
                    $stmt->execute();
                    
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc(); 
                ?>
                <div class="card-body">
                    <form action="offer_edit_processing.php?offerid=<?php echo $offer_id; ?>" id="offer_form_edit" name="offer_form_edit" method="post" id="form-details">
                        <div class="row">
                            <div class="mb-3">
                                <label><h5>Product name*</h5></label>
                                <input name="product_title" id="product_title" type="text" class="form-control" value="<?php echo $row['title']; ?>">
                                <div id="charactercount_warning_title"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3">
                                <label><h5>Product description*</h5></label>
                                <pre><textarea name="product_desc" id="product_desc" rows="7" class="form-control" style="white-space: pre-line"><?php echo $row['description']; ?></textarea></pre>
                                <div id="charactercount_warning_desc"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label><h5>Amazon ASIN</h5></label>
                                <input name="asin" type="text" class="form-control nospaces">
                            </div>
                            <div class="mb-3 col-lg-4">
                                <div class="form-group">
                                    <label for="asin_market"><h5>Marketplace for this ASIN</h5></label>
                                    <select class="form-control form-select" name="asin_market" id="asin_market" required>
                                        <?php if ($row['asin_market']) { echo "<option value='".$row['asin_market']."'>".$row['asin_market']."</option><option disabled>-<option>"; } ?>
                                        <option value=".com" selected>United States (.com)</option>
                                        <option value=".ca">Canada (.ca)</option>
                                        <option value=".fr">France (.fr)</option>
                                        <option value=".de">Germany (.de)</option>
                                        <option value=".it">Italy (.it)</option>
                                        <option value=".co.jp">Japan (.co.jp)</option>
                                        <option value=".mx">Mexico (.mx)</option>
                                        <option value=".nl">Netherlands (.nl)</option>
                                        <option value=".es">Spain (.es)</option>
                                        <option value=".se">Sweden (.se)</option>
                                        <option value=".co.uk">United Kingdom (.co.uk)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label><h5>Quantity to sell*</h5></label>
                                <input name="qts" id="qts" type="text" class="form-control nospaces numbersonly" value="<?php echo $row['units_available']; ?>" required>
                            </div>
                            <div class="mb-3 col-lg-4">
                                <label><h5>Total asking price $ (USD)*</h5></label>
                                <input name="price" id="price" type="text" class="form-control nospaces numbersonly" value="<?php echo $row['asking_price']; ?>" required>
                            </div>
                        </div>
                        <br>
                        <?php
                            if (strpos($row['product_locations'], "az_us") !== false) {
                                $az_us = "checked";
                            } else { $az_us = ""; }
                            if (strpos($row['product_locations'], "az_ovs") !== false) {
                                $az_ovs = "checked";
                            } else { $az_ovs = ""; }
                            if (strpos($row['product_locations'], "oth_usa") !== false) {
                                $oth_usa = "checked";
                            } else { $oth_usa = ""; }
                            if (strpos($row['product_locations'], "oth_ovs") !== false) {
                                $oth_ovs = "checked";
                            } else { $oth_ovs = ""; }
                        ?>
                        <div class="row">
                            <div class="mb-3">
                                <h5>Currently stored at:</h5>
                                <div class="form-check">
                                    <h6>
                                        <label class="form-check-label" for="azon_usa">Amazon warehouse (United States)</label>
                                        <input class="form-check-input" type="checkbox" id="azon_usa" value="az_us" <?php echo $az_us; ?>>
                                    </h6>
                                    <h6>
                                        <label class="form-check-label" for="azon_overseas">Amazon warehouse (Outside USA)</label>
                                        <input class="form-check-input" type="checkbox" id="azon_overseas" value="az_ovs" <?php echo $az_ovs; ?>>
                                    </h6>
                                    <h6>
                                        <label class="form-check-label" for="other_storage_usa">Other storage (United States)</label>
                                        <input class="form-check-input" type="checkbox" id="other_storage_usa" value="oth_usa" <?php echo $oth_usa; ?>>
                                    </h6>
                                    <h6>
                                        <label class="form-check-label" for="other_storage_overseas">Other storage (Outside USA)</label>
                                        <input class="form-check-input" type="checkbox" id="other_storage_overseas" value="oth_ovs" <?php echo $oth_ovs; ?>>
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
                                <input name="img_link1" id="img_link1" type="text" class="form-control nospaces" value="<?php echo $row['img1']; ?>" required>
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
                        <input type="hidden" name="<?= ASCsrf::getTokenName() ?>" value="<?= ASCsrf::getToken() ?>">
                        <button id="submit_edits" type="submit" class="btn btn-primary form-change" style="float:right">Submit edits <i class="fa-solid fa-circle-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
            </div>
        </div>  
    </div>
</div>
    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>
<script>
    $(function() {
        $("form[name='offer_form_edit']").validate({
            rules: {
                product_title: {
                    required: true,
                    minlength: 10,
                    maxlength: 100
                },
                product_desc: {
                    required: true,
                    minlength: 20,
                    maxlength: 700
                },
                qts: {
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
                    required: "Enter your product title"
                },
                product_desc: {
                    required: "Enter your product description",
                },
                qts: {
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
$('#product_title').keyup(function(){
    var len = $('#product_title').val().length;
    document.getElementById("charactercount_warning_title").innerHTML = ""+ len +" characters";

    if(len > 100 || len < 10){
        document.getElementById("submit_edits").innerHTML = "Submit edits <i class='fa-solid fa-ban'></i>",
        $("#submit_edits").prop("disabled",true);
    }
});

$('#product_desc').keyup(function(){
    var len = $('#product_desc').val().length;
    document.getElementById("charactercount_warning_desc").innerHTML = ""+ len +" characters";

    if(len > 700 || len < 20){
        document.getElementById("submit_edits").innerHTML = "Submit edits <i class='fa-solid fa-ban'></i>",
        $("#submit_edits").prop("disabled",true);
    }
});
</script>
<script>
$('#product_desc,#product_title,#img_link1,#qts,#price').keyup(function(){
    var len_title = $('#product_title').val().length,
    len_desc = $('#product_desc').val().length;

    if(len_title < 101 && len_title > 9 && len_desc < 701 && len_desc > 19){
        var img_link1 = $('#img_link1').val().length,
        len_qts = $('#qts').val().length,
        len_price = $('#price').val().length;

        if(img_link1 > 0 && len_qts > 0 && len_price > 0){
            document.getElementById("submit_edits").innerHTML = "Submit edits <i class='fa-solid fa-circle-arrow-right'></i>",
            $("#submit_edits").prop("disabled",false);
        } else {
            document.getElementById("submit_edits").innerHTML = "Submit edits <i class='fa-solid fa-ban'></i>",
            $("#submit_edits").prop("disabled",true);
        }
    }
});
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
  </body>
</html>