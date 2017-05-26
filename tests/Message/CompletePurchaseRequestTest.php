<?php

namespace Omnipay\Advcash\Message;

use Mockery as m;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private $request;

    protected function setUp()
    {
        $arguments = [$this->getHttpClient(), $this->getHttpRequest()];
        $this->request = m::mock('Omnipay\Advcash\Message\CompletePurchaseRequest[getEndpoint]', $arguments);
        $this->request->setAccount('Account');
        $this->request->setAccountName('AccountName');
        $this->request->setSecret('Secret');
        $this->request->setCurrency('Currency');
        $this->request->setAmount('10.00');
        $this->request->setReturnUrl('ReturnUrl');
        $this->request->setCancelUrl('CancelUrl');
        $this->request->setNotifyUrl('NotifyUrl');
        $this->request->setTransactionId(1);
    }

    public function testCreateResponseHash()
    {
        $parameters = [
            'ac_transfer' => '0d7285b0-feab-45a4-8b6c-7aaadaeb4ffc',
            'ac_start_date' => 'R904800934646',
            'ac_sci_name' => 'Name',
            'ac_src_wallet' => 'R000000000000',
            'ac_dest_wallet' => 'R000000000001',
            'ac_order_id' => '1488216728',
            'ac_amount' => '6.00',
            'ac_merchant_currency' => 'RUR',
        ];

        $passwordHash = $this->request->getSecret();

        $expectedFingerprint = "{$parameters['ac_transfer']}:{$parameters['ac_start_date']}:{$parameters['ac_sci_name']}:{$parameters['ac_src_wallet']}:{$parameters['ac_dest_wallet']}:{$parameters['ac_order_id']}:{$parameters['ac_amount']}:{$parameters['ac_merchant_currency']}:{$passwordHash}";

        $fingerprint = $this->request->createResponseHash($parameters);
        $this->assertEquals(hash('sha256', $expectedFingerprint), $fingerprint);
    }

    public function testSendSuccess()
    {
        $httpRequest = new HttpRequest([], [
            'ac_transfer' => '0d7285b0-feab-45a4-8b6c-7aaadaeb4ffc',
            'ac_start_date' => 'R904800934646',
            'ac_sci_name' => 'Name',
            'ac_src_wallet' => 'R000000000000',
            'ac_dest_wallet' => 'R000000000001',
            'ac_order_id' => '1488216728',
            'ac_amount' => '6.00',
            'ac_merchant_currency' => 'RUR',
            'ac_hash' => '07704b6129c8d96beb415ceb35951466c3c47216e5bd05afe6d7b13c963085a9',
            'ac_transaction_status' => 'COMPLETED',
        ]);
        $request = new CompletePurchaseRequest($this->getHttpClient(), $httpRequest);
        $request->setSecret('Secret');
        $response = $request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1488216728', $response->getTransactionId());
        $this->assertEquals('6.00', $response->getAmount());
        $this->assertEquals('RUR', $response->getCurrency());
    }
}