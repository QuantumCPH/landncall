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

    private static $currency = 'SEK';
    private static $iParent = 59368;
    private static $a_iProduct = 7993;
    private static $cb_iProduct = 7992;
    private static $voip_iProduct = 7994;
    private static $telintaSOAPUrl = "https://mybilling.telinta.com";
    private static $telintaSOAPUser = 'API_login';
    private static $telintaSOAPPassword = 'ee4eriny';

    public static function ResgiterCustomer(Customer $customer, $OpeningBalance) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $tCustomer = $pb->add_customer(array('customer_info' => array(
                            'name' => $customer->getUniqueid(), //
                            'iso_4217' => self::$currency,
                            'i_parent' => self::$iParent,
                            'i_customer_type' => 1,
                            'opening_balance' => -($OpeningBalance),
                            'credit_limit' => 0,
                            'dialing_rules' => array('ip' => '00'),
                            'email' => 'okh@zapna.com'
                            )));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Error in Customer Registration", "We have faced an issue in Customer registration on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $customer->setICustomer($tCustomer->i_customer);
        $customer->save();
        $pb->_logout();
        return true;
    }

    public static function createAAccount($mobileNumber, Customer $customer) {
        return self::createAccount($customer, $mobileNumber, 'a', self::$a_iProduct);
    }

    public static function createCBAccount($mobileNumber, Customer $customer) {
        return self::createAccount($customer, $mobileNumber, 'cb', self::$cb_iProduct);
    }

    public static function createReseNumberAccount($VOIPNumber, Customer $customer, $currentActiveNumber) {

        if (self::createAccount($customer, $VOIPNumber, '', self::$voip_iProduct,'Y')) {
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
                emailLib::sendErrorInTelinta("Account update_account_followme: " . $accountName . " Error!", "We have faced an issue in Customer Account Deletion on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
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
    }

    public static function terminateAccount(TelintaAccounts $telintaAccount) {
        try {
            $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
            $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
            $account = $pb->terminate_account(array('i_account' => $telintaAccount->getIAccount()));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Account Deletion: " . $accountName . " Error!", "We have faced an issue in Customer Account Deletion on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $pb->_logout();
        $telintaAccount->setStatus(5);
        $telintaAccount->save();
        return true;
    }

    public static function getBalance(Customer $customer) {
        try {
            $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
            $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

            $cInfo = $pb->get_customer_info(array(
                        'name' => $customer->getUniqueid(),
                    ));
            $Balance = $cInfo->customer_info->balance;
            $pb->_logout();
        } catch (SoapFault $e) {
            //if ($Balance == "") {
                emailLib::sendErrorInTelinta("Error in getBalance", "We have faced an issue on Success in getBalnace on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                $pb->_logout();
                return false;
           // }
        }
        if ($Balance == 0)
            return $Balance;
        else
            return -1 * $Balance;
    }

    public static function charge(Customer $customer, $amount) {
        return self::makeTransaction($customer, "Manual charge", $amount);
    }

    public static function recharge(Customer $customer, $amount) {
        return self::makeTransaction($customer, "Manual payment", $amount);
    }

    public static function callHistory(Customer $customer, $fromDate, $toDate) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $xdrList = $pb->get_customer_xdr_list(array('i_customer' => $customer->getICustomer(), 'from_date' => $fromDate, 'to_date' => $toDate, 'i_service' => 3));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Customer Call History: " . $customer->getId() . " Error!", "We have faced an issue with Customer while Fetching Call History  this is the error for cusotmer with  Customer ID: " . $customer->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
        }
        $pb->_logout();
        return $xdrList;
    }

    public static function getCustomerInfo($uniqueId) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

        $cInfo = $pb->get_customer_info(array(
                    'name' => $uniqueId,
                ));
        $i_customer = $cInfo->customer_info->i_customer;
        $pb->_logout();
        return $i_customer;
    }

    public static function getCustomerAccountList($iCustomer) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $cInfo = $pb->get_account_list(array(
                        'i_customer' => $iCustomer,
                        'offset' => 0,
                        'limit' => 100
                    ));
        } catch (SoapFault $e) {
            #emailLib::sendErrorInTelinta("Error in Customer Registration", "We have faced an issue in Customer registration on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");
            die($e->faultstring);
            $pb->_logout();
            return false;
        }
        $pb->_logout();
        return $cInfo;
    }

    //// Private Area.

    private static function createAccount(Customer $customer, $mobileNumber, $accountType, $iProduct, $followMeEnabled='N') {

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);

        try {
            $accountName = $accountType . $mobileNumber;
            $account = $pb->add_account(array('account_info' => array(
                            'i_customer' => $customer->getICustomer(),
                            'name' => $accountName, //75583 03344090514
                            'id' => $accountName,
                            'iso_4217' => self::$currency,
                            'opening_balance' => 0,
                            'credit_limit' => null,
                            'i_product' => $iProduct,
                            'i_routing_plan' => 2034,
                            'billing_model' => 1,
                            'password' => 'asdf1asd',
                            'h323_password' => 'asdf1asd',
                            'activation_date' => date('Y-m-d'),
                            'batch_name' => $customer->getUniqueid(),
                            'follow_me_enabled' => $followMeEnabled
                            )));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Account Creation: " . $accountName . " Error!", "We have faced an issue in Customer Account Creation on telinta. this is the error for cusotmer with  id: " . $customer->getId() . " and on Account" . $accountName . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
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

    private static function makeTransaction(Customer $customer, $action, $amount) {
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        $session = $pb->_login(self::$telintaSOAPUser, self::$telintaSOAPPassword);
        try {
            $accounts = $pb->make_transaction(array(
                        'i_customer' => $customer->getICustomer(),
                        'action' => $action, //Manual payment, Manual charge
                        'amount' => $amount,
                        'visible_comment' => 'charge by SOAP ' . $action
                    ));
        } catch (SoapFault $e) {
            emailLib::sendErrorInTelinta("Customer Transcation: " . $customer->getId() . " Error!", "We have faced an issue with Customer while making transaction " . $action . " this is the error for cusotmer with  Customer ID: " . $customer->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
            $pb->_logout();
            return false;
        }
        $pb->_logout();
        return true;
    }

}

?>
