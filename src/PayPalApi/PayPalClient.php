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
 * @class PayPalClient
 * @package UnifiedPaymentGateway\PayPalApi
 * @implements PayPalApiInterface
 * @description The PayPal client for the Unified Payment Gateway.
 * @link https://developer.paypal.com/api/rest/
 * @link https://developer.paypal.com/docs/api/webhooks/
 * @link https://developer.paypal.com/docs/api/disputes/
 * @link https://developer.paypal.com/docs/subscriptions/
 * @link https://developer.paypal.com/docs/api/payments/
 * @link https://developer.paypal.com/docs/api/oauth-api/
 * @link https://developer.paypal.com/docs/api/vault/
 * @link https://developer.paypal.com/docs/api/partner-referrals/
 * @link https://developer.paypal.com/docs/api/merchant-services/
 * @link https://developer.paypal.com/docs/api/invoicing/
 * @link https://developer.paypal.com/docs/api/merchant-marketing/
 * @link https://developer.paypal.com/docs/api/customer-disputes/
 * @link https://developer.paypal.com/docs/api/merchant-notifications/
 * @link https://developer.paypal.com/docs/api/merchant-risk-management/
 * @since 1.0.0
 */
class PayPalClient implements PayPalApiInterface
{
    /**
     * @var TokenProvider
     * @description The token provider for the PayPal API.
     * @since 1.0.0
     */
    private $tokenProvider;

    /**
     * @var PaymentProvider
     * @description The payment provider for the PayPal API.
     * @since 1.0.0
     */
    private $paymentProvider;

    /**
     * @var SubscriptionProvider
     * @description The subscription provider for the PayPal API.
    * @since 1.0.0
     */
    private $subscriptionProvider;

    /**
     * @var WebhookHandler
     * @description The webhook handler for the PayPal API.
     * @since 1.0.0
     */
    private $webhookHandler;

    /**
     * @var DisputeProvider
     * @description The dispute provider for the PayPal API.
     * @since 1.0.0
     */
    private $disputeProvider;

    /**
     * @method __construct
     * @param array $config Configuration for the PayPal client.
     * @description The constructor for the PayPal client.
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        // Initialize the token provider.   
        $this->tokenProvider = new TokenProvider($config);
        $accessToken = $this->tokenProvider->getAccessToken();

        // Initialize the payment provider.
        $this->paymentProvider = new PaymentProvider($config, $accessToken);

        // Initialize the subscription provider.
        $this->subscriptionProvider = new SubscriptionProvider($config, $accessToken);

        // Initialize the webhook handler.
        $this->webhookHandler = new WebhookHandler($config);

        // Initialize the dispute provider.
        $this->disputeProvider = new DisputeProvider($config, $accessToken);
    }

    /**
     * @method getAccessToken
     * @return string
     * @description Get the access token for the PayPal API.
     * @since 1.0.0
     */
    public function getAccessToken()
    {
        return $this->tokenProvider->getAccessToken();
    }

    /**
     * @method createPayment
     * @param array $paymentData Payment data for the payment provider.
     * @return mixed
     * @description Create a payment.
     * @since 1.0.0
     */
    public function createPayment(array $paymentData)
    {
        return $this->paymentProvider->createPayment($paymentData);
    }

    /**
     * @method capturePayment
     * @param string $paymentId
     * @return mixed
     * @description Capture a payment.
     * @since 1.0.0
     */
    public function capturePayment($paymentId)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/payment/{$paymentId}/capture");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->getAccessToken()
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method refundPayment
     * @param string $paymentId Payment ID for the payment provider.
     * @param array $refundData Refund data for the payment provider.
     * @return mixed
     * @description Refund a payment.
     * @since 1.0.0
     */
    public function refundPayment($paymentId, array $refundData)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/sale/{$paymentId}/refund");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($refundData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->getAccessToken()
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @return mixed
     * @description Create a subscription.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        return $this->subscriptionProvider->createSubscription($subscriptionData);
    }

    /**
     * @method updateSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @param array $subscriptionData Subscription data for the payment provider.
     * @return mixed
     * @description Update a subscription.
     * @since 1.0.0
     */
    public function updateSubscription($subscriptionId, array $subscriptionData)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/billing/subscriptions/{$subscriptionId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($subscriptionData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->getAccessToken()
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the payment provider.
     * @return mixed
     * @description Cancel a subscription.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/billing/subscriptions/{$subscriptionId}/cancel");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->getAccessToken()
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method handleWebhook
     * @param array $webhookData Webhook data for the payment provider.
     * @return mixed
     * @description Handle a webhook.
     * @since 1.0.0
     */
    public function handleWebhook(array $webhookData)
    {
        return $this->webhookHandler->handleWebhook($webhookData);
    }

    /**
     * @method getDispute
     * @param string $disputeId Dispute ID for the payment provider.
     * @return mixed
     * @description Get a dispute.
     * @since 1.0.0
     */
    public function getDispute($disputeId)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/customer/disputes/{$disputeId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->getAccessToken()
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method respondToDispute
     * @param string $disputeId Dispute ID for the payment provider.
     * @param array $response Response data for the payment provider.
     * @return mixed
     * @description Respond to a dispute.
     * @since 1.0.0
     */
    public function respondToDispute($disputeId, array $response)
    {
        return $this->disputeProvider->provideSupportingInfo($disputeId, $response);
    }
}
