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

use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnifiedPaymentGateway\StripeApi\Contracts\StripeWebhookEventHandlerInterface;

/**
 * @class StripeWebhookHandler
 * @package UnifiedPaymentGateway\StripeApi
 * @description The webhook handler for the Stripe API.
 * @since 1.0.0
 */
class StripeWebhookHandler
{
    /**
     * @var array
     * @description The configuration for the webhook handler.
     * @since 1.0.0
     */
    private $config;

    /**
     * @var StripeWebhookEventHandlerInterface
     * @description The event handler for Stripe webhook events.
     * @since 1.0.0
     */
    private $eventHandler;

    /**
     * @method __construct
     * @param array $config Configuration for the webhook handler.
     * @param StripeWebhookEventHandlerInterface $eventHandler The event handler for Stripe webhook events.
     * @throws \InvalidArgumentException If webhook secret is missing from config
     * @description The constructor for the webhook handler.
     * @since 1.0.0
     */
    public function __construct(array $config, StripeWebhookEventHandlerInterface $eventHandler)
    {
        if (!isset($config['stripe_webhook_secret'])) {
            throw new \InvalidArgumentException('Stripe webhook secret is required in configuration');
        }
        $this->config = $config;
        $this->eventHandler = $eventHandler;
    }

    /**
     * @method handleWebhook
     * @param string $payload Raw webhook payload
     * @param string $signatureHeader Stripe signature header
     * @return array Response with status code and message
     * @description Handle a webhook event with Stripe.
     * @since 1.0.0
     */
    public function handleWebhook(string $payload, string $signatureHeader)
    {
        try {
            // Verify and construct the webhook event
            $event = $this->verifyWebhookSignature($payload, $signatureHeader);
            
            // Process the webhook event using the event handler
            $this->eventHandler->processWebhookEvent($event->toArray());

            return [
                'statusCode' => 200,
                'message' => 'Webhook processed successfully'
            ];

        } catch (SignatureVerificationException $e) {
            // Handle signature verification error
            error_log("Stripe webhook signature verification failed: " . $e->getMessage());
            return [
                'statusCode' => 400,
                'message' => 'Webhook signature verification failed'
            ];

        } catch (\Exception $e) {
            // Handle other exceptions
            error_log("Error processing Stripe webhook: " . $e->getMessage());
            return [
                'statusCode' => 500,
                'message' => 'Error processing webhook'
            ];
        }
    }

    /**
     * @method verifyWebhookSignature
     * @param string $payload Raw webhook payload
     * @param string $signatureHeader Stripe signature header
     * @return \Stripe\Event Verified Stripe event
     * @throws SignatureVerificationException If signature verification fails
     * @description Verify the webhook signature with Stripe.
     * @since 1.0.0
     */
    private function verifyWebhookSignature(string $payload, string $signatureHeader)
    {
        return Webhook::constructEvent(
            $payload,
            $signatureHeader,
            $this->config['stripe_webhook_secret']
        );
    }
}