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
 * @class StripeClient
 * @package UnifiedPaymentGateway\StripeApi
 * @implements StripeApiInterface
 * @description The Stripe client for the Unified Payment Gateway.
 * @since 1.0.0
 */
class StripeClient implements StripeApiInterface
{
    /**
     * @var StripeTokenProvider
     * @description The token provider for the Stripe API.
     * @since 1.0.0
     */
    private $tokenProvider;

    /**
     * @var StripePaymentProvider
     * @description The payment provider for the Stripe API.
     * @since 1.0.0
     */
    private $paymentProvider;

    /**
     * @var StripeSubscriptionProvider
     * @description The subscription provider for the Stripe API.
     * @since 1.0.0
     */
    private $subscriptionProvider;

    /**
     * @var StripeWebhookHandler
     * @description The webhook handler for the Stripe API.
     * @since 1.0.0
     */
    private $webhookHandler;

    /**
     * @method __construct
     * @param array $config Configuration for the Stripe client.
     * @description The constructor for the Stripe client.
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        // Initialize the token provider
        $this->tokenProvider = new StripeTokenProvider($config);

        // Initialize the payment provider
        $this->paymentProvider = new StripePaymentProvider($this->tokenProvider->getAccessToken());

        // Initialize the subscription provider
        $this->subscriptionProvider = new StripeSubscriptionProvider($this->tokenProvider->getAccessToken());

        // Initialize the webhook handler
        $this->webhookHandler = new StripeWebhookHandler($config);
    }

    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the Stripe API.
     * @return mixed
     * @description Create a payment intent with Stripe.
     * @since 1.0.0
     */
    public function createPaymentIntent(array $paymentData)
    {
        return $this->paymentProvider->createPaymentIntent($paymentData);
    }

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the Stripe API.
     * @return mixed
     * @description Capture a payment intent with Stripe.
     * @since 1.0.0
     */
    public function capturePaymentIntent($paymentId)
    {
        return $this->paymentProvider->capturePaymentIntent($paymentId);
    }

    /**
     * @method refundPayment
     * @param string $paymentId Payment ID for the Stripe API.
     * @param array $refundData Refund data for the Stripe API.
     * @return mixed
     * @description Refund a payment with Stripe.
     * @since 1.0.0
     */
    public function refundPayment($paymentId, array $refundData)
    {
        return $this->paymentProvider->refundPayment($paymentId, $refundData);
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the Stripe API.
     * @return mixed
     * @description Create a subscription with Stripe.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        return $this->subscriptionProvider->createSubscription($subscriptionData);
    }

    /**
     * @method updateSubscription
     * @param string $subscriptionId Subscription ID for the Stripe API.
     * @param array $subscriptionData Subscription data for the Stripe API.
     * @return mixed
     * @description Update a subscription with Stripe.
     * @since 1.0.0
     */
    public function updateSubscription($subscriptionId, array $subscriptionData)
    {
        return $this->subscriptionProvider->updateSubscription($subscriptionId, $subscriptionData);
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the Stripe API.
     * @return mixed
     * @description Cancel a subscription with Stripe.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->subscriptionProvider->cancelSubscription($subscriptionId);
    }

    /**
     * @method handleWebhook
     * @param array $webhookData Webhook data for the Stripe API.
     * @return mixed
     * @description Handle a webhook with Stripe.
     * @since 1.0.0
     */
    public function handleWebhook(array $webhookData)
    {
        return $this->webhookHandler->handleWebhook($webhookData);
    }
}
