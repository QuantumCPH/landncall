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

       //put your code here
    private static $iParent = 74137;                //Company Resller ID on Telinta
    private static $currency = 'SEK';
    private static $a_iProduct = 10281;
    private static $CBProduct = '';
    private static $VoipProduct = '';
    private static $telintaSOAPUrl = "https://mybilling.telinta.com";
    private static $telintaSOAPUser = 'API_login';
    private static $telintaSOAPPassword = 'ee4eriny';

   

    public static function telintaRegisterCompany(Company $company) {

        
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $tCustomer = $pb->add_customer(array('customer_info' => array(
                            'name' => $company->getVatNo(), //75583 03344090514
                            'iso_4217' => self::$currency,
                            'i_parent' => self::$iParent,
                            'i_customer_type' => 1,
                            'opening_balance' => -(5000),
                            'credit_limit' => null,
                            'dialing_rules' => array('ip' => '00'),
                            'email' => 'okh@zapna.com'
                            )));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Error in Company Registration", "We have faced an issue in Company registration on telinta. this is the error for cusotmer with  id: " . $company->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $company->setICustomer($tCustomer->i_customer);
        $company->save();
        $pb->_logout();
        return true;
    }

    public static function telintaRegisterEmployeeCT($employeMobileNumber, Company $company) {

        return self::createAccount($company, $employeMobileNumber, 'a', self::$a_iProduct);
   }
   public static function telintaRegisterEmployeeCB($employeMobileNumber, Company $company) {

        return self::createAccount($company, $employeMobileNumber, 'cb', self::$a_iProduct);
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

    private static function createAccount(Company $company, $mobileNumber, $accountType, $iProduct, $followMeEnabled='N') {

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

        try {
            $accountName = $accountType . $mobileNumber;
            $account = $pb->add_account(array('account_info' => array(
                            'i_customer' => $company->getICustomer(),
                            'name' => $accountName, //75583 03344090514
                            'id' => $accountName,
                            'iso_4217' => self::$currency,
                            'opening_balance' => 0,
                            'credit_limit' => null,
                            'i_product' => $iProduct,
                            'i_routing_plan' => 2039,
                            'billing_model' => 1,
                            'password' => 'asdf1asd',
                            'h323_password' => 'asdf1asd',
                            'activation_date' => date('Y-m-d'),
                            'batch_name' => $company->getVatNo(),
                            'follow_me_enabled' => $followMeEnabled
                            )));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Account Creation: " . $accountName . " Error!", "We have faced an issue in Company Account Creation on telinta. this is the error for cusotmer with  id: " . $company->getId() . " and on Account" . $accountName . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }

        $telintaAccount = new TelintaAccounts();
        $telintaAccount->setAccountTitle($accountName);
        $telintaAccount->setParentId($company->getId());
        $telintaAccount->setParentTable("company");
        $telintaAccount->setICustomer($company->getICustomer());
        $telintaAccount->setIAccount($account->i_account);
        $telintaAccount->save();
        return true;
    }
    public static function recharge(Company $company, $amount) {
        return self::makeTransaction($company, "Manual payment", $amount);
    }

}

?>
