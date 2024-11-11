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

use UnifiedPaymentGateway\StripeApi\StripeClient;

/**
 * @class StripePaymentGateway
 * @package UnifiedPaymentGateway\UnifiedPayment
 * @description The Stripe payment gateway implementation.
 * @since 1.0.0
 */
class StripePaymentGateway implements UnifiedPaymentGateway
{
    /**
     * @var StripeClient
     * @description The Stripe client for the payment gateway.
     * @since 1.0.0
     */
    private $stripeClient;

    /**
     * @method __construct
     * @param array $config Configuration for the Stripe client.
     * @description The constructor for the Stripe payment gateway.
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        $this->stripeClient = new StripeClient($config);
    }

    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the payment provider.
     * @description Create a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function createPaymentIntent(array $paymentData)
    {
        return $this->stripeClient->createPaymentIntent($paymentData);
    }

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the payment provider.
     * @description Capture a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function capturePaymentIntent($paymentId)
    {
        return $this->stripeClient->capturePaymentIntent($paymentId);
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
        return $this->stripeClient->refundPayment($paymentId, $refundData);
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @description Create a subscription with the payment provider.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        return $this->stripeClient->createSubscription($subscriptionData);
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @description Cancel a subscription with the payment provider.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->stripeClient->cancelSubscription($subscriptionId);
    }
}
