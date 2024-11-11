
# Unified Payment Gateway

**Unified Payment Gateway** is a PHP library that provides a unified interface for integrating both PayPal and Stripe payments. This package is designed with the SOLID principles in mind, making it modular, extendable, and easy to use in any PHP project.

## Features

- Unified API for handling payments across PayPal and Stripe.
- Support for creating payments, capturing, and refunds.
- Subscription management for both PayPal and Stripe.
- Webhook handling for both PayPal and Stripe.
- Easy configuration through a single config file.

## Installation

Install the package using Composer:

```bash
composer require siddik-web/unified-payment-gateway
```

## Configuration

To configure your payment providers, create a configuration file (e.g., `config/payment_config.php`) with the following structure:

```php
<?php
return [
    'paypal' => [
        'client_id' => 'your_paypal_client_id',
        'client_secret' => 'your_paypal_secret',
        'environment' => 'sandbox',  // Use 'production' for live environment
        'webhook_url' => 'https://example.com/paypal/webhook'
    ],
    'stripe' => [
        'api_key' => 'your_stripe_secret_key',
        'webhook_secret' => 'your_stripe_webhook_secret'
    ],
    'default_provider' => 'stripe'  // or 'paypal'
];
```

## Usage

### Initialize the Unified Payment Client

To start using the library, create an instance of `UnifiedPaymentClient` through the `UnifiedPaymentFactory`.

```php
use UnifiedPaymentGateway\UnifiedPaymentFactory;

$config = include 'config/payment_config.php';
$paymentClient = UnifiedPaymentFactory::createPaymentClient($config);
```

### Creating a Payment

```php
$paymentData = [
    'amount' => 5000,  // Amount in cents for Stripe (5000 = $50.00)
    'currency' => 'usd',
    'description' => 'Order #12345'
];

$response = $paymentClient->createPayment($paymentData);
echo "Payment created: " . json_encode($response);
```

### Capturing a Payment

```php
$paymentId = 'your_payment_id';
$response = $paymentClient->capturePayment($paymentId);
echo "Payment captured: " . json_encode($response);
```

### Refunding a Payment

```php
$refundData = [
    'amount' => 2000  // Amount to refund (optional)
];
$response = $paymentClient->refundPayment($paymentId, $refundData);
echo "Payment refunded: " . json_encode($response);
```

### Managing Subscriptions

To create a subscription:

```php
$subscriptionData = [
    'plan_id' => 'your_plan_id',
    'customer_id' => 'your_customer_id'
];

$response = $paymentClient->createSubscription($subscriptionData);
echo "Subscription created: " . json_encode($response);
```

### Webhook Handling

To handle webhooks, use the `handleWebhook` method and pass in the webhook payload:

```php
$webhookData = json_decode(file_get_contents('php://input'), true);
$response = $paymentClient->handleWebhook($webhookData);
```

## Folder Structure

The main files and folders in this package include:

```
src/
├── PayPalApi/
├── StripeApi/
└── UnifiedPayment/
config/
└── payment_config.php  # Configuration file example
```

- `src/PayPalApi`: Contains classes for managing PayPal-specific functionality.
- `src/StripeApi`: Contains classes for managing Stripe-specific functionality.
- `src/UnifiedPayment`: Contains classes for unified payment management.

## Requirements

- PHP 8.0 or higher
- Composer
- [Guzzle](https://github.com/guzzle/guzzle) for HTTP requests (automatically installed as a dependency)

## License

This project is licensed under the MIT License.

---

Feel free to contribute, open issues, or request new features!
