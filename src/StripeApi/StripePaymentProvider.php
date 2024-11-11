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

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

/**
 * @class StripePaymentProvider
 * @package UnifiedPaymentGateway\StripeApi
 * @description The payment provider for the Stripe API.
 * @since 1.0.0
 */
class StripePaymentProvider
{
    /**
     * @var StripeClient
     * @description The Stripe client for the payment provider.
     * @since 1.0.0
     */
    private $stripeClient;

    /**
     * @method __construct
     * @param string $accessToken Access token for the payment provider.
     * @description The constructor for the payment provider.
     * @since 1.0.0
     */
    public function __construct($accessToken)
    {
        // Set up Stripe with the provided access token
        $this->stripeClient = new StripeClient($accessToken);
    }

    /**
     * @method createPaymentIntent
     * @param array $paymentData Payment data for the Stripe API.
     * @return mixed
     * @description Create a payment intent with Stripe.
     * @since 1.0.0
     * @throws \Exception
     */
    public function createPaymentIntent(array $paymentData)
    {
        try {
            return $this->stripeClient->paymentIntents->create($paymentData);
        } catch (ApiErrorException $e) {
            throw new \Exception("Error creating payment intent: " . $e->getMessage());
        }
    }

    /**
     * @method capturePaymentIntent
     * @param string $paymentId Payment ID for the Stripe API.
     * @return mixed
     * @description Capture a payment intent with Stripe.
     * @since 1.0.0
     * @throws \Exception
     */
    public function capturePaymentIntent($paymentId)
    {
        try {
            $paymentIntent = $this->stripeClient->paymentIntents->retrieve($paymentId);
            return $paymentIntent->capture();
        } catch (ApiErrorException $e) {
            throw new \Exception("Error capturing payment intent: " . $e->getMessage());
        }
    }

    /**
     * @method refundPayment
     * @param string $paymentId Payment ID for the Stripe API.
     * @param array $refundData Refund data for the Stripe API.
     * @return mixed
     * @description Refund a payment with Stripe.
     * @since 1.0.0
     * @throws \Exception
     */
    public function refundPayment($paymentId, array $refundData)
    {
        try {
            return $this->stripeClient->refunds->create([
                'payment_intent' => $paymentId,
                'amount' => $refundData['amount'] ?? null,
                'reason' => $refundData['reason'] ?? null,
                'metadata' => $refundData['metadata'] ?? []
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception("Error processing refund: " . $e->getMessage());
        }
    }
}
