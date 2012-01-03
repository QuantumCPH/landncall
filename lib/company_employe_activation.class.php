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
   
                    if(!$telintaRegisterCus){
                       emailLib::sendErrorInTelinta("Error in B2b company registration", "Unable to call. We have faced an issue in company registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_landNcall_B2b&action=add&name=".$companyCVR."&currency=SEK&enable_dialingrules=Yes&int_dial_pre=00&email=okh@zapna.com&customer_class=3332&type=customer&credit_limit=&opening_balance=5000. <br/> Please Investigate.");
                        return false;
                    }
                    parse_str($telintaRegisterCus);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in B2b company registration", "We have faced an issue on Success in company registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_landNcall_B2b&action=add&name=".$companyCVR."&currency=SEK&enable_dialingrules=Yes&int_dial_pre=00&email=okh@zapna.com&customer_class=3332&type=customer&credit_limit=&opening_balance=5000. <br/> Please Investigate.");
                        return false;
                    }


                return true;      
    }

    public static function telintaRegisterEmployee($employeMobileNumber, $companyCVRNumber) {

   // $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=' . $employeMobileNumber . '&customer=' . $companyCVRNumber . '&opening_balance=0&product=zerocall_app_dk&outgoing_default_r_r=2039&credit_limit=&billing_model=1&password=' . $passwordVar);
        $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$employeMobileNumber.'&customer='.$companyCVRNumber.'&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');
        
             if(!$telintaAddAccountA){
                       emailLib::sendErrorInTelinta("Error in B2b employee  a account registration", "We have faced an issue in employee registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a".$employeMobileNumber."&customer=".$companyCVRNumber."&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd. <br/> Please Investigate.");
                        return false;
                    }
                    parse_str($telintaAddAccountA);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in employee  a account   registration", "We have faced an issue in employee registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a".$employeMobileNumber."&customer=".$companyCVRNumber."&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd. <br/> Please Investigate.");
                        return false;
                    }
    sleep(0.25);
         $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$employeMobileNumber.'&customer='.$companyCVRNumber.'&opening_balance=0&credit_limit=&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');
  if(!$telintaAddAccountCB){
                       emailLib::sendErrorInTelinta("Error in B2b employee CB account registration", "We have faced an issue in employee registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb".$employeMobileNumber."&customer=".$companyCVRNumber."&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd. <br/> Please Investigate.");
                        return false;
                    }
                    parse_str($telintaAddAccountCB);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in B2b employee CB account registration", "We have faced an issue in employee registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb".$employeMobileNumber."&customer=".$companyCVRNumber."&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd. <br/> Please Investigate.");
                        return false;
                    }

   return true;    
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
