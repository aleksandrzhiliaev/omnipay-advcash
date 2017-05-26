<?php

namespace Omnipay\Advcash\Message;

use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     *
     * @var PurchaseRequest
     *
     */
    private $request;

    protected function setUp()
    {
        $httpClient = $this->getHttpClient();

        $this->request = new RefundRequest($httpClient, $this->getHttpRequest());
        $this->request->setPayeeAccount('PayeeAccount');
        $this->request->setAmount('10.00');
        $this->request->setDescription('Description');
        $this->request->setApiName('ApiName');
        $this->request->setApiSecret('ApiSecret');
        $this->request->setAccount('Account');
        $this->request->setCurrency('Currency');
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $expectedData = [
            'apiName' => 'ApiName',
            'apiSecret' => 'ApiSecret',
            'accountEmail' => 'Account',
            'amount' => '10.00',
            'currency' => 'CURRENCY',
            'payeeEmail' => 'PayeeAccount',
            'note' => 'Description',
        ];
        $this->assertEquals($expectedData, $data);
    }

}