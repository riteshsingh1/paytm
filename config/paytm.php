<?php

return [
    /**
     * Available Options : TEST, PROD
     *  - Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
     *  - Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
     *  - Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
     *  - Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
     *  - Above details will be different for testing and production environment.
     */
    'PAYTM_ENVIRONMENT'=> env('PAYTM_ENVIRONMENT', 'TEST'),

    'PAYTM_MERCHANT_KEY'=> env('PAYTM_MERCHANT_KEY', 'xxxxxxxxxxx'),

    'PAYTM_MERCHANT_MID' => env('PAYTM_MERCHANT_MID'),

    'PAYTM_MERCHANT_GUID' => env('PAYTM_MERCHANT_GUID'),

    'PAYTM_SALES_WALLET_GUID' => env('PAYTM_SALES_WALLET_GUID'),

    'PAYTM_MERCHANT_WEBSITE' => env('PAYTM_MERCHANT_WEBSITE'),

    'PAYTM_REFUND_URL' => env('PAYTM_REFUND_URL'),

    'PAYTM_STATUS_QUERY_URL' => env('PAYTM_STATUS_QUERY_URL'),

    'PAYTM_TXN_URL' => env('PAYTM_TXN_URL'),

    'PAYTM_GRATIFICATION_URL' => env('PAYTM_GRATIFICATION_URL'),

    'PAYTM_DOMAIN' => env('PAYTM_DOMAIN', 'pguat.paytm.com'),

    'PAYTM_WALLET_DOMAIN' => env('PAYTM_WALLET_DOMAIN', 'trust-uat.paytm.in')

];

/*
- Use PAYTM_ENVIRONMENT as 'PROD' if you wanted to do transaction in production environment else 'TEST' for doing transaction in testing environment.
- Change the value of PAYTM_MERCHANT_KEY constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_MID constant with details received from Paytm.
- Change the value of PAYTM_MERCHANT_WEBSITE constant with details received from Paytm.
- Above details will be different for testing and production environment.
*/
// define('PAYTM_ENVIRONMENT', 'TEST'); // PROD
// define('PAYTM_MERCHANT_KEY', 'xxxxxxxxxxxxxxxxxxxxxxxx'); //Change this constant's value with Merchant key downloaded from portal
// define('PAYTM_MERCHANT_MID', 'xxxxxxxxxxxxxxxxxxxxxxx'); //Change this constant's value with MID (Merchant ID) received from Paytm
// define('PAYTM_MERCHANT_GUID', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); //Change this constant's value with MGUID (Merchant Guid) received from Paytm
// define('PAYTM_SALES_WALLET_GUID', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); //Change this constant's value with Sales Wallet Guid received from Paytm
// define('PAYTM_MERCHANT_WEBSITE', 'xxxxxxx'); //Change this constant's value with Website name received from Paytm
// $PAYTM_DOMAIN = "pguat.paytm.com";
// $PAYTM_WALLET_DOMAIN = "trust-uat.paytm.in";
// if (PAYTM_ENVIRONMENT == 'PROD') {
// 	$PAYTM_DOMAIN = 'secure.paytm.in';
// 	$PAYTM_WALLET_DOMAIN = "trust.paytm.in"
// }
// define('PAYTM_REFUND_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND');
// define('PAYTM_STATUS_QUERY_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS');
// define('PAYTM_TXN_URL', 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction');
// define('PAYTM_GRATIFICATION_URL', 'https://'.$PAYTM_WALLET_DOMAIN.'/wallet-web/salesToUserCredit');
