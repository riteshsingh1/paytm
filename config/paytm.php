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

    'SUB_WALLET_GUID' => env('SUB_WALLET_GUID'),

    'PAYTM_MERCHANT_WEBSITE' => env('PAYTM_MERCHANT_WEBSITE'),

    'PAYTM_REFUND_URL' => env('PAYTM_REFUND_URL'),

    'PAYTM_STATUS_QUERY_URL' => env('PAYTM_STATUS_QUERY_URL', 'https://securegw-stage.paytm.in/order/status'),

    'PAYTM_TXN_URL' => env('PAYTM_TXN_URL','https://securegw-stage.paytm.in/order/process'),

    'PAYTM_GRATIFICATION_URL' => env('PAYTM_GRATIFICATION_URL'),

    'PAYTM_DOMAIN' => env('PAYTM_DOMAIN', 'pguat.paytm.com'),

    'PAYTM_WALLET_DOMAIN' => env('PAYTM_WALLET_DOMAIN', 'trust-uat.paytm.in')

];

/*
