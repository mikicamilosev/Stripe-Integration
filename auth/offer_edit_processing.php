<?php 
    include 'templates/header.php';
    include 'templates/db_connect.php';
?>

<div class="row">
    <?php
        require 'templates/sidebar.php';
    ?>
            <div class="col-lg-9 mb-4">
                <div class="form-wrapper active" id="edit_offer" style="display:block;">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa-solid fa-pen-to-square"></i> Edit your offer
                        </div>
                        <div class="card-body">
                            <br>
                            <br>
                            <div style="text-align:center;">
                                <h5>Processing edits...</h5>
                                <img src="assets/img/loading.gif" style="max-width:250px"/>
                            </div>
                            <br>                    
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
    var offerid = "<?php echo $_GET['offerid']; ?>"
    function pageRedirect() {
        window.location.replace("https://www.nickc36.sg-host.com/auth/offer_edit_complete.php?offerid=" + offerid + "");
    }      
    setTimeout("pageRedirect()", 4000);
</script>
  </body>
</html>
    