<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: Login.php');
    exit;
}

require_once('vendor/autoload.php'); // Path may vary based on your project structure

$stripe = new \Stripe\StripeClient(
  'your_stripe_secret_key'
);

$amount = 1000; // Replace with the amount for the answer
$checkout_session = $stripe->checkout->sessions->create([
  'payment_method_types' => ['card'],
  'line_items' => [[
    'price_data' => [
      'currency' => 'usd',
      'product_data' => [
        'name' => 'Payment for answer',
      ],
      'unit_amount' => $amount,
    ],
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => 'http://your-website.com/success.php',
  'cancel_url' => 'http://your-website.com/cancel.php',
]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <button id="checkout-button">Pay</button>
    <script>
        var stripe = Stripe('your_stripe_publishable_key');
        var checkoutButton = document.getElementById('checkout-button');

        checkoutButton.addEventListener('click', function() {
            stripe.redirectToCheckout({ sessionId: '<?php echo $checkout_session->id; ?>' })
            .then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
