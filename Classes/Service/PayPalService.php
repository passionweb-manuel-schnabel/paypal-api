<?php

namespace Passionweb\PayPalApi\Service;

use Psr\Log\LoggerInterface;

class PayPalService
{
    protected LoggerInterface $logger;
    protected array $extConf;
    protected string $payPalMode;
    protected string $payPalUrl;
    protected string $payPalClientId;
    protected string $payPalClientSecret;
    protected string $payPalAccesToken;

    public function __construct(
        LoggerInterface $logger,
        array $extConf
    ){
        $this->logger = $logger;
        $this->extConf = $extConf;

        $this->payPalMode = $this->extConf['payPalMode'];
        $this->payPalUrl = $this->extConf['payPalUrl'];
        $this->payPalClientSecret = $this->payPalMode === 'live' ? $this->extConf['payPalLiveClientSecret'] : $this->extConf['payPalSandboxClientSecret'];
        $this->payPalClientId = $this->payPalMode === 'live' ? $this->extConf['payPalLiveClientId'] : $this->extConf['payPalSandboxClientId'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->payPalUrl . '/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, $this->payPalClientId . ':' . $this->payPalClientSecret);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        $this->payPalAccesToken = json_decode($result)->access_token;
        curl_close($ch);
    }

    public function createPayment(string $buyer, float $price, string $redirectUrl) {
        $paymentDetails  = [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal'
            ],
            'transactions' => [
                [
                    'amount' => [
                        'total' => (string) $price,
                        'currency' => 'EUR'
                    ],
                    'item_list' => [
                        'items' => [
                            [
                                'name' => 'Sample payment from ' . $buyer,
                                'currency' => 'EUR',
                                'quantity' => '1',
                                'sku' => '123123',
                                'price' => (string) $price
                            ]
                        ],
                        'shipping_address' => [
                            'recipient_name' => $buyer,
                            'line1' => 'Teststrasse 1',
                            'city' => 'Testhausen',
                            'state' => 'Testland',
                            'postal_code' => '12345',
                            'country_code' => 'DE'
                        ]
                    ],
                    'description' => 'Sample payment description',
                    'invoice_number' => uniqid(),
                ]
            ],
            'note_to_payer' => 'Contact us for any questions on your order.',
            'redirect_urls' => [
                'return_url' => $redirectUrl . "?success=true&custom_param=12345678",
                'cancel_url' => $redirectUrl . "?success=false"
            ],
        ];

        try {
            $ch = curl_init();
            $postData = json_encode($paymentDetails);
            curl_setopt($ch, CURLOPT_URL, $this->payPalUrl . '/payments/payment');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            $headers = array();
            $headers[] = 'Authorization: Bearer ' . $this->payPalAccesToken;
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $paymentResponse = json_decode($result, true);

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            if (is_array($paymentResponse['links'])) {
                foreach ($paymentResponse['links'] as $link) {
                    if ($link['rel'] == 'approval_url') {
                        return $link['href'];
                    }
                }
            }
            throw new \Exception('Could not find approval_url in response');
        } catch (\Exception $e) {
            $this->logger->error("Paypal payment creation failed!", array("Errormessage" => $e->getMessage()));
            return "";
        }
    }
}
