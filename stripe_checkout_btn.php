<?php
require_once('vendor/autoload.php'); // Include the Stripe PHP library

\Stripe\Stripe::setApiKey('YOUR_STRIPE_SECRET_KEY'); // Replace with your own Stripe Secret Key

$session = \Stripe\Checkout\Session::create([
  'payment_method_types' => ['card'],
  'line_items' => [
    [
      'price_data' => [
        'currency' => 'usd',
        'unit_amount' => 2000, // Replace with the actual price in cents
        'product_data' => [
          'name' => 'Your Product Name',
        ],
      ],
      'quantity' => 1,
    ],
  ],
  'mode' => 'payment',
  'success_url' => 'https://example.com/success', // Replace with your own success URL
  'cancel_url' => 'https://example.com/cancel', // Replace with your own cancel URL
]);

$checkout_session_id = $session->id;
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