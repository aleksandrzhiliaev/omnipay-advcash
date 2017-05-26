<?php

namespace Omnipay\Advcash\Message;


use Omnipay\Advcash\authDTO;
use Omnipay\Advcash\MerchantWebService;
use Omnipay\Advcash\sendMoney;
use Omnipay\Advcash\sendMoneyRequest;
use Omnipay\Advcash\validationSendMoney;
use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends AbstractRequest
{
    protected $endpoint = 'https://www.nixmoney.com/send';

    public function getAccount()
    {
        return $this->getParameter('account');
    }

    public function setAccount($value)
    {
        return $this->setParameter('account', $value);
    }

    public function getApiName()
    {
        return $this->getParameter('apiName');
    }

    public function setApiName($value)
    {
        return $this->setParameter('apiName', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    public function getPayeeAccount()
    {
        return $this->getParameter('payeeAccount');
    }

    public function setPayeeAccount($value)
    {
        return $this->setParameter('payeeAccount', $value);
    }

    public function setCurrency($value)
    {
        if ($value !== null) {
            $value = strtoupper($value);
        }
        $value = str_replace('RUB', 'RUR', $value);
        return $this->setParameter('currency', $value);
    }

    public function getData()
    {
        $this->validate('payeeAccount', 'amount', 'description');

        $data['apiName'] = $this->getApiName();
        $data['accountEmail'] = $this->getAccount();
        $data['apiSecret'] = $this->getApiSecret();
        $data['amount'] = number_format((float)$this->getAmount(), 2, '.', '');
        $data['currency'] = $this->getCurrency();
        $data['payeeEmail'] = $this->getPayeeAccount();
        $data['note'] = $this->getDescription();

        return $data;
    }

    public function sendData($data)
    {
        $merchantWebService = new MerchantWebService();

        $arg0 = new authDTO();
        $arg0->apiName = $data['apiName'];
        $arg0->accountEmail = $data['accountEmail'];
        $arg0->authenticationToken = $merchantWebService->getAuthenticationToken($data['apiSecret']);

        $arg1 = new sendMoneyRequest();
        $arg1->amount = $data['amount'];
        $arg1->currency = $data['currency'];
        $arg1->email = $data['payeeEmail'];
        $arg1->note = $data['note'];
        $arg1->savePaymentTemplate = false;

        $validationSendMoney = new validationSendMoney();
        $validationSendMoney->arg0 = $arg0;
        $validationSendMoney->arg1 = $arg1;

        $sendMoney = new sendMoney();
        $sendMoney->arg0 = $arg0;
        $sendMoney->arg1 = $arg1;

        try {
            $merchantWebService->validationSendMoney($validationSendMoney);
            $sendMoneyResponse = $merchantWebService->sendMoney($sendMoney);
        } catch (\Exception $e) {
            throw new InvalidRequestException('Error: ' . $e->getMessage());
        }

        return $this->response = new RefundResponse($this, $sendMoneyResponse);
    }
}
