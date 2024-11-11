<?php
/**
 * @package UnifiedPaymentGateway\PayPalApi
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 Siddik Web
 * @license MIT
 * @link https://github.com/siddik-web/unified-payment-gateway
 * @version 1.0.0
 * @since 1.0.0
 */

namespace UnifiedPaymentGateway\PayPalApi;

/**
 * @interface PayPalApiInterface
 * @package UnifiedPaymentGateway\PayPalApi
 * @description This interface defines the methods for interacting with the PayPal API.
 * @since 1.0.0
 */
interface PayPalApiInterface
{
    /**
     * @method getAccessToken
     * @return string
     * @description Get the access token for the PayPal API.
     * @since 1.0.0
     */
    public function getAccessToken();

    /**
     * @method createPayment
     * @param array $paymentData Payment data for the payment provider.
     * @return mixed
     * @description Create a payment.
     * @since 1.0.0
     */
    public function createPayment(array $paymentData);

    /**
     * @method capturePayment
     * @param string $paymentId Payment ID for the payment provider.
     * @return mixed
     * @description Capture a payment.
     * @since 1.0.0
     */
    public function capturePayment($paymentId);

    /**
     * @method refundPayment
     * @param string $paymentId
     * @param array $refundData
     * @return mixed
     * @description Refund a payment.
     * @since 1.0.0
     */
    public function refundPayment($paymentId, array $refundData);

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @return mixed
     * @description Create a subscription.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData);

    /**
     * @method updateSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @param array $subscriptionData Subscription data for the payment provider.
     * @return mixed
     * @description Update a subscription.
     * @since 1.0.0
     */
    public function updateSubscription($subscriptionId, array $subscriptionData);

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @return mixed
     * @description Cancel a subscription.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId);

    /**
     * @method handleWebhook
     * @param array $webhookData Webhook data for the payment provider.
     * @return mixed
     * @description Handle a webhook.
     * @since 1.0.0
     */
    public function handleWebhook(array $webhookData);

    /**
     * @method getDispute
     * @param string $disputeId Dispute ID for the payment provider.
     * @return mixed
     * @description Get a dispute.
     * @since 1.0.0
     */
    public function getDispute($disputeId);

    /**
     * @method respondToDispute
     * @param string $disputeId Dispute ID for the payment provider.
     * @param array $response Response data for the payment provider.
     * @return mixed
     * @description Respond to a dispute.
     * @since 1.0.0
     */
    public function respondToDispute($disputeId, array $response);
}
