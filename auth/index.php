<?php

include 'templates/header.php';
$comments = app('comment')->getComments();
?>
        
<div class="row">
    <?php
        // Include sidebar template
        // and set active page to "home".
        $sidebarActive = 'home';
        require 'templates/sidebar.php';
    ?>

    <div class="col-lg-9">
    <?php if ($row['featured_listings']) { ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <b><i class="fa-solid fa-fire"></i> Featured listing</b>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">test</div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php } ?> 
        <div class="row">
            <div class="col-4">
                <div>
                    <article class="shadow-sm rounded card mb-2">
                        <img src="assets/img/main_shop.png" style="max-width:205px;" class="card-img-top mx-auto d-block">
                        <div class="card-body">
                            <h4 class="card-title">Browse offers</h4>
                            <p class="card-text">
                                Sort, filter, and hunt for your next amazing deal.  There's a lot of treasure in here!
                            </p>
                            <a role="button" tabindex="0" href="offers_browse.php" target="" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Browse all</a>
                        </div>
                    </article>
                </div>
            </div>       
            <div class="col-4">
                <div>
                    <article class="shadow-sm rounded card mb-2">
                        <img src="assets/img/main_clock.png" style="max-width:180px; margin-top:20px;margin-bottom:20px;" class="card-img-top mx-auto d-block">
                        <div class="card-body">
                            <h4 class="card-title">Leaving soon</h4>
                            <p class="card-text">
                                These offers are set to expire any time now.  Take one last look, once they're gone they may not ever be back!
                            </p>
                            <a role="button" tabindex="0" href="offers_browse.php" target="" class="btn btn-warning"><i class="fa-regular fa-hourglass-half"></i> Offers leaving soon</a>
                        </div>
                    </article>
                </div>
            </div>
            <div class="col-4">
                <div>
                    <article class="shadow-sm rounded card mb-2">
                        <img src="assets/img/main_money.png" style="max-width:200px; margin-top:20px;margin-bottom:20px;" class="card-img-top mx-auto d-block">
                        <div class="card-body">
                            <h4 class="card-title">List yours</h4>
                            <p class="card-text">
                                Have products you want to get rid of? List yours today to reach buyers looking for great deals.
                            </p>
                            <a role="button" tabindex="0" href="offer_create.php" target="" class="btn btn-primary"><i class="fa-solid fa-circle-plus"></i> Add your offer</a>
                        </div>
                    </article>
                </div>
            </div>
        </div>        
    </div>
</div>

    <?php include 'templates/footer.php'; ?>
    <script src="assets/js/app/index.js"></script>
  </body>
</html>
