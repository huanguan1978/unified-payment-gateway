<?php
/**
 * @package UnifiedPaymentGateway\UnifiedPayment
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 Siddik Web
 * @license MIT
 * @link https://github.com/siddik-web/unified-payment-gateway
 * @version 1.0.0
 * @since 1.0.0
 */

namespace UnifiedPaymentGateway\UnifiedPayment;

/**
 * @class UnifiedPaymentFactory
 * @package UnifiedPaymentGateway\UnifiedPayment
 * @description The factory for the unified payment client.
 * @since 1.0.0
 */
class UnifiedPaymentFactory
{
    /**
     * @method create
     * @param array $config Configuration for the payment gateway.
     * @description Create a unified payment client with the payment gateway.
     * @since 1.0.0
     */
    public static function create(array $config): UnifiedPaymentClient
    {
        switch ($config['provider']) {
            case 'paypal':
                $gateway = new PayPalPaymentGateway($config);
                break;
            case 'stripe':
                $gateway = new StripePaymentGateway($config);
                break;
            default:
                throw new \Exception("Unsupported payment provider: {$config['provider']}");
        }

        return new UnifiedPaymentClient($gateway);
    }
}
