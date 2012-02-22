<?php
require_once(sfConfig::get('sf_lib_dir') . '/telintaSoap.class.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Telinta
 * emails are being sent in each of the action and same that is just becuse if Managment needs diffrent messages.
 * @author baran Khan
 */
class Telienta {

    //put your code here

    private static $customerReseller            = 'R_LandNcall';
    private static $companyReseller             = 'R_LandNcall_B2B';
    private static $currency                    = 'SEK';
    private static $AProduct                    = 'YYYLandncall_CT';
    private static $CBProduct                   = 'YYYLandncall_callback';
    private static $VoipProduct                 = 'YYYLandncall_Forwarding';
    private static $telintaSOAPUrl              = "https://mybilling.telinta.com";
    private static $telintaSOAPUser             = 'API_login';
    private static $telintaSOAPPassword         = 'ee4eriny';


    public static function ResgiterCustomer($uniqueId, $OpeningBalance,$company=false) {

        if($company)
            $reseller = self::$companyReseller;
        else
            $reseller = self::$customerReseller;

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=" . $reseller . "&action=add&name=" . $uniqueId . "&currency=" . self::$currency . "&opening_balance=-" . $OpeningBalance . "&credit_limit=0&enable_dialingrules=Yes&int_dial_pre=00&email=okh@zapna.com&type=customer";
        $RegisterCustomer = file_get_contents($url);

        sleep(0.5);

        if (!$RegisterCustomer) {
            emailLib::sendErrorInTelinta("Error in Customer Registration", "Unable to call. We have faced an issue in Customer registration on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($RegisterCustomer);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in Customer Registration", "We have faced an issue on Success in customer registration on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $RegisterCustomer . "  <br/> Please Investigate.");
            return false;
        }


        return true;
    }

    public static function createAAccount($mobileNumber, $uniqueId) {

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a" . $mobileNumber . "&customer=" . $uniqueId . "&opening_balance=0&credit_limit=&product=" . self::$AProduct . "&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd";
        $aAccount = file_get_contents($url);

        sleep(0.5);

        if (!$aAccount) {
            emailLib::sendErrorInTelinta("Error in createAAccount", "Unable to call. We have faced an issue in createAAccount on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($aAccount);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in createAAccount", "We have faced an issue on Success in createAAccount on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $aAccount . "  <br/> Please Investigate.");
            return false;
        }


        return true;
    }

    public static function createCBount($mobileNumber, $uniqueId) {
        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb" . $mobileNumber . "&customer=" . $uniqueId . "&opening_balance=0&credit_limit=&product=" . self::$CBProduct . "&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd";
        $cbAccount = file_get_contents($url);
        sleep(0.5);
        if (!$cbAccount) {
            emailLib::sendErrorInTelinta("Error in createCBount", "Unable to call. We have faced an issue in createCBount on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($cbAccount);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in createCBount", "We have faced an issue on Success in createCBount on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $cbAccount . "  <br/> Please Investigate.");
            return false;
        }
        return true;
    }

    public static function deactivateFollowMeNumber($VOIPNumber,$CurrentActiveNumber){

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=update&name=" . $VOIPNumber . "&active=N&follow_me_number=" . $CurrentActiveNumber . "&type=account";
        $deactivate = file_get_contents($url);
        sleep(0.5);
        if (!$deactivate) {
            emailLib::sendErrorInTelinta("Error in deactivateFollowMeNumber", "Unable to call. We have faced an issue in deactivateFollowMeNumber on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($deactivate);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in deactivateFollowMeNumber", "We have faced an issue on Success in deactivateFollowMeNumber on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $deactivate . "  <br/> Please Investigate.");
            return false;
        }
        return true;
    }


    public static function delAccount($account){

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=".$account."&type=account";
        $del = file_get_contents($url);
        sleep(0.5);
        if (!$del) {
            emailLib::sendErrorInTelinta("Error in delAccount", "Unable to call. We have faced an issue in delAccount on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($del);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in delAccount", "We have faced an issue on Success in delAccount on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $del . "  <br/> Please Investigate.");
            return false;
        }
        return true;
    }

    public static function getBalance($uniqueId){

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser,self::$telintaSOAPPassword );

        try {
        $cInfo = $pb->get_customer_info(array(
                'name' => $uniqueId ,
        ));
          } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Error in Customer Balance", "We have faced an issue in getBalnace on telinta. this is the error for cusotmer with  id: " . $uniqueId . " and error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $Balance = $cInfo->customer_info->balance;
        $pb->_logout();

        if($Balance==0)
            return $Balance;
        else
            return -1*$Balance;
    }


    public static function createReseNumberAccount($VOIPNumber,$uniqueId,$currentActiveNumber){

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=".$VOIPNumber . "&customer=" . $uniqueId . "&opening_balance=0&credit_limit=&product=".self::$VoipProduct."&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=" . $currentActiveNumber . "&billing_model=1&password=asdf1asd";
        $reseNumber = file_get_contents($url);
        sleep(0.5);
        if (!$reseNumber) {
            emailLib::sendErrorInTelinta("Error in createReseNumberAccount", "Unable to call. We have faced an issue in createReseNumberAccount on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($reseNumber);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in createReseNumberAccount", "We have faced an issue on Success in createReseNumberAccount on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $reseNumber . "  <br/> Please Investigate.");
            return false;
        }
        return true;
    }

    public static function charge($uniqueId,$amount){

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=customer&action=manual_charge&name=" . $uniqueId . "&amount=" . $amount;
        $chargeRes = file_get_contents($url);
        sleep(0.5);
        if (!$chargeRes) {
            emailLib::sendErrorInTelinta("Error in charge", "Unable to call. We have faced an issue in charge on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($chargeRes);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in charge", "We have faced an issue on Success in charge on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $chargeRes . "  <br/> Please Investigate.");
            return false;
        }
        return true;
    }

     public static function recharge($uniqueId,$amount){

        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name=".$uniqueId."&amount=".$amount."&type=customer";
        $chargeRes = file_get_contents($url);
        sleep(0.5);
        if (!$chargeRes) {
            emailLib::sendErrorInTelinta("Error in charge", "Unable to call. We have faced an issue in charge on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        parse_str($chargeRes);
        if (isset($success) && $success != "OK") {
            emailLib::sendErrorInTelinta("Error in charge", "We have faced an issue on Success in charge on telinta. this is the error on the following url:" . $url . " <br/> and error is: " . $chargeRes . "  <br/> Please Investigate.");
            return false;
        }
        return true;
    }

    public static function callHistory($uniqueId,$fromDate,$toDate){
        $url = "https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=customer&action=get_xdrs&name=".$uniqueId."&tz=Europe/Stockholm&from_date=".$fromDate."&to_date=".$toDate;
        $history = file_get_contents($url);
        sleep(0.5);
        if (!$history) {
            emailLib::sendErrorInTelinta("Error in callHistory", "Unable to call. We have faced an issue in callHistory on telinta. this is the error on the following url: " . $url . "  <br/> Please Investigate.");
            return false;
        }
        return $history;
    }







}

?>
