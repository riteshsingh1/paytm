<?php

namespace imritesh\paytm;

use imritesh\paytm\Process;
/**
 * Paytm('for translator')->toNumber(9810456407)->forOrder()->pay(100);
 */
class Paytm
{

    protected $mobileNumber;
    protected $requestData;
    protected $metaData;
    protected $orderId;

    public function __construct($metaData)
    {
        $this->metaData = $metaData;
    }

    public function forOrder(String $order)
    {
        $this->orderId = $order;
        return $this;
    }

    public function toNumber($number)
    {
        $this->mobileNumber = $number;
        return $this;
    }

    public function pay($amount)
    {
        $paytmGratificationUrl = 'https://'.config('paytm.PAYTM_GRATIFICATION_URL').'/wallet-web/salesToUserCredit';;
        $requestData=json_encode($this->prepareData($amount));
        $Checksumhash = (new Process)->getChecksumFromString($requestData,config('paytm.PAYTM_MERCHANT_KEY'));
        $headerValue = array('Content-Type:application/json','mid:'.config('paytm.PAYTM_MERCHANT_GUID'),'checksumhash:'.$Checksumhash);
        $ch = curl_init($paytmGratificationUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerValue);
        $info = curl_getinfo($ch);
        return curl_exec($ch);
    }

    protected function prepareData($amount) :array
    {
        return ["request" => $this->prepareRequest($amount),
                    $this->withMetaData()
                ];
    }

    public function withMetaData() :array
    {
        return [
            "metadata"=>$this->metaData,
            "ipAddress"=>"127.0.0.1",
            "platformName"=>"PayTM",
            "operationType"=>"SALES_TO_USER_CREDIT"
        ];
    }

    protected function prepareRequest($amount)
    {
       return [
            "requestType" => null,
            "merchantGuid" => config('paytm.PAYTM_MERCHANT_GUID'),
            "merchantOrderId" => $this->orderId,
            "salesWalletName"=> null,
            "salesWalletGuid"=>config('paytm.PAYTM_SALES_WALLET_GUID'),
            "payeeEmailId"=>null,
            "payeePhoneNumber"=>$this->mobileNumber,
            "payeeSsoId"=>"",
            "appliedToNewUsers"=>"Y",
            "amount"=>$amount,
            "currencyCode"=>"INR"
       ];
    }
}
