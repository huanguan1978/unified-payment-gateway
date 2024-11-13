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
 * @class WebhookHandler
 * @package UnifiedPaymentGateway\PayPalApi
 * @description Handles PayPal webhook notifications
 * @since 1.0.0
 */
class WebhookHandler
{
    /**
     * @var array
     * @description The configuration for the webhook handler
     * @since 1.0.0
     */
    private $config;

    /**
     * @var string
     * @description The access token for PayPal API
     * @since 1.0.0
     */
    private $accessToken;

    /**
     * @method __construct
     * @param array $config Configuration for the webhook handler
     * @param string $accessToken Access token for PayPal API
     * @description Constructor for the webhook handler
     * @since 1.0.0
     */
    public function __construct(array $config, $accessToken)
    {
        $this->config = $config;
        $this->accessToken = $accessToken;
    }

    /**
     * @method handleWebhook
     * @param string $payload Raw webhook payload
     * @param array $headers Request headers
     * @return array Processed webhook data
     * @throws \RuntimeException If webhook validation fails
     * @description Handle incoming webhook notification from PayPal
     * @since 1.0.0
     */
    public function handleWebhook($payload, array $headers)
    {
        // Verify webhook signature
        $this->verifyWebhookSignature($payload, $headers);

        // Parse webhook payload
        $data = json_decode($payload, true);
        if (!$data) {
            throw new \RuntimeException('Invalid webhook payload');
        }

        // Process based on event type
        switch ($data['event_type']) {
            case 'PAYMENT.SALE.COMPLETED':
                return $this->handlePaymentCompleted($data);

            case 'PAYMENT.SALE.DENIED':
                return $this->handlePaymentDenied($data);

            case 'PAYMENT.SALE.REFUNDED':
                return $this->handlePaymentRefunded($data);

            case 'BILLING.SUBSCRIPTION.CREATED':
                return $this->handleSubscriptionCreated($data);

            case 'BILLING.SUBSCRIPTION.CANCELLED':
                return $this->handleSubscriptionCancelled($data);

            case 'BILLING.SUBSCRIPTION.SUSPENDED':
                return $this->handleSubscriptionSuspended($data);

            default:
                return [
                    'status' => 'unhandled',
                    'message' => 'Unhandled webhook event type: ' . $data['event_type']
                ];
        }
    }

    /**
     * @method verifyWebhookSignature
     * @param string $payload Raw webhook payload
     * @param array $headers Request headers
     * @throws \RuntimeException If signature verification fails
     * @description Verify the authenticity of the webhook notification
     * @since 1.0.0
     */
    private function verifyWebhookSignature($payload, array $headers)
    {
        $webhookId = $this->config['webhook_id'] ?? null;
        if (!$webhookId) {
            throw new \RuntimeException('Webhook ID not configured');
        }

        $signatureVerification = [
            'auth_algo' => $headers['PAYPAL-AUTH-ALGO'] ?? '',
            'cert_url' => $headers['PAYPAL-CERT-URL'] ?? '',
            'transmission_id' => $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
            'transmission_sig' => $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
            'transmission_time' => $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
            'webhook_id' => $webhookId,
            'webhook_event' => $payload
        ];

        $ch = curl_init();
        $url = $this->config['sandbox_mode'] ?? false 
            ? 'https://api.sandbox.paypal.com/v1/notifications/verify-webhook-signature' 
            : 'https://api.paypal.com/v1/notifications/verify-webhook-signature';
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($signatureVerification));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->accessToken
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException('Failed to verify webhook signature');
        }

        $verification = json_decode($response, true);
        if (!$verification || $verification['verification_status'] !== 'SUCCESS') {
            throw new \RuntimeException('Invalid webhook signature');
        }
    }

    /**
     * @method handlePaymentCompleted
     * @param array $data Webhook event data
     * @return array Processed payment data
     * @description Handle payment completed webhook event
     * @since 1.0.0
     */
    private function handlePaymentCompleted(array $data)
    {
        return [
            'status' => 'completed',
            'transaction_id' => $data['resource']['id'],
            'amount' => $data['resource']['amount']['total'],
            'currency' => $data['resource']['amount']['currency']
        ];
    }

    /**
     * @method handlePaymentDenied
     * @param array $data Webhook event data
     * @return array Processed payment data
     * @description Handle payment denied webhook event
     * @since 1.0.0
     */
    private function handlePaymentDenied(array $data)
    {
        return [
            'status' => 'denied',
            'transaction_id' => $data['resource']['id'],
            'reason' => $data['resource']['state_reason'] ?? 'Unknown'
        ];
    }

    /**
     * @method handlePaymentRefunded
     * @param array $data Webhook event data
     * @return array Processed refund data
     * @description Handle payment refunded webhook event
     * @since 1.0.0
     */
    private function handlePaymentRefunded(array $data)
    {
        return [
            'status' => 'refunded',
            'transaction_id' => $data['resource']['id'],
            'refund_id' => $data['resource']['refund_id'],
            'amount' => $data['resource']['amount']['total'],
            'currency' => $data['resource']['amount']['currency']
        ];
    }

    /**
     * @method handleSubscriptionCreated
     * @param array $data Webhook event data
     * @return array Processed subscription data
     * @description Handle subscription created webhook event
     * @since 1.0.0
     */
    private function handleSubscriptionCreated(array $data)
    {
        return [
            'status' => 'created',
            'subscription_id' => $data['resource']['id'],
            'plan_id' => $data['resource']['plan_id'],
            'start_time' => $data['resource']['start_time']
        ];
    }

    /**
     * @method handleSubscriptionCancelled
     * @param array $data Webhook event data
     * @return array Processed subscription data
     * @description Handle subscription cancelled webhook event
     * @since 1.0.0
     */
    private function handleSubscriptionCancelled(array $data)
    {
        return [
            'status' => 'cancelled',
            'subscription_id' => $data['resource']['id'],
            'cancel_time' => $data['resource']['status_update_time']
        ];
    }

    /**
     * @method handleSubscriptionSuspended
     * @param array $data Webhook event data
     * @return array Processed subscription data
     * @description Handle subscription suspended webhook event
     * @since 1.0.0
     */
    private function handleSubscriptionSuspended(array $data)
    {
        return [
            'status' => 'suspended',
            'subscription_id' => $data['resource']['id'],
            'suspend_time' => $data['resource']['status_update_time']
        ];
    }
}
