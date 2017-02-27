<?php

namespace Omnipay\Advcash;

use Omnipay\Common\AbstractGateway;

/**
 * Gateway Class
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Advcash';
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

    public function getApiName()
    {
        return $this->getParameter('apiName');
    }

    public function setApiName($value)
    {
        return $this->setParameter('apiName', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('apiSecret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('apiSecret', $value);
    }

    public function getDefaultParameters()
    {
        return array(
            'account' => '',
            'accountName' => '',
            'secret' => '',
        );
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Advcash\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Advcash\Message\CompletePurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Advcash\Message\RefundRequest', $parameters);
    }
}
