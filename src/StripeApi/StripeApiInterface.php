<?php
/**
 * @package UnifiedPaymentGateway\StripeApi
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 Siddik Web
 * @license MIT
 * @link https://github.com/siddik-web/unified-payment-gateway
 * @version 1.0.0
 * @since 1.0.0
 */

namespace UnifiedPaymentGateway\StripeApi;

/**
 * @interface StripeApiInterface
 * @package UnifiedPaymentGateway\StripeApi
 * @description The Stripe API interface.
 * @since 1.0.0
 */
interface StripeApiInterface
{
    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the Stripe API.
     * @return mixed
     * @description Create a payment intent with Stripe.
     * @since 1.0.0
     */
    public function createPaymentIntent(array $paymentData);

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the Stripe API.
     * @return mixed
     * @description Capture a payment intent with Stripe.
     * @since 1.0.0
     */
    public function capturePaymentIntent($paymentId);

    /**
     * @method refundPayment
     * @param string $paymentId Payment ID for the Stripe API.
     * @param array $refundData Refund data for the Stripe API.
     * @return mixed
     * @description Refund a payment with Stripe.
     * @since 1.0.0
     */
    public function refundPayment($paymentId, array $refundData);

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the Stripe API.
     * @return mixed
     * @description Create a subscription with Stripe.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData);

    /**
     * @method updateSubscription
     * @param string $subscriptionId Subscription ID for the Stripe API.
     * @param array $subscriptionData Subscription data for the Stripe API.
     * @return mixed
     * @description Update a subscription with Stripe.
     * @since 1.0.0
     */
    public function updateSubscription($subscriptionId, array $subscriptionData);

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the Stripe API.
     * @return mixed
     * @description Cancel a subscription with Stripe.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId);

    /**
     * @method handleWebhook
     * @param array $webhookData Webhook data for the Stripe API.
     * @return mixed
     * @description Handle a webhook with Stripe.
     * @since 1.0.0
     */
    public function handleWebhook(array $webhookData);
}
