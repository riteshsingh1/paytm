<?php
namespace imritesh\paytm;

class Process
{
    public function encrypt_e($input, $ky) {
        // $key = $ky;
        // $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        // $input = $this->pkcs5_pad_e($input, $size);
        // $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        // $iv = "@@@@&&&&####$$$$";
        // mcrypt_generic_init($td, $key, $iv);
        // $data = mcrypt_generic($td, $input);
        // mcrypt_generic_deinit($td);
        // mcrypt_module_close($td);
        $data = base64_encode($input);
        return $data;
    }
    public function decrypt_e($crypt, $ky) {
        // $crypt = base64_decode($crypt);
        // $key = $ky;
        // $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        // $iv = "@@@@&&&&####$$$$";
        // mcrypt_generic_init($td, $key, $iv);
        // $decrypted_data = mdecrypt_generic($td, $crypt);
        // mcrypt_generic_deinit($td);
        // mcrypt_module_close($td);
        // $decrypted_data = $this->pkcs5_unpad_e($decrypted_data);
        // $decrypted_data = rtrim($decrypted_data);
        // return $decrypted_data;
        return  base64_decode($crypt);
    }
    public function pkcs5_pad_e($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    public function pkcs5_unpad_e($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        return substr($text, 0, -1 * $pad);
    }
    public function generateSalt_e($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }
    public function checkString_e($value) {
        $myvalue = ltrim($value);
        $myvalue = rtrim($myvalue);
        if ($myvalue == 'null')
            $myvalue = '';
        return $myvalue;
    }
    public function getChecksumFromArray($arrayList, $key, $sort=1) {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = $this->getArray2Str($arrayList);
        $salt = $this->generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }
    public function getChecksumFromString($str, $key) {

        $salt = $this->generateSalt_e(4);
        //$salt = '1234';
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = $this->encrypt_e($hashString, $key);
        return $checksum;
    }
    public function verifychecksum_e($arrayList, $key, $checksumvalue) {
        $arrayList = $this->removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2Str($arrayList);
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);
        $finalString = $str . "|" . $salt;
        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;
        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }
    public function verifychecksumFromstr_e($str, $key, $checksumvalue) {
        /*$arrayList = removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = $this->getArray2Str($arrayList);*/
        $paytm_hash = $this->decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);
        $finalString = $str . "|" . $salt;
        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;
        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }
    public function getArray2Str($arrayList) {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr .= $this->checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . $this->checkString_e($value);
            }
        }
        return $paramStr;
    }
    public function redirect2PG($paramList, $key) {
        $hashString = $this->getchecksumFromArray($paramList, config('paytm.PAYTM_MERCHANT_KEY'));
        $checksum = $this->encrypt_e($hashString, $key);
    }
    public function removeCheckSumParam($arrayList) {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }
    public function getTxnStatus($requestParamList) {
        $statusQueryUrl = 'https://'.config('paytm.PAYTM_STATUS_QUERY_URL').'/oltp/HANDLER_INTERNAL/TXNSTATUS';
        return $this->callAPI($statusQueryUrl, $requestParamList);
    }
    public function initiateTxnRefund($requestParamList) {
        $refundUrl = 'https://'.config('paytm.PAYTM_REFUND_URL').'/oltp/HANDLER_INTERNAL/REFUND';
        $CHECKSUM = $this->getChecksumFromArray($requestParamList,config('paytm.PAYTM_MERCHANT_KEY'),0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return $this->callAPI($refundUrl, $requestParamList);
    }
    public function callAPI($apiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData =json_encode($requestParamList);
        $postData = 'JsonData='.urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse,true);
        return $responseParamList;
    }
}