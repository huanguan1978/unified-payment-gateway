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
 * @class SubscriptionProvider
 * @package UnifiedPaymentGateway\PayPalApi
 * @description The subscription provider for the PayPal API.
 * @since 1.0.0
 */
class SubscriptionProvider
{
    /**
     * @var array
     * @description The configuration for the subscription provider.
     * @since 1.0.0
     */
    private $config;

    /**
     * @var string
     * @description The access token for the subscription provider.
     * @since 1.0.0
     */
    private $accessToken;

    /**
     * @method __construct
     * @param array $config Configuration for the subscription provider.
     * @param string $accessToken Access token for the subscription provider.
     * @description The constructor for the subscription provider.
     * @since 1.0.0
     */
    public function __construct(array $config, $accessToken)
    {
        $this->config = $config;
        $this->accessToken = $accessToken;
    }

    /**
     * @method createSubscription
     * @param array $subscriptionData Subscription data for the payment provider.
     * @return string JSON response from PayPal API
     * @throws \InvalidArgumentException If required fields are missing
     * @throws \RuntimeException If API call fails
     * @description Create a subscription with PayPal.
     * @since 1.0.0
     */
    public function createSubscription(array $subscriptionData)
    {
        // Validate required subscription data
        $requiredFields = ['plan_id', 'subscriber', 'application_context'];
        foreach ($requiredFields as $field) {
            if (!isset($subscriptionData[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        // Validate subscriber information
        if (!isset($subscriptionData['subscriber']['name']) || !isset($subscriptionData['subscriber']['email_address'])) {
            throw new \InvalidArgumentException("Subscriber must have name and email_address");
        }

        // Validate application context
        if (!isset($subscriptionData['application_context']['return_url']) || 
            !isset($subscriptionData['application_context']['cancel_url'])) {
            throw new \InvalidArgumentException("Application context must have return_url and cancel_url");
        }

        $subscriptionPayload = [
            'plan_id' => $subscriptionData['plan_id'],
            'subscriber' => [
                'name' => [
                    'given_name' => $subscriptionData['subscriber']['name']['given_name'] ?? '',
                    'surname' => $subscriptionData['subscriber']['name']['surname'] ?? ''
                ],
                'email_address' => $subscriptionData['subscriber']['email_address']
            ],
            'application_context' => [
                'return_url' => $subscriptionData['application_context']['return_url'],
                'cancel_url' => $subscriptionData['application_context']['cancel_url'],
                'brand_name' => $subscriptionData['application_context']['brand_name'] ?? '',
                'locale' => $subscriptionData['application_context']['locale'] ?? 'en-US',
                'shipping_preference' => $subscriptionData['application_context']['shipping_preference'] ?? 'NO_SHIPPING',
                'user_action' => $subscriptionData['application_context']['user_action'] ?? 'SUBSCRIBE_NOW',
                'payment_method' => [
                    'payer_selected' => $subscriptionData['application_context']['payment_method']['payer_selected'] ?? 'PAYPAL',
                    'payee_preferred' => $subscriptionData['application_context']['payment_method']['payee_preferred'] ?? 'IMMEDIATE_PAYMENT_REQUIRED'
                ]
            ]
        ];

        // Add optional start time if provided
        if (isset($subscriptionData['start_time'])) {
            $subscriptionPayload['start_time'] = $subscriptionData['start_time'];
        }

        // Add optional shipping address if provided
        if (isset($subscriptionData['shipping_address'])) {
            $subscriptionPayload['shipping_address'] = $subscriptionData['shipping_address'];
        }

        $ch = curl_init();

        $baseUrl = $this->config['sandbox'] ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
        curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v1/billing/subscriptions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($subscriptionPayload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \RuntimeException('PayPal API error: ' . $response);
        }

        return $response;
    }

    /**
     * @method updateSubscription
     * @param string $subscriptionId Subscription ID to update
     * @param array $subscriptionData Updated subscription data
     * @return string JSON response from PayPal API
     * @throws \RuntimeException If API call fails
     * @description Update an existing subscription with PayPal.
     * @since 1.0.0
     */
    public function updateSubscription($subscriptionId, array $subscriptionData)
    {
        $ch = curl_init();

        $baseUrl = $this->config['sandbox'] ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
        curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v1/billing/subscriptions/{$subscriptionId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($subscriptionData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \RuntimeException('PayPal API error: ' . $response);
        }

        return $response;
    }

    /**
     * @method cancelSubscription
     * @param string $subscriptionId Subscription ID to cancel
     * @param string $reason Reason for cancellation
     * @return string JSON response from PayPal API
     * @throws \RuntimeException If API call fails
     * @description Cancel an existing subscription with PayPal.
     * @since 1.0.0
     */
    public function cancelSubscription($subscriptionId, $reason = '')
    {
        $payload = ['reason' => $reason];

        $ch = curl_init();

        $baseUrl = $this->config['sandbox'] ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";
        curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v1/billing/subscriptions/{$subscriptionId}/cancel");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode >= 400) {
            throw new \RuntimeException('PayPal API error: ' . $response);
        }

        return $response;
    }
}
