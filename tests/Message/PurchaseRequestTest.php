<?php

namespace Omnipay\Advcash\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     *
     * @var PurchaseRequest
     *
     */
    private $request;

    protected function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setAccount('Account');
        $this->request->setAccountName('AccountName');
        $this->request->setCurrency('Currency');
        $this->request->setAmount('10.00');
        $this->request->setReturnUrl('ReturnUrl');
        $this->request->setCancelUrl('CancelUrl');
        $this->request->setNotifyUrl('NotifyUrl');
        $this->request->setTransactionId(1);
        $this->request->setDescription('Description');
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $sign = hash('sha256', $this->request->getAccount() . ":" . $this->request->getAccountName() . ":" . $this->request->getAmount() . ":" . $this->request->getCurrency() . ":" . $this->request->getSecret() . ":" . $this->request->getTransactionId());

        $expectedData = [
            'ac_account_email' => 'Account',
            'ac_sci_name' => 'AccountName',
            'ac_amount' => '10.00',
            'ac_currency' => 'CURRENCY',
            'ac_order_id' => 1,
            'ac_sign' => $sign,
            'ac_comments' => 'Description'
        ];
        $this->assertEquals($expectedData, $data);
    }

    public function testSendSuccess()
    {
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://wallet.advcash.com/sci/', $response->getRedirectUrl());
        $this->assertEquals(true, $response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
    }
}