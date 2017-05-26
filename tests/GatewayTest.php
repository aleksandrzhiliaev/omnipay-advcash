<?php

namespace Omnipay\Advcash;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'amount' => '0.1',
            'currency' => 'RUR',
            'transactionId' => 123,
            'description' => 'Order: 123',
            'cancelUrl' => 'https://url.com/cancel',
            'returnUrl' => 'https://url.com/return',
            'notifyUrl' => 'https://url.com/notify',
        ]);
        $this->assertInstanceOf('\Omnipay\Advcash\Message\PurchaseRequest', $request);
        $this->assertSame('0.10', $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf('\Omnipay\Advcash\Message\CompletePurchaseRequest', $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund([
            'payeeAccount' => 'U04174047283211',
            'amount' => 0.1,
            'description' => 'Testing nixmoney',
            'currency' => 'USD',
        ]);
        $this->assertInstanceOf('\Omnipay\Advcash\Message\RefundRequest', $request);
        $this->assertSame('U04174047283211', $request->getPayeeAccount());
    }
}