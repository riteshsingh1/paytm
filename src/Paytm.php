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


 public function getStatus($orderId)
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
            $checksum = (new Process)->getChecksumFromArray($paytmParams, config('paytm.PAYTM_MERCHANT_KEY'));

            /* put generated checksum value here */
            $paytmParams["CHECKSUMHASH"] = $checksum;

            /* prepare JSON string for request */
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

            /* for Staging */
            $url = "https://securegw-stage.paytm.in/order/status";

            /* for Production */
            // $url = "https://securegw.paytm.in/order/status";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
            return curl_exec($ch);
    }
