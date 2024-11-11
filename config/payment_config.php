<?php
/**
 * @package UnifiedPaymentGateway\Config
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 Siddik Web
 * @license MIT
 * @link https://github.com/siddik-web/unified-payment-gateway
 * @version 1.0.0
 * @since 1.0.0
 */

// Configuration for the payment providers.
return [
    'paypal' => [
        'client_id' => 'your_paypal_client_id',
        'client_secret' => 'your_paypal_secret',
        'environment' => 'sandbox',
        'webhook_url' => 'https://example.com/paypal/webhook'
    ],
    'stripe' => [
        'api_key' => 'your_stripe_secret_key',
        'webhook_secret' => 'your_stripe_webhook_secret'
    ],
    'default_provider' => 'stripe'
];
