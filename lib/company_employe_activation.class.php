<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of company_employe_activation
 *
 * @author baran
 */
class CompanyEmployeActivation {

    //put your code here

    public $CompnayId;

    public $callingModel;
    public $targetUrl;

   

    public static function telintaRegisterCompany($companyCVR) {
        $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_landNcall_B2b&action=add&name='.$companyCVR.'&currency=SEK&enable_dialingrules=Yes&int_dial_pre=00&email=okh@zapna.com&customer_class=3332&type=customer&credit_limit=&opening_balance=5000');
    sleep(0.5);

    }

    public static function telintaRegisterEmployee($employeMobileNumber, $companyCVRNumber) {

   // $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=' . $employeMobileNumber . '&customer=' . $companyCVRNumber . '&opening_balance=0&product=zerocall_app_dk&outgoing_default_r_r=2039&credit_limit=&billing_model=1&password=' . $passwordVar);
        $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$employeMobileNumber.'&customer='.$companyCVRNumber.'&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');
            sleep(0.5);
         $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$employeMobileNumber.'&customer='.$companyCVRNumber.'&opening_balance=0&credit_limit=&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');
 

    }

    public static function randomPrefix($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    public static function randomPrefixq($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    public static function randomPrefixw($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

}

?>
