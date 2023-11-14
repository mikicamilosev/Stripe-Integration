<?php
require 'vendor/autoload.php';

$stripe = new \Stripe\StripeClient('sk_test_4eC39HqLyjWDarjtT1zdp7dc');

$checkout_session = $stripe->checkout->sessions->create([
  'line_items' => [[
    'price_data' => [
      'currency' => 'usd',
      'product_data' => [
        'name' => 'T-shirt',
      ],
      'unit_amount' => 2000,
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'http://localhost/success',
  'cancel_url' => 'http://localhost/cancel',
]);

header("HTTP/1.1 303 See Other");

?>

<!DOCTYPE html>
<html>
<head>
  <title>Stripe Checkout</title>
  <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
  <button id="checkout-button">Checkout</button>

  <script>
    var stripe = Stripe('YOUR_STRIPE_PUBLISHABLE_KEY'); // Replace with your own Stripe Publishable Key

    document.getElementById('checkout-button').addEventListener('click', function() {
      stripe.redirectToCheckout({
        sessionId: '<?php echo $checkout_session_id; ?>'
      }).then(function(result) {
        // Handle any errors that occur during Checkout
      });
    });
  </script>
</body>
</html>