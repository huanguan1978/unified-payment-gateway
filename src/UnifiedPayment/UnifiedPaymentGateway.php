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
 * @interface UnifiedPaymentGateway
 * @package UnifiedPaymentGateway\UnifiedPayment
 * @description The unified payment gateway interface.
 * @since 1.0.0
 */
interface UnifiedPaymentGateway
{
    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the payment provider.
     * @description Create a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function createPaymentIntent(array $paymentData);

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the payment provider.
     * @description Capture a payment intent with the payment provider.
     * @since 1.0.0
     */
    public function capturePaymentIntent($paymentId);

    /**
     * @method refundPayment
     * @param string $paymentId Payment ID for the payment provider.
     * @param array $refundData Refund data for the payment provider.
     * @description Refund a payment with the payment provider.
     * @since 1.0.0
     */
    public function refundPayment($paymentId, array $refundData);

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @description Create a subscription with the payment provider.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData);

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @description Cancel a subscription with the payment provider.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId);
}
