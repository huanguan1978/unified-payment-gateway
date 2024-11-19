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

use UnifiedPaymentGateway\PayPalApi\PayPalClient;

/**
 * @class PayPalPaymentGateway
 * @package UnifiedPaymentGateway\UnifiedPayment
 * @description The PayPal payment gateway implementation.
 * @since 1.0.0
 */
class PayPalPaymentGateway implements UnifiedPaymentGateway
{
    /**
     * @var PayPalClient
     * @description The PayPal client for the payment gateway.
     * @since 1.0.0
     */
    private $payPalClient;

    /**
     * @method __construct
     * @param array $config Configuration for the PayPal client.
     * @description The constructor for the PayPal payment gateway.
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        $this->payPalClient = new PayPalClient($config);
    }

    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the payment provider.
     * @description Create a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function createPaymentIntent(array $paymentData)
    {
        return $this->payPalClient->createPayment($paymentData);
    }

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the payment provider.
     * @description Capture a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function capturePaymentIntent($paymentId)
    {
        return $this->payPalClient->capturePayment($paymentId);
    }

    /**
     * @method executePaymentIntent
     * @param string $paymentId Payment ID for the payment provider.
     * @param string $payerId Payer ID for the payment provider.
     * @description Capture a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function executePaymentIntent($paymentId, $payerId)
    {
        return $this->payPalClient->executePayment($paymentId, $payerId);
    }


    /**
     * @method refundPayment
     * @param string $paymentId Payment ID for the payment provider.
     * @param array $refundData Refund data for the payment provider.
     * @description Refund a payment with the payment provider.
     * @since 1.0.0
     */
    public function refundPayment($paymentId, array $refundData)
    {
        return $this->payPalClient->refundPayment($paymentId, $refundData);
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @description Create a subscription with the payment provider.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        return $this->payPalClient->createSubscription($subscriptionData);
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @description Cancel a subscription with the payment provider.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->payPalClient->cancelSubscription($subscriptionId);
    }
}
