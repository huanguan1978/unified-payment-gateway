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
 * @class PaymentProvider
 * @package UnifiedPaymentGateway\PayPalApi
 * @description The payment provider for the PayPal API.
 * @since 1.0.0
 */
class PaymentProvider
{
    /**
     * @var array
     * @description The configuration for the payment provider.
     * @since 1.0.0
     */
    private $config;

    /**
     * @var string
     * @description The access token for the payment provider.
     * @since 1.0.0
     */
    private $accessToken;

    /**
     * @method __construct
     * @param array $config Configuration for the payment provider.
     * @param string $accessToken Access token for the payment provider.
     * @description The constructor for the payment provider.
     * @since 1.0.0
     */
    public function __construct(array $config, $accessToken)
    {
        $this->config = $config;
        $this->accessToken = $accessToken;
    }

    /**
     * @method createPayment
     * @param array $paymentData Payment data for the payment provider.
     * @return string JSON response from PayPal API
     * @description Create a payment with PayPal.
     * @since 1.0.0
     */
    public function createPayment(array $paymentData)
    {
        // Validate required payment data
        $requiredFields = ['amount', 'currency', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field])) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
            }
        }

        // Validate amount is numeric and positive
        if (!is_numeric($paymentData['amount']) || $paymentData['amount'] <= 0) {
            throw new \InvalidArgumentException("Amount must be a positive number");
        }

        // Get return and cancel URLs from config or use defaults
        $returnUrl = $this->config['return_url'] ?? "https://example.com/return";
        $cancelUrl = $this->config['cancel_url'] ?? "https://example.com/cancel";

        $paymentPayload = [
            "intent" => "sale",
            "redirect_urls" => [
                "return_url" => $returnUrl,
                "cancel_url" => $cancelUrl
            ],
            "payer" => [
                "payment_method" => "paypal"
            ],
            "transactions" => [
                [
                    "amount" => [
                        "total" => number_format($paymentData['amount'] / 100, 2, '.', ''), // Convert cents to dollars with proper formatting
                        "currency" => strtoupper($paymentData['currency'])
                    ],
                    "description" => substr($paymentData['description'], 0, 127), // PayPal has a 127 char limit
                    "item_list" => [
                        "items" => isset($paymentData['items']) ? $this->formatLineItems($paymentData['items']) : []
                    ]
                ]
            ]
        ];

        // Add optional shipping address if provided
        if (isset($paymentData['shipping_address'])) {
            $paymentPayload['transactions'][0]['item_list']['shipping_address'] = $paymentData['shipping_address'];
        }

        // Add optional invoice number if provided
        if (isset($paymentData['invoice_number'])) {
            $paymentPayload['transactions'][0]['invoice_number'] = $paymentData['invoice_number'];
        }

        $ch = curl_init();

        // Use sandbox URL if configured
        $url = $this->config['sandbox'] ? "https://api.sandbox.paypal.com/v1/payments/payment" : "https://api.paypal.com/v1/payments/payment";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentPayload));
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
     * @method formatLineItems
     * @param array $items Array of item details
     * @return array Formatted items for PayPal API
     * @description Format line items for PayPal API request
     * @since 1.0.0
     */
    private function formatLineItems(array $items)
    {
        return array_map(function($item) {
            return [
                'name' => substr($item['name'] ?? '', 0, 127),
                'quantity' => $item['quantity'] ?? 1,
                'price' => number_format(($item['price'] ?? 0) / 100, 2, '.', ''),
                'currency' => strtoupper($item['currency'] ?? 'USD'),
                'sku' => $item['sku'] ?? null
            ];
        }, $items);
    }
}
