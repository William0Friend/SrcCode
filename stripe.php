<?php
#Include the Stripe PHP library in your PHP code using the following line:
require_once('/path/to/stripe-php/init.php');
#Set up the Stripe API keys:
\Stripe\Stripe::setApiKey('sk_test_XXXXXXXXXXXXXXXXXXXXXXXX');
#Use the Stripe PHP library to create and confirm a payment intent:
$intent = \Stripe\PaymentIntent::create([
    'amount' => 1000,
    'currency' => 'usd',
    'payment_method_types' => ['card'],
  ]);
  
  $intent = \Stripe\PaymentIntent::retrieve($intent->id);
  $intent->confirm();
// Define the payment notification endpoint
$app->post('/api/payment-notification', function() use ($app) {
    // Get the payment gateway response from the request body
    $response = json_decode($app->request->getBody(), true);
    
    // Verify the payment gateway response using the Stripe API
    require_once('/path/to/stripe-php/init.php');
    \Stripe\Stripe::setApiKey('sk_test_XXXXXXXXXXXXXXXXXXXXXXXX');
    $event = \Stripe\Event::retrieve($response['id']);
    
    // Handle the payment notification based on the event type
    switch ($event->type) {
        case 'payment_intent.succeeded':
            // Update the database to mark the transaction as successful
            break;
        case 'payment_intent.failed':
            // Update the database to mark the transaction as failed
            break;
    }
});
// Set up a webhook to receive payment notifications
$body = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

require_once('/path/to/stripe-php/init.php');

try {
  $event = \Stripe\Webhook::constructEvent(
    $body, $sig_header, 'whsec_XXXXXXXXXXXXXXXXXXXXXXXX'
  );
} catch (\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the payment notification based on the event type
switch ($event->type) {
  case 'payment_intent.succeeded':
    // Update the database to mark the transaction as successful
    break;
  case 'payment_intent.failed':
    // Update the database to mark the transaction as failed
    break;
}

http_response_code(200);
?>