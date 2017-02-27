<?php

namespace Omnipay\Advcash\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    private $endpoint = 'https://wallet.advcash.com/sci/';

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getAccount()
    {
        return $this->getParameter('account');
    }

    public function setAccount($value)
    {
        return $this->setParameter('account', $value);
    }

    public function getAccountName()
    {
        return $this->getParameter('accountName');
    }

    public function setAccountName($value)
    {
        return $this->setParameter('accountName', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getData()
    {
        $this->validate('account', 'accountName', 'currency', 'amount');

        $sign = hash('sha256', $this->getAccount() . ":" . $this->getAccountName() . ":" . $this->getAmount() . ":" . $this->getCurrency() . ":" . $this->getSecret() . ":" . $this->getTransactionId());

        $data['ac_account_email'] = $this->getAccount();
        $data['ac_sci_name'] = $this->getAccountName();
        $data['ac_amount'] = $this->getAmount();
        $data['ac_currency'] = $this->getCurrency();
        $data['ac_order_id'] = $this->getTransactionId();
        $data['ac_sign'] = $sign;
        $data['ac_comments'] = $this->getDescription();

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getEndpoint());
    }
}
