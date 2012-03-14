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
set_time_limit(10000000);
class CompanyEmployeActivation {

    //put your code here

       //put your code here
    private static $iParent = 72197;                //Company Resller ID on Telinta
    private static $currency = 'SEK';
    private static $a_iProduct = 7993;
    private static $CBProduct = 7992;
    private static $VoipProduct = 7994;
    private static $telintaSOAPUrl = "https://mybilling.telinta.com";
    private static $telintaSOAPUser = 'API_login';
    private static $telintaSOAPPassword = 'ee4eriny';

   

    public static function telintaRegisterCompany(Company $company) {
       
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
           $vatNumber="LCB2B".$company->getVatNo();
        try {
            $tCustomer = $pb->add_customer(array('customer_info' => array(
                            'name' => $vatNumber, //75583 03344090514
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
        //$company->save();
        $pb->_logout();
        return true;
    }


   public static function telintaRegisterEmployeeCT($employeMobileNumber, Company $company) {

        return self::createAccount($company, $employeMobileNumber, 'a', self::$a_iProduct);
   }

   public static function telintaRegisterEmployeeCB($employeMobileNumber, Company $company) {

        return self::createAccount($company, $employeMobileNumber, 'cb', self::$CBProduct);
   }

   public static function createReseNumberAccount($VOIPNumber, Company $company, $currentActiveNumber) {

        if (self::createAccount($company, $VOIPNumber, '', self::$VoipProduct,'Y')) {
            $ct = new Criteria();
            $ct->add(TelintaAccountsPeer::ACCOUNT_TITLE, $VOIPNumber);
            $ct->addAnd(TelintaAccountsPeer::STATUS, 3);
            $telintaAccount = TelintaAccountsPeer::doSelectOne($ct);
            $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
            $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

            try {
                $accounts = $pb->update_account_followme(array('i_account' => $telintaAccount->getIAccount(),
                            "followme_info" => array(
                                'i_account' => $telintaAccount->getIAccount(),
                                'period' => '',
                                'sequence' => 'Order',
                                'timeout' => 30
                                )));
            } catch (SoapFault $e) {
                emailLib::sendErrorInTelinta("Account update_account_followme: " . $accountName . " Error!", "We have faced an issue in Customer Account Deletion on telinta. this is the error for cusotmer with  id: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                $pb->_logout();
                return false;
            }

            try {
                $accounts = $pb->add_followme_number(array('i_account' => $telintaAccount->getIAccount(),
                            'number_info' => array(
                                'i_account' => $telintaAccount->getIAccount(),
                                'name' => $currentActiveNumber,
                                'redirect_number' => $currentActiveNumber,
                                'period' => 'always',
                                'period_description' => 'Always',
                                'active' => 'Y',
                                'timeout' => 15
                            )
                        ));
            } catch (SoapFault $e) {
                emailLib::sendErrorInTelinta("Account add_followme_number: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account Deletion on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                $pb->_logout();
                return false;
            }
           
        }
         return true;
    }

   public static function recharge(Company $company, $amount) {
        return self::makeTransaction($company, "Manual payment", $amount);
    }

    public static function charge(Company $company, $amount) {
        return self::makeTransaction($company, "Manual charge", $amount);
    }

     public static function terminateAccount(TelintaAccounts $telintaAccount) {
        try {
            $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
            $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
            $account = $pb->terminate_account(array('i_account' => $telintaAccount->getIAccount()));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Account Deletion: " . $accountName . " Error!", "We have faced an issue in Company Account Deletion on telinta. this is the error for cusotmer with  id: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $telintaAccount->setStatus(5);
        $telintaAccount->save();
        $pb->_logout();
        return true;
    }

     public static function getAccountCallHistory($iAccount, $fromDate, $toDate) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $xdrList = $pb->get_xdr_list(array('i_account' => $iAccount, 'from_date' => $fromDate, 'to_date' => $toDate));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Employee Call History: " . $iAccount . " Error!", "We have faced an issue with Employee while Fetching Call History  this is the error for cusotmer with ID: " . $iAccount . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
        }
        $pb->_logout();
        return $xdrList;
    }

    public static function getAccountInfo($iAccount) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

        try {
            $aInfo = $pb->get_account_info(array(
                        'i_account' => $iAccount,
                    ));
            $pb->_logout();
        } catch (SoapFault $e) {//" . $company->getId() . "
            emailLib::sendErrorInTelinta("Employee Account ifo Fetching:  Error!", "We have faced an issue in Employee Account Info Fetch on telinta. this is the error for cusotmer with  account: " . $telintaAccount . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $pb->_logout();
        return $aInfo;
    }

     public static function getBalance(Company $company) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

        try {

            $cInfo = $pb->get_customer_info(array(
                        'i_customer' => $company->getICustomer(),
                    ));
            $Balance = $cInfo->customer_info->balance;
            $pb->_logout();
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Company Balance Fetching: " . $company->getId() . " Error!", "We have faced an issue in Company Account Balance Fetch on telinta. this is the error for cusotmer with  Uniqueid: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $pb->_logout();
        if ($Balance == 0)
            return $Balance;
        else
            return -1 * $Balance;
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
    private static function makeTransaction(Company $company, $action, $amount) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $accounts = $pb->make_transaction(array(
                        'i_customer' => $company->getICustomer(),
                        'action' => $action, //Manual payment, Manual charge
                        'amount' => $amount,
                        'visible_comment' => 'charge by SOAP ' . $action
                    ));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Customer Transcation: " . $company->getId() . " Error!", "We have faced an issue with Customer while making transaction " . $action . " this is the error for cusotmer with  Customer ID: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $pb->_logout();
        return true;
    }
     public static function callHistory(Company $company, $fromDate, $toDate) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $xdrList = $pb->get_customer_xdr_list(array('i_customer' => $company->getICustomer(), 'from_date' => $fromDate, 'to_date' => $toDate));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Company Call History: " . $company->getId() . " Error!", "We have faced an issue with Company while Fetching Call History  this is the error for cusotmer with  Company ID: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
        }
        $pb->_logout();
        return $xdrList;
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
