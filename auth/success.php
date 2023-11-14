<?php 
    include 'templates/header.php';
    $currentUser = app('current_user');
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
                    Success!
                </div>
            </div>
        </div>  
    </div>
</div>
    <script src="assets/js/vendor/sha512.js"></script>
    <?php include 'templates/footer.php'; ?>
  </body> 
</html>