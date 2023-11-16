<?php
    include dirname(__FILE__) . '/../ASEngine/AS.php';
    if (! app('login')->isLoggedIn()) {
        redirect("login.php");
    }
    $currentUser = app('current_user');
    $userid = $currentUser->id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rid My Inventory - Liquidation marketplace for e-commerce products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/app.css" type="text/css" media="all" />
    <link rel="stylesheet" href="assets/css/negotiations.css" type="text/css" media="all" />
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    
    <!-- Fotorama -->
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>

    <!-- Stripe Checkout -->
    <script src="https://js.stripe.com/v3/"></script>
    
</head>
<body>
    <div class="cover-container d-flex flex-column">
        <?php include 'navbar.php'; ?>
            <!-- start: container -->
            <div class="container pt-4">