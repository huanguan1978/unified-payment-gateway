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
 * @class TokenProvider
 * @package UnifiedPaymentGateway\PayPalApi
 * @description The token provider for the PayPal API.
 * @since 1.0.0
 */
class TokenProvider
{
    /**
     * @var array
     * @description The configuration for the token provider.
     * @since 1.0.0
     */
    private $config;

    /**
     * @var string|null
     * @description Cached access token
     * @since 1.0.0
     */
    private $accessToken = null;

    /**
     * @var int|null
     * @description Token expiration timestamp
     * @since 1.0.0
     */
    private $tokenExpiration = null;

    /**
     * @method __construct
     * @param array $config Configuration for the token provider.
     * @description The constructor for the token provider.
     * @since 1.0.0
     */
    public function __construct(array $config)
    {
        if (!isset($config['client_id']) || !isset($config['client_secret'])) {
            throw new \InvalidArgumentException('PayPal client_id and client_secret are required');
        }
        $this->config = $config;
    }

    /**
     * @method getAccessToken
     * @return string
     * @throws \RuntimeException If token request fails
     * @description Get the access token for the PayPal API.
     * @since 1.0.0
     */
    public function getAccessToken()
    {
        // Return cached token if still valid
        if ($this->accessToken && $this->tokenExpiration > time()) {
            return $this->accessToken;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_USERPWD, $this->config['client_id'] . ':' . $this->config['client_secret']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Language: en_US'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new \RuntimeException('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException('Failed to get PayPal access token. Response: ' . $response);
        }

        $tokenData = json_decode($response, true);
        
        if (!isset($tokenData['access_token']) || !isset($tokenData['expires_in'])) {
            throw new \RuntimeException('Invalid token response from PayPal');
        }

        // Cache the token and expiration
        $this->accessToken = $tokenData['access_token'];
        $this->tokenExpiration = time() + $tokenData['expires_in'] - 60; // Subtract 60 seconds for safety margin

        return $this->accessToken;
    }
}
