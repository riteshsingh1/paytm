<?php


namespace imritesh\paytm;


interface Base
{
    /**
     * @return mixed
     */
    public function dataIsValid();

    /**
     * @return mixed
     */
    public function makePayment();
}