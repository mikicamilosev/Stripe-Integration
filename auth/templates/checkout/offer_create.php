<?php

require_once '../db_connect.php';
require_once '../../vendor/autoload.php';
require_once '../../ASEngine/ASConfig.php';
require_once 'secrets.php';
$featured_home;
$featured_browse;
$featured_facebook;
if ($_POST['feat_offer'] > 0) {
  $featured_home = "Y";
} else {
  $featured_home = "N";
}
if ($_POST['xpost_facebook'] > 0) {
  $featured_browse = "Y";
} else {
  $featured_browse = "N";
}
if ($_POST['auto_renew'] > 0) {
  $featured_facebook = "Y";
} else {
  $featured_facebook = "N";
}

function generateUniqueId()
{
  // Generate a unique ID using the current timestamp and a random number
  $timestamp = microtime(true); // Get the current timestamp with microseconds
  $randomNumber = mt_rand(); // Generate a random number

  // Concatenate and hash the timestamp and random number to create the ID
  $id = md5($timestamp . $randomNumber);

  return $id;
}

// Example usage
$uniqueId = generateUniqueId();

$datetime_added = date('Y-m-d H:i:s');
$nextMonth = date('Y-m-d H:i:s', strtotime('+1 month'));

$_POST['product_location'];
$unit_price = $_POST['price'] / $_POST['qts'];
if($_POST['offer_id'] !== ""){
  $sql = "UPDATE offers SET title='" . $_POST['title'] . "', description='" . $_POST['desc'] . "', datetime_added='" . $datetime_added . "', datetime_expires='" . $nextMonth . "', owner_id='" . $_POST['owner_id'] . "', owner_name='" . $_POST['owner_name'] . "', asin='" . $_POST['asin'] . "', asin_market='" . $_POST['asin_country'] . "', featured_home='" . $featured_home . "', featured_browse='" . $featured_browse . "', featured_facebook='" . $featured_facebook . "', product_locations='" . $_POST['product_location'] . "', units_available='" . $_POST['qts'] . "', asking_price='" . $_POST['price'] . "', price_per_unit='" . $unit_price . "', img1='" . $_POST['img_link1'] . "', img2='" . $_POST['img_link2'] . "', img3='" . $_POST['img_link3'] . "', img4='" . $_POST['img_link4'] . "', img5='" . $_POST['img_link5'] . "' WHERE offer_id='". $_POST["offer_id"] . "'";
} else {
  $sql = "INSERT INTO offers (offer_id, offer_status, title, description, datetime_added, datetime_expires, owner_id, owner_name, asin, asin_market, featured_home, featured_browse, featured_facebook, product_locations, units_available, asking_price, price_per_unit, img1, img2, img3, img4, img5, count_views) VALUES ('" . $uniqueId . "', 'active', '" . $_POST['title'] . "', '" . $_POST['desc'] . "', '" . $datetime_added . "', '" . $nextMonth . "', '" . $_POST['owner_id'] . "', '" . $_POST['owner_name'] . "', '" . $_POST['asin'] . "', '" . $_POST['asin_country'] . "', '" . $featured_home . "', '" . $featured_browse . "', '" . $featured_facebook . "', '" . $_POST['product_location'] . "', '" . $_POST['qts'] . "', '" . $_POST['price'] . "', '" . $unit_price . "', '" . $_POST['img_link1'] . "', '" . $_POST['img_link2'] . "', '" . $_POST['img_link3'] . "', '" . $_POST['img_link4'] . "', '" . $_POST['img_link5'] . "', '0')";
}
$db->query($sql);
$db->close();

$stripe = new \Stripe\StripeClient($stripeSecretKey);
header('Content-Type: application/json');

$YOUR_DOMAIN = WEBSITE_DOMAIN . 'auth/templates/checkout';

if($_POST['auto_renew'] > 0){
  $checkout_session = $stripe->checkout->sessions->create([
    'ui_mode' => 'embedded',
    'line_items' => [
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => 'Auto Renew(monthly)',
            'description' => $_POST['desc'],
            'images' => [
              $_POST['img_link1'],
            ]
          ],
          'unit_amount' => $_POST['auto_renew'] * 100,
          'recurring' => [
            'interval' => 'month',
            'interval_count' => 6
          ]
        ],
        'quantity' => 1,
      ],
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => '30 Day Renewal',
            'description' => $_POST['desc'],
            'images' => [
              $_POST['img_link1'],
            ]
          ],
          'unit_amount' => $_POST['month_charge'] * 100,
        ],
        'quantity' => 1,
      ],
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => 'Featured offer banner',
          ],
          'unit_amount' => $_POST['feat_offer'] * 100,
        ],
        'quantity' => 1,
      ],
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => 'Cross-post offer to Facebook group',
          ],
          'unit_amount' => $_POST['xpost_facebook'] * 100,
        ],
        'quantity' => 1,
      ],
    ],
    'mode' => 'subscription',
    'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',
  ]);
} else {
  $checkout_session = $stripe->checkout->sessions->create([
    'ui_mode' => 'embedded',
    'line_items' => [
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => '30 Day Renewal',
            'description' => $_POST['desc'],
            'images' => [
              $_POST['img_link1'],
            ]
          ],
          'unit_amount' => $_POST['month_charge'] * 100,
        ],
        'quantity' => 1,
      ],
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => 'Featured offer banner',
          ],
          'unit_amount' => $_POST['feat_offer'] * 100,
        ],
        'quantity' => 1,
      ],
      [
        'price_data' => [
          'currency' => 'usd',
          'product_data' => [
            'name' => 'Cross-post offer to Facebook group',
          ],
          'unit_amount' => $_POST['xpost_facebook'] * 100,
        ],
        'quantity' => 1,
      ],
    ],
    'mode' => 'payment',
    'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',
  ]);
}


 
echo json_encode(array('clientSecret' => $checkout_session->client_secret));