<?php
/**
 * @package UnifiedPaymentGateway\StripeApi\Contracts
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 Siddik Web
 * @license MIT
 * @link https://github.com/siddik-web/unified-payment-gateway
 * @version 1.0.0
 * @since 1.0.0
 */

namespace UnifiedPaymentGateway\StripeApi\Contracts;

/**
 * @interface StripeWebhookEventHandlerInterface
 * @package UnifiedPaymentGateway\StripeApi\Contracts
 * @description The interface for the Stripe webhook event handler.
 * @since 1.0.0
 */
interface StripeWebhookEventHandlerInterface
{
    /**
     * @method processWebhookEvent
     * @param array $webhookData Webhook data for the Stripe API.
     * @description Process the webhook event.
     * @since 1.0.0
     */
    public function processWebhookEvent(array $webhookData);
}