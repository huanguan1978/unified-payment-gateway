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

use Stripe\Subscription;
use Stripe\StripeClient;

/**
 * @class StripeSubscriptionProvider
 * @package UnifiedPaymentGateway\StripeApi
 * @description The subscription provider for the Stripe API.
 * @since 1.0.0
 */
class StripeSubscriptionProvider
{
    /**
     * @var StripeClient
     * @description The Stripe client for the subscription provider.
     * @since 1.0.0
     */
    private $stripeClient;

    /**
     * @method __construct
     * @param string $accessToken Access token for the subscription provider.
     * @description The constructor for the subscription provider.
     * @since 1.0.0
     */
    public function __construct($accessToken)
    {
        // Initialize Stripe SDK with the access token
        $this->stripeClient = new StripeClient($accessToken);
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the Stripe API.
     * @description Create a subscription with Stripe.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        return $this->stripeClient->subscriptions->create($subscriptionData);
    }

    /**
     * @method updateSubscription
     * @param string $subscriptionId Subscription ID for the Stripe API.
     * @param array $subscriptionData Subscription data for the Stripe API.
     * @description Update a subscription with Stripe.
     * @since 1.0.0
     */
    public function updateSubscription($subscriptionId, array $subscriptionData)
    {
        return $this->stripeClient->subscriptions->update($subscriptionId, $subscriptionData);
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID for the Stripe API.
     * @description Cancel a subscription with Stripe.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId)
    {
        return $this->stripeClient->subscriptions->cancel($subscriptionId);
    }
}