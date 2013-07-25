<?php

require_once(sfConfig::get('sf_lib_dir') . '/telintaSoap.class.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Telinta
 * emails are being sent in each of the action and same that is just because if Managment needs diffrent messages.
 * @author baran Khan
 */
set_time_limit(10000000);

class Telienta {

    private $currency;
    private $iParentRLandnCall;
    private $iParentUS;
    private $a_iProduct;
    private $cb_iProduct;
    private $voip_iProduct;
    private $telintaSOAPUrl;
//    private $telintaSOAPUser = 'API_login';
//    private $telintaSOAPPassword = 'ee4eriny';

    public function __construct() {
         $this->iParentRLandnCall = sfConfig::get("app_telinta_reseller");
         $this->iParentUS         = sfConfig::get("app_telinta_us_reseller");
         $this->currency          = sfConfig::get("app_telinta_currency");
         $this->telintaSOAPUrl    = sfConfig::get("app_telinta_soap_uri");
         $this->a_iProduct        = sfConfig::get("app_a_iproduct");
         $this->cb_iProduct       = sfConfig::get("app_cb_iproduct");
         $this->voip_iProduct     = sfConfig::get("app_voip_iproduct");
         $this->telinta_soap_user = sfConfig::get("app_telinta_soap_user");
         $this->telinta_soap_password = sfConfig::get("app_telinta_soap_password");
    }
    public function ResgiterCustomer(Customer $customer, $OpeningBalance, $creditLimit=0, $USReseller = false) {
        $tCustomer = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');

        $uniqueId ="LNB2C".$customer->getId().$customer->getUniqueid();
        if ($USReseller) {
            $Parent = $this->iParentUS;
        } else {
            $Parent = $this->iParentRLandnCall;
        }
        while (!$tCustomer && $retry_count < $max_retries) {
            try {

                $tCustomer = $pb->add_customer(array('customer_info' => array(
                                'name' => $uniqueId,
                                'iso_4217' => $this->currency,
                                'i_parent' => $Parent,
                                'i_customer_type' => 1,
                                'opening_balance' => -($OpeningBalance),
                                'credit_limit' => $creditLimit,
                                'dialing_rules' => array('ip' => '00'),
                                'email' => 'okh@zapna.com'
                                )));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                    if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');
                       continue;
                    }else{ 
                       emailLib::sendErrorInTelinta("Error in Customer Registration", "We have faced an issue in Customer registration on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                       return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Error in Customer Registration", "We have faced an issue in Customer registration on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        $customer->setICustomer($tCustomer->i_customer);
        $customer->save();

        return true;
    }

    public function createAAccount($mobileNumber, Customer $customer) {
        return $this->createAccount($customer, $mobileNumber, 'a', $this->a_iProduct);
    }

    public function createCBAccount($mobileNumber, Customer $customer) {
        $iProduct = $this->cb_iProduct;
        return $this->createAccount($customer, $mobileNumber, 'cb',  $iProduct);
    }

    public function createReseNumberAccount($VOIPNumber, Customer $customer, $currentActiveNumber) {
        $voip_iProduct = $this->voip_iProduct; 
        if ($this->createAccount($customer, $VOIPNumber, '', $voip_iProduct, 'Y')) {
            $accounts = false;
            $max_retries = 10;
            $retry_count = 0;

            $ct = new Criteria();
            $ct->add(TelintaAccountsPeer::ACCOUNT_TITLE, $VOIPNumber);
            $ct->addAnd(TelintaAccountsPeer::STATUS, 3);
            $telintaAccount = TelintaAccountsPeer::doSelectOne($ct);
            $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');

            while (!$accounts && $retry_count < $max_retries) {
                try {
                    $accounts = $pb->update_account_followme(array('i_account' => $telintaAccount->getIAccount(),
                                "followme_info" => array(
                                    'i_account' => $telintaAccount->getIAccount(),
                                    'period' => '',
                                    'sequence' => 'Order',
                                    'timeout' => 30
                                    )));
                } catch (SoapFault $e) {
                    if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                       if($e->faultstring=="Authentification failed"){
                           emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                           $this->startNewSession();
                           ///// after starting new session, new object must initialize for PortaBillingSoapCient
                           $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');
                           continue;
                        }else{ 
                          emailLib::sendErrorInTelinta("Account update_account_followme: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account update_account_followme on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                          return false;
                        }
                    }
                }


                sleep(0.5);
                $retry_count++;
            }
            if ($retry_count == $max_retries) {
                emailLib::sendErrorInTelinta("Account update_followme_number: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account update_followme_number on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
                return false;
            }

            $accounts = false;
            $max_retries = 10;
            $retry_count = 0;
            while (!$accounts && $retry_count < $max_retries) {
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
                    if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                        if($e->faultstring=="Authentification failed"){
                           emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                           $this->startNewSession();
                           ///// after starting new session, new object must initialize for PortaBillingSoapCient
                           $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');
                           continue;
                        }else{
                            emailLib::sendErrorInTelinta("Account add_followme_number: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account add_followme_number on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                            return false;
                        }
                    }
                }
                sleep(0.5);
                $retry_count++;
            }
            if ($retry_count == $max_retries) {
                emailLib::sendErrorInTelinta("Account add_followme_number: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account add_followme_number on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
                return false;
            }
        }
    }

    public function terminateAccount(TelintaAccounts $telintaAccount) {
        $account = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');

        while (!$account && $retry_count < $max_retries) {
            try {
                $account = $pb->terminate_account(array('i_account' => $telintaAccount->getIAccount()));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                    if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');
                       continue;
                    }else{
                        emailLib::sendErrorInTelinta("Account Deletion: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account Deletion on telinta. This is the error for cusotmer with Id: " . $telintaAccount->getIAccount() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                        return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Account Deletion: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account Deletion on telinta. This is the error for cusotmer with Id: " . $telintaAccount->getIAccount() . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        $telintaAccount->setStatus(5);
        $telintaAccount->save();
        return true;
    }

    public function getBalance(Customer $customer) {
        $cInfo = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');


        while (!$cInfo && $retry_count < $max_retries) {
            try {
                $cInfo = $pb->get_customer_info(array(
                            'i_customer' => $customer->getICustomer(),
                        ));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                    if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');
                       continue;
                    }else{
                      emailLib::sendErrorInTelinta("Error in Get Balance", "We have faced an issue on Success in Get Balnace on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                      return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Error in Get Balance", "We have faced an issue on Success in Get Balnace on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        $Balance = $cInfo->customer_info->balance;
        if ($Balance == 0)
            return 0.001;
        else
            return -1 * $Balance;
    }


    public function charge(Customer $customer, $amount, $description="Refill") {
        return $this->makeTransaction($customer, "Manual charge", $amount, $description);

    }

    public function recharge(Customer $customer, $amount, $description="Recharge") {

        $c = new Criteria;
        $c->add(EmailAlertSentPeer::USAGE_ALERT_STATUS_ID, null, Criteria::ISNOTNULL);
        $c->addAnd(EmailAlertSentPeer::CUSTOMER_ID,$customer->getId());
        $emailAlertCount = EmailAlertSentPeer::doCount($c);
        if($emailAlertCount>0){
           $emailAlerts =  EmailAlertSentPeer::doSelect($c);
           foreach($emailAlerts as $emailAlert){
               $emailAlert->setUsageAlertStatusId(null);
               $emailAlert->save();
           }
        }

        $c = new Criteria;
        $c->add(SmsAlertSentPeer::USAGE_ALERT_STATUS_ID, null, Criteria::ISNOTNULL);
        $c->addAnd(SmsAlertSentPeer::CUSTOMER_ID,$customer->getId());
        $smsAlertCount = SmsAlertSentPeer::doCount($c);
        if($smsAlertCount>0){
           $smsAlerts =  SmsAlertSentPeer::doSelect($c);
           foreach($smsAlerts as $smsAlert){
               $smsAlert->setUsageAlertStatusId(null);
               $smsAlert->save();
           }
        }

        return $this->makeTransaction($customer, "Manual payment", $amount, $description);

    }


    public function callHistory($customer, $fromDate, $toDate, $reseller=false,$iService=3) {
        $xdrList = false;
        $max_retries = 10;
        $retry_count = 0;
        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');

        if ($reseller)
            $icustomer = $customer;
        else
            $icustomer = $customer->getICustomer();
        while (!$xdrList && $retry_count < $max_retries) {
            try {
                $xdrList = $pb->get_customer_xdr_list(array('i_customer' => $icustomer, 'from_date' => $fromDate, 'to_date' => $toDate, 'i_service' => $iService));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                    if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');
                       continue;
                    }else{

                        emailLib::sendErrorInTelinta("Customer Call History: " . $icustomer . " Error!", "We have faced an issue with Customer while Fetching Call History. This is the error for cusotmer with ICustomer: " . $icustomer . " and the i_service is: ".$iService." and error is " . $e->faultstring . "  <br/> Please Investigate.");
                        return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;

        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Customer Call History: " . $icustomer . " Error!", "We have faced an issue with Customer while Fetching Call History on telinta. This is the error for cusotmer with ICustomer: " . $icustomer . " and the i_service is: ".$iService." and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        return $xdrList;
    }



    public function getCustomerInfo($uniqueId) {
        $cInfo = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');

        while (!$accounts && $retry_count < $max_retries) {
            try {
                $cInfo = $pb->get_customer_info(array(
                            'name' => $uniqueId,
                        ));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                    if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');
                       continue;
                    }else{
                        emailLib::sendErrorInTelinta("unique id " . $uniqueId . " Error!", "We have faced an issue with Customer while getting info. This is the error for cusotmer with Customer unique id: " . $uniqueId . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                        return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("unique id " . $uniqueId . " Error!", "We have faced an issue with Customer while getting info. This is the error for cusotmer with Customer unique id: " . $uniqueId . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        $i_customer = $cInfo->customer_info->i_customer;
 
        return $i_customer;
    }

    public function getCustomerAccountList($iCustomer) {
        $cInfo = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');

        try {
            $cInfo = $pb->get_account_list(array(
                        'i_customer' => $iCustomer,
                        'offset' => 0,
                        'limit' => 100
                    ));
        } catch (SoapFault $e) {
            #emailLib::sendErrorInTelinta("Error in Customer Registration", "We have faced an issue in Customer registration on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");
            die($e->faultstring);

            return false;
        }

        return $cInfo;
    }

    //// Private Area.

    private function createAccount(Customer $customer, $mobileNumber, $accountType, $iProduct, $followMeEnabled='N') {
        $account = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');

        $accountName = $accountType . $mobileNumber;
        while (!$account && $retry_count < $max_retries) {
            try {

                $account = $pb->add_account(array('account_info' => array(
                                'i_customer' => $customer->getICustomer(),
                                'name' => $accountName, //75583 03344090514
                                'id' => $accountName,
                                'iso_4217' => $this->currency,
                                'opening_balance' => 0,
                                'credit_limit' => null,
                                'i_product' => $iProduct,
                                'i_routing_plan' => 2034,
                                'billing_model' => 1,
                                'password' => 'asdf1asd',
                                'h323_password' => 'asdf1asd',
                                'activation_date' => date('Y-m-d'),
                                'batch_name' => "LNB2C".$customer->getId().$customer->getUniqueid(),
                                'follow_me_enabled' => $followMeEnabled
                                )));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                   if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Account');
                       continue;
                    }else{ 
                        emailLib::sendErrorInTelinta("Account Creation: " . $accountName . " Error!", "We have faced an issue in Customer Account Creation on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and on Account" . $accountName . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                        return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Account Creation: " . $accountName . " Error!", "We have faced an issue in Customer Account Creation on telinta. This is the error for cusotmer with Id: " . $customer->getId() . " and on Account" . $accountName . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        $telintaAccount = new TelintaAccounts();
        $telintaAccount->setAccountTitle($accountName);
        $telintaAccount->setParentId($customer->getId());
        $telintaAccount->setParentTable("customer");
        $telintaAccount->setICustomer($customer->getICustomer());
        $telintaAccount->setIAccount($account->i_account);
        $telintaAccount->save();
        return true;
    }

    private function makeTransaction(Customer $customer, $action, $amount,$description) {
        $accounts = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');

        while (!$accounts && $retry_count < $max_retries) {
            try {
                $accounts = $pb->make_transaction(array(
                            'i_customer' => $customer->getICustomer(),
                            'action' => $action, //Manual payment, Manual charge
                            'amount' => $amount,
                            'visible_comment' => $description
                        ));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error' && $e->faultstring != 'Bad Gateway') {
                    if($e->faultstring=="Authentification failed"){
                       emailLib::sendErrorInTelinta("Authentification failed","Authentification failed on telinta");
                       $this->startNewSession();
                       ///// after starting new session, new object must initialize for PortaBillingSoapCient
                       $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');
                       continue;
                    }else{
                        emailLib::sendErrorInTelinta("Customer Transcation: " . $customer->getId() . " Error!", "We have faced an issue with Customer while making transaction " . $action . " with balance:" . $amount . ". This is the error for cusotmer with Customer ID: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");

                        return false;
                    }
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Customer Transcation: " . $customer->getId() . " Error!", "We have faced an issue with Customer while making transaction " . $action . " with balance:" . $amount . ". This is the error for cusotmer with Customer ID: " . $customer->getId() . " and error is " . $e->faultstring . ". Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        return true;
    }
    
    public function startNewSession(){
        $pb = new PortaBillingSoapClient($this->telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login($this->telinta_soap_user, $this->telinta_soap_password);

        if ($session) {
            $ctc = new Criteria();
            $countTC = TelintaConfigPeer::doCount($ctc);
            if($countTC==0){
                $telintaConfig = new TelintaConfig();
                $telintaConfig->setSession($session);
                $telintaConfig->save();
            }else{
               $telintaConfig = TelintaConfigPeer::doSelectOne($ctc); 
               $telintaConfig->setSession($session);
               $telintaConfig->save();
            }
            emailLib::sendErrorInTelinta("New Session started","New session generated for telinta. session id: ".$session);
            return true;
        }
    }
}

?>
