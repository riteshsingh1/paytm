<?php

namespace imritesh\paytm;

use imritesh\paytm\Process;
/**
 * Paytm()->purpose('INCENTIVE')->accountNumber()->ifsc()->withReferenceId()->paymentAmount(100)->later()->pay();
 * Paytm()->checkPaymentStatus($referenceId);
 */
class Paytm implements Base
{
    /**
     * @var
     */
    protected $referenceId;
    /**
     * @var
     */
    protected $account;
    /**
     * @var
     */
    protected $ifscCode;
    /**
     * @var
     */
    protected $amount;
    /**
     * @var string
     */
    protected $purposeName = 'INCENTIVE';

    /**
     * @var
     */
    public $response;

    /**
     * @var
     */
    public $later = false;

    /**
     * @param $amount
     * @return Paytm
     */
    public function paymentAmount($amount): Paytm
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param $purpose
     * @return $this
     */
    public function purpose($purpose): Paytm
    {
        $this->purposeName = $purpose;
        return $this;
    }

    /**
     * @param $accountNumber
     * @return $this
     */
    public function accountNumber($accountNumber) : Paytm
    {
        $this->account = $accountNumber;
        return $this;
    }

    /**
     * @param $ifscCode
     * @return $this
     */
    public function ifsc($ifscCode) : Paytm
    {
        $this->ifscCode = $ifscCode;
        return $this;
    }

    public function withReferenceId($refId): Paytm
    {
        $this->referenceId = $refId;
        return $this;
    }

    /**
     * @return \Exception|Paytm
     */
    public function pay()
    {
        try {
            if ($this->dataIsValid()) {
                 $this->response =  $this->makePayment();
            }
        } catch (\Exception $e) {
            return $e;
        }
        return $this;
    }

    /**
     * @param $orderId
     * @return bool|string
     */
    public function checkPaymentStatus($orderId)
    {
        /* initialize an array */
        $paytmParams = array();

        /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
        $paytmParams["MID"] = config('paytm.PAYTM_MERCHANT_MID');

        /* Enter your order id which needs to be check status for */
        $paytmParams["ORDERID"] = $orderId;
        /**
         * Generate checksum by parameters we have
         * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys
         */
        $checksum = Process::getChecksumFromArray($paytmParams, config('paytm.PAYTM_MERCHANT_KEY'));

        /* put generated checksum value here */
        $paytmParams["CHECKSUMHASH"] = $checksum;

        /* prepare JSON string for request */
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        $ch = curl_init(config('paytm.PAYTM_STATUS_QUERY_URL'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        return curl_exec($ch);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function dataIsValid(): bool
    {
        if(!$this->account)
        {
          return throw new \Exception('Account Number Is Required', 422);
        }
        if(!$this->referenceId)
        {
            return throw new \Exception('Reference ID Is Required', 422);
        }
        if (!$this->ifscCode)
        {
            return throw new \Exception('IFSC Code Is Required', 422);
        }
        return true;
    }

    /**
     * @return $this|mixed
     */
    public function makePayment()
    {
        /* prepare JSON string for request body */
        $post_data = json_encode($this->buildParams(), JSON_UNESCAPED_SLASHES);
        /**
         * Generate checksum by parameters we have in body
         */
        $checksum = Process::getChecksumFromString($post_data, "GKpp@p_b!l%MG#cC");

        /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
        $x_mid = config('paytm.PAYTM_MERCHANT_MID');

        /* put generated checksum value here */
        $x_checksum = $checksum;

        $ch = curl_init(config('paytm.PAYTM_TXN_URL'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $this->response =  json_decode($response);
        return $this;
    }

    /**
     * @return array
     */
    public function buildParams(): array
    {
        /* initialize an array */
        $paytmParams = array();

        /* Find Sub Wallet GUID in your Paytm Dashboard at https://dashboard.paytm.com */
        $paytmParams["subwalletGuid"] = config('paytm.SUB_WALLET_GUID');

        /* Enter your order id which needs to be check disbursal status for */
        $paytmParams["orderId"] = $this->referenceId;

        /* Enter Beneficiary Account Number in which the disbursal needs to be made */
        $paytmParams["beneficiaryAccount"] = $this->account;

        /* Enter Beneficiary's Bank IFSC Code */
        $paytmParams["beneficiaryIFSC"] = $this->ifscCode;

        /* Amount in INR to transfer */
        $paytmParams["amount"] = $this->amount;

        /* Enter Purpose of transfer, possible values are: SALARY_DISBURSEMENT, REIMBURSEMENT, BONUS, INCENTIVE, OTHER */
        $paytmParams["purpose"] = "INCENTIVE";

        /* Enter the date for which you wants to disburse the amount. Required if purpose is SALARY_DISBURSEMENT or REIMBURSEMENT */
        if ($this->later)
        {
             $paytmParams["date"] = "YYYY-MM-DD";
        }
        return $paytmParams;
    }
}
