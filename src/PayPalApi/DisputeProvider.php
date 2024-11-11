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
 * @class DisputeProvider
 * @package UnifiedPaymentGateway\PayPalApi
 * @description The dispute provider for the PayPal API.
 * @since 1.0.0
 */
class DisputeProvider
{
    /**
     * @var array
     * @description The configuration for the dispute provider.
     * @since 1.0.0
     */
    private $config;

    /**
     * @var string
     * @description The access token for the dispute provider.
     * @since 1.0.0
     */
    private $accessToken;

    /**
     * @method __construct
     * @param array $config Configuration for the dispute provider.
     * @param string $accessToken Access token for the dispute provider.
     * @description The constructor for the dispute provider.
     * @since 1.0.0
     */
    public function __construct(array $config, $accessToken)
    {
        $this->config = $config;
        $this->accessToken = $accessToken;
    }

    /**
     * @method listDisputes
     * @return string JSON response containing list of disputes
     * @description Get list of disputes from PayPal.
     * @since 1.0.0
     */
    public function listDisputes()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/customer/disputes");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method getDispute
     * @param string $disputeId The ID of the dispute to retrieve
     * @return string JSON response containing dispute details
     * @description Get details of a specific dispute.
     * @since 1.0.0
     */
    public function getDispute($disputeId)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/customer/disputes/{$disputeId}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method acceptClaim
     * @param string $disputeId The ID of the dispute to accept
     * @return string JSON response containing the result
     * @description Accept a dispute claim.
     * @since 1.0.0
     */
    public function acceptClaim($disputeId)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/customer/disputes/{$disputeId}/accept-claim");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @method provideSupportingInfo
     * @param string $disputeId The ID of the dispute
     * @param array $evidence The evidence data to submit
     * @return string JSON response containing the result
     * @description Provide supporting information for a dispute.
     * @since 1.0.0
     */
    public function provideSupportingInfo($disputeId, array $evidence)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/customer/disputes/{$disputeId}/provide-supporting-info");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($evidence));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->accessToken}"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
