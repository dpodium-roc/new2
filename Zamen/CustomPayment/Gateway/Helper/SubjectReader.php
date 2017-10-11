<?php
namespace Zamen\CustomPayment\Gateway\Helper;

use CustomPayment\Transaction;

use Magento\Quote\Model\Quote;
use Magento\Payment\Gateway\Helper;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;

class SubjectReader
{
    //read response object
    public function readResponseObject(array $subject)
    {
        $response = Helper\SubjectReader::readResponse($subject);
        if (!isset($response['object']) || !is_object($response['object']))
        {
            throw new \InvalidArgumentException('Response object does not exist');
        }
        return $response['object'];
    }

    //read payment
    public function readPayment(array $subject)
    {
        return Helper\SubjectReader::readPayment($subject);
    }


    //read transaction
    public function readTransaction(array $subject)
    {
        if (!isset($subject['object']) || !isset($subject['object']))
        {
            throw new \InvalidArgumentException('Response object does not exist');
        }

        if (!isset($subject['object']->transaction) && !$subject['object']->transaction instanceof Transaction)
        {
            throw new \InvalidArgumentException('The object is not a class \CustomPayment\Transaction');
        }
        return $subject['object']->transaction;
    }

    //read amount
    public function readAmount(array $subject)
    {
        return Helper\SubjectReader::readAmount($subject);
    }

    //read customer id
    public function readCustomerId(array $subject)
    {
        if (!isset($subject['customer_id']))
        {
            throw new \InvalidArgumentException('The "customerID field does not exist"');
        }
        return (int) $subject['customer_id'];
    }

    //read public hash
    public function readPublicHash(array $subject)
    {
        if (empty($subject[PaymentTokenInterface::PUBLIC_HASH]))
        {
            throw new \InvalidArgumentException('The "public hash" field does not exist');
        }
        return $subject[PaymentTokenInterface::PUBLIC_HASH];
    }

    //read paypal
    public function readPayPal(Transaction $transaction)
    {
        if (!isset($transaction->paypal))
        {
            throw new \InvalidArgumentException('Transaction has no paypal attribute');
        }
        return $transaction->paypal;
    }

}