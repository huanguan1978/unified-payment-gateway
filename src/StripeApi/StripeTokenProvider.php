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
 * @class StripeTokenProvider
 * @package UnifiedPaymentGateway\StripeApi
 * @description The token provider for the Stripe API.
 * @since 1.0.0
 */
class StripeTokenProvider
{
    /**
     * @var array
     * @description The configuration for the token provider.
     * @since 1.0.0
     */
    private $config;

    /**
     * @method __construct
     * @param array $config Configuration for the token provider.
     * @description The constructor for the token provider.
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @method getAccessToken
     * @return string
     * @description Get the access token for the Stripe API.
     * @since 1.0.0
     */
    public function getAccessToken()
    {
        // Check if sandbox mode is enabled
        if (!empty($this->config['sandbox']) && $this->config['sandbox'] === true) {
            // Return the sandbox API key
            return $this->config['sandbox_api_key'];
        }
        
        // Stripe typically uses API keys instead of OAuth tokens
        return $this->config['api_key'];
    }
}
