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
 * @class UnifiedPaymentClient
 * @package UnifiedPaymentGateway\UnifiedPayment
 * @description The unified payment client for the payment gateway.
 * @since 1.0.0
 */
class UnifiedPaymentClient
{
    /**
     * @var UnifiedPaymentGateway
     * @description The payment gateway for the unified payment client.
     * @since 1.0.0
     */
    public $paymentGateway;

    /**
     * @method __construct
     * @param UnifiedPaymentGateway $paymentGateway The payment gateway for the unified payment client.
     * @description The constructor for the unified payment client.
     * @since 1.0.0
     */
    public function __construct(UnifiedPaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the payment provider.
     * @description Create a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function createPaymentIntent(array $paymentData)
    {
        return $this->paymentGateway->createPaymentIntent($paymentData);
    }

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the payment provider.
     * @description Capture a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function capturePaymentIntent($paymentId)
    {
        return $this->paymentGateway->capturePaymentIntent($paymentId);
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
        return $this->paymentGateway->refundPayment($paymentId, $refundData);
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @description Create a subscription with the payment provider.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        return $this->paymentGateway->createSubscription($subscriptionData);
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @description Cancel a subscription with the payment provider.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->paymentGateway->cancelSubscription($subscriptionId);
    }
}
