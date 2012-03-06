<?php

require_once(sfConfig::get('sf_lib_dir') . '/changeLanguageCulture.php');
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');
require_once(sfConfig::get('sf_lib_dir') . '/commissionLib.php');
require_once(sfConfig::get('sf_lib_dir') . '/smsCharacterReplacement.php');
require_once(sfConfig::get('sf_lib_dir').'/ForumTel.php');
require_once(sfConfig::get('sf_lib_dir').'/CurrencyConverter.class.php');

/**
 * payments actions.
 *
 * @package    zapnacrm
 * @subpackage payments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.6 2010-09-19 18:53:06 orehman Exp $
 */
class paymentsActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    private function getTargetUrl() {
        return sfConfig::get('app_main_url');
    }
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeThankyou(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11
        changeLanguageCulture::languageCulture($request, $this);

        $urlval = "thanks-" . $request->getParameter('transact');

        $email2 = new DibsCall();
        $email2->setCallurl($urlval);

        $email2->save();
    }

    public function executeReject(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);
        //get the order_id
        $order_id = $request->getParameter('orderid');
        //$error_text = substr($request->getParameter('errortext'), 0, strpos($request->getParameter('errortext'), '!'));
        $error_text = $this->getContext()->getI18N()->__('Payment is unfortunately not accepted because your information is incorrect, please try again by entering correct credit card information');

        $this->forward404Unless($order_id);

        $order = CustomerOrderPeer::retrieveByPK($order_id);
        $c = new Criteria();
        $c->add(TransactionPeer::ORDER_ID, $order_id);
        $transaction = TransactionPeer::doSelectOne($c);

        $this->forward404Unless($order);

        $order->setOrderStatusId(4); //cancelled

        $this->getUser()->setFlash('error_message',
                $error_text
        );

        $this->order = $order;
        $this->forward404Unless($this->order);

        $this->order_id = $order->getId();
        $this->amount = $transaction->getAmount();
        $this->form = new PaymentForm();

        $this->setTemplate('signup');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $product_id = $this->getUser()->getAttribute('product_id', '', 'usersignup');
            $customer_id = $this->getUser()->getAttribute('customer_id', '', 'usersignup');

            if ($product_id == '' || $customer_id == '') {
                $this->forward404('Product or customer id not found in session');
            }

            $order = new Order();
            $transaction = new Transaction();
            $product = ProductPeer::retrieveByPK($product_id);

            $order->setProductId($product_id);
            $order->setCustomerId($customer_id);
            $order->setExtraRefill($form->getValue('extra_refill'));
            $order->setIsFirstOrder(1);

            $order->save();

            $transaction->setAmount($product->getPrice() + $order->getExtraRefill());
            $transaction->setDescription('Product order');
            $transaction->setOrderId($order->getId());
            $transaction->setCustomerId($customer_id);
            //$transaction->setTransactionStatusId() // default value 1

            $transaction->save();

            $this->processTransaction($form->getValues(), $transaction, $request);

            $this->redirect('@signup_complete');
        }
    }

    public function executeSignup(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11
        changeLanguageCulture::languageCulture($request, $this);

        //$this->getUser()->setCulture('en');
        //$getCultue = $this->getUser()->getCulture();
        // Store data in the user session
        //$this->getUser()->setAttribute('activelanguage', $getCultue);

        $this->form = new PaymentForm();


        $product_id = $request->getParameter('pid');
        $customer_id = $request->getParameter('cid');

        $this->getUser()->setAttribute('product_ids', $product_id);
        $this->getUser()->setAttribute('cusid', $customer_id);

        if ($product_id == '' || $customer_id == '') {
            $this->forward404('Product id not found in session');
        }

        $order = new CustomerOrder();
        $transaction = new Transaction();

        $order->setProductId($product_id);
        $order->setCustomerId($customer_id);
        $order->setExtraRefill($order->getProduct()->getInitialBalance());

        //$extra_refil_choices = ProductPeer::getRefillChoices();
        //TODO: restrict quantity to be 1
        $order->setQuantity(1);

        //$order->setExtraRefill($extra_refil_choices[0]);//minumum refill amount
        $order->setIsFirstOrder(1);

        $order->save();

        $transaction->setAmount($order->getProduct()->getPrice() - $order->getProduct()->getInitialBalance() + $order->getExtraRefill());
        //TODO: $transaction->setAmount($order->getProduct()->getPrice());
        $transaction->setDescription($this->getContext()->getI18N()->__('Registrering inkl. taletid'));
        $transaction->setOrderId($order->getId());
        $transaction->setCustomerId($customer_id);
        //$transaction->setTransactionStatusId() // default value 1

        $transaction->save();

        $this->order = $order;
        $this->forward404Unless($this->order);

        $this->order_id = $order->getId();
        $this->amount = $transaction->getAmount();
    }

    protected function processTransaction($creditcardinfo = null, Transaction $transactionObj = null, sfWebRequest $request
    ) {

        $relay_script_url = 'https://relay.ditonlinebetalingssystem.dk/relay/v2/relay.cgi/';

        $transactionInfo = array(
            'cardno' => $creditcardinfo['cardno'],
            'expmonth' => $creditcardinfo['expmonth'],
            'expyear' => $creditcardinfo['expyear'],
            'cvc' => $creditcardinfo['cvc'],
            'merchantnumber' => sfConfig::get('app_epay_merchant_number'),
            'currency' => sfConfig::get('app_epay_currency'),
            'instantCapture' => sfConfig::get('app_epay_instant_capture'),
            'authemail' => sfConfig::get('app_epay_authemail'),
            'orderid' => $transactionObj->getOrderId(),
            'amount' => $transactionObj->getAmount(),
            'accepturl' => $relay_script_url . $this->getController()->genUrl('@epay_accept_url'),
            'declineurl' => $relay_script_url . $this->getController()->genUrl('@epay_reject_url'),
        );
    }

    public function executeShowReceipt(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request, $this);

        //is authenticated
        $this->customer = CustomerPeer::retrieveByPK(
                        $this->getUser()->getAttribute('customer_id', null, 'usersession')
        );

        $this->redirectUnless($this->customer, '@customer_login');
        //check to see if transaction id is there

        $transaction_id = $request->getParameter('tid');

        $this->forward404Unless($transaction_id);

        //is this receipt really belongs to authenticated user

        $transaction = TransactionPeer::retrieveByPK($transaction_id);

        $this->forward404Unless($transaction->getCustomerId() == $this->customer->getId(), 'Not allowed');

        //set customer order
        $customer_order = CustomerOrderPeer::retrieveByPK($transaction->getOrderId());

        if ($customer_order) {
            $vat = $customer_order->getIsFirstOrder() ?
                    ($customer_order->getProduct()->getPrice() * $customer_order->getQuantity() -
                    $customer_order->getProduct()->getInitialBalance()) * .20 :
                    0;
        }
        else
            die('Error retreiving');


        $this->renderPartial('payments/order_receipt', array(
            'customer' => $this->customer,
            'order' => CustomerOrderPeer::retrieveByPK($transaction->getOrderId()),
            'transaction' => $transaction,
            'vat' => $vat,
        ));

        return sfView::NONE;
    }

    public function executeConfirmpayment(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);
        $urlval = $request->getParameter('transact');
        $email2 = new DibsCall();
        $email2->setCallurl($urlval);
        $email2->save();
        $dibs = new DibsCall();
        $dibs->setCallurl("Ticket Number:".$request->getParameter('ticket'));
        $dibs->save();
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        //print_r($_REQUEST);
        // Store data in the user session
        //$this->getUser()->setAttribute('activelanguage', $getCultue);
        ////load the thankSuccess template

        if ($request->getParameter('transact') != '') {

            $this->logMessage(print_r($_GET, true));

            $is_transaction_ok = false;
            $subscription_id = '';
            $order_id = "";
            $order_amount = "";
            //get the order_id from the session
            //change the status of that order to complete,
            //change the customer status to compete too
            $order_id = $request->getParameter('orderid');
            $ticket_id = $request->getParameter('ticket');
            // echo $order_id.'<br />';
            $subscription_id = $request->getParameter('subscriptionid');
            $this->logMessage('sub id: ' . $subscription_id);
            $order_amount = $request->getParameter('amount') / 100;

            $this->forward404Unless($order_id || $order_amount);

            //get order object
            $order = CustomerOrderPeer::retrieveByPK($order_id);


            if (isset($ticket_id) && $ticket_id != "") {

                $subscriptionvalue = 0;

                $subscriptionvalue = $request->getParameter('subscriptionid');


                if (isset($subscriptionvalue) && $subscriptionvalue > 1) {
//  echo 'is autorefill activated';
                    //auto_refill_amount
                    $auto_refill_amount_choices = array_keys(ProductPeer::getRefillHashChoices());

                    $auto_refill_amount = in_array($request->getParameter('user_attr_2'), $auto_refill_amount_choices) ? $request->getParameter('user_attr_2') : $auto_refill_amount_choices[0];
                    $order->getCustomer()->setAutoRefillAmount($auto_refill_amount);


                    //auto_refill_lower_limit
                    $auto_refill_lower_limit_choices = array_keys(ProductPeer::getAutoRefillLowerLimitHashChoices());

                    $auto_refill_min_balance = in_array($request->getParameter('user_attr_3'), $auto_refill_lower_limit_choices) ? $request->getParameter('user_attr_3') : $auto_refill_lower_limit_choices[0];
                    $order->getCustomer()->setAutoRefillMinBalance($auto_refill_min_balance);

                    $order->getCustomer()->setTicketval($ticket_id);
                    $order->save();
                    $auto_refill_amount = "refill amount" . $auto_refill_amount;
                    $email2d = new DibsCall();
                    $email2d->setCallurl($auto_refill_amount);
                    $email2d->save();
                    $minbalance = "min balance" . $auto_refill_min_balance;
                    $email2dm = new DibsCall();
                    $email2dm->setCallurl($minbalance);
                    $email2dm->save();
                }
            }
            //check to see if that customer has already purchased this product
            $c = new Criteria();
            $c->add(CustomerProductPeer::CUSTOMER_ID, $order->getCustomerId());
            $c->addAnd(CustomerProductPeer::PRODUCT_ID, $order->getProductId());
            $c->addJoin(CustomerProductPeer::CUSTOMER_ID, CustomerPeer::ID);
            $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, sfConfig::get('app_status_new'), Criteria::NOT_EQUAL);

            // echo 'retrieve order id: '.$order->getId().'<br />';

            if (CustomerProductPeer::doCount($c) != 0) {
                echo 'Customer is already registered.';
                //exit the script successfully
                return sfView::NONE;
            }

            //set subscription id
            //$order->getCustomer()->setSubscriptionId($subscription_id);
            //set auto_refill amount
            //if order is already completed > 404
            $this->forward404Unless($order->getOrderStatusId() != sfConfig::get('app_status_completed'));
            $this->forward404Unless($order);

            //  echo 'processing order <br />';

            $c = new Criteria;
            $c->add(TransactionPeer::ORDER_ID, $order_id);
            $transaction = TransactionPeer::doSelectOne($c);

            //  echo 'retrieved transaction<br />';

            if ($transaction->getAmount() > $order_amount || $transaction->getAmount() < $order_amount) {
                //error
                $order->setOrderStatusId(sfConfig::get('app_status_error')); //error in amount
                $transaction->setTransactionStatusId(sfConfig::get('app_status_error')); //error in amount
                $order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_error')); //error in amount
                echo 'setting error <br /> ';
            } else {
                //TODO: remove it
                $transaction->setAmount($order_amount);

                $order->setOrderStatusId(sfConfig::get('app_status_completed')); //completed
                $order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed')); //completed
                $transaction->setTransactionStatusId(3); //completed
                // echo 'transaction=ok <br /> ';
                $is_transaction_ok = true;
            }


            $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();

            $product_price_vat = .20 * $product_price;

            $order->setQuantity(1);
            // $order->getCustomer()->getAgentCompany();
            //set active agent_package in case customer
            if ($order->getCustomer()->getAgentCompany()) {
                $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
                $transaction->setAgentCompanyId($order->getCustomer()->getReferrerId()); //completed
            }





            $order->save();
            $transaction->save();
            if ($is_transaction_ok) {

                // echo 'Assigning Customer ID <br/>';
                //set customer's proudcts in use
                $customer_product = new CustomerProduct();

                $customer_product->setCustomer($order->getCustomer());
                $customer_product->setProduct($order->getProduct());

                $customer_product->save();

                //register to fonet
                $this->customer = $order->getCustomer();

                //Fonet::registerFonet($this->customer);
                //recharge the extra_refill/initial balance of the prouduct
                //Fonet::recharge($this->customer, $order->getExtraRefill());

                $cc = new Criteria();
                $cc->add(EnableCountryPeer::ID, $this->customer->getCountryId());
                $country = EnableCountryPeer::doSelectOne($cc);

                $mobile = $country->getCallingCode() . $this->customer->getMobileNumber();

                $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0, 1);     // bcdef
                if ($getFirstnumberofMobile == 0) {
                    $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                    $TelintaMobile = '46' . $TelintaMobile;
                } else {
                    $TelintaMobile = '46' . $this->customer->getMobileNumber();
                }


                $uniqueId = $this->customer->getUniqueid();
                echo $uniqueId."<br/>";
                $uc = new Criteria();
                $uc->add(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueId);
                $selectedUniqueId = UniqueIdsPeer::doSelectOne($uc);
                echo $selectedUniqueId->getStatus()."<br/>Baran";

                if($selectedUniqueId->getStatus()==0){
                    echo "inside";
                    $selectedUniqueId->setStatus(1);
                    $selectedUniqueId->setAssignedAt(date('Y-m-d H:i:s'));
                    $selectedUniqueId->save();
                    }else{
                        $uc = new Criteria();
                        $uc->add(UniqueIdsPeer::REGISTRATION_TYPE_ID, 1);
                        $uc->addAnd(UniqueIdsPeer::STATUS, 0);
                        $availableUniqueCount = UniqueIdsPeer::doCount($uc);
                        $availableUniqueId = UniqueIdsPeer::doSelectOne($uc);

                        if($availableUniqueCount  == 0){
                            // Unique Ids are not avaialable. Then Redirect to the sorry page and send email to the support.
                            emailLib::sendUniqueIdsShortage();
                            $this->redirect($this->getTargetUrl() .'customer/shortUniqueIds');
                            //$this->redirect('http://landncall.zerocall.com/b2c.php/customer/shortUniqueIds');
                        }
                        $uniqueId = $availableUniqueId->getUniqueNumber();
                        $this->customer->setUniqueid($uniqueId);
                        $this->customer->save();
                        $availableUniqueId->setStatus(1);
                        $availableUniqueId->setAssignedAt(date('Y-m-d H:i:s'));
                        $availableUniqueId->save();
                }


             $callbacklog = new CallbackLog();
                $callbacklog->setMobileNumber($TelintaMobile);
                $callbacklog->setuniqueId($uniqueId);
                $callbacklog->setCheckStatus(3);
                $callbacklog->save();
                $emailId = $this->customer->getEmail();
                $OpeningBalance = $order->getExtraRefill();
                $customerPassword = $this->customer->getPlainText();

                //Section For Telinta Add Cusomter

                Telienta::ResgiterCustomer($this->customer, $OpeningBalance);
                Telienta::createAAccount($TelintaMobile, $this->customer);
                Telienta::createCBAccount($TelintaMobile, $this->customer);




                //if the customer is invited, Give the invited customer a bonus of 10dkk
                $invite_c = new Criteria();
                $invite_c->add(InvitePeer::INVITE_NUMBER, $this->customer->getMobileNumber());
                $invite_c->add(InvitePeer::INVITE_STATUS, 2);
                $invite = InvitePeer::doSelectOne($invite_c);

                //echo $this->customer->getMobileNumber().'Yess<br>';
                // e//cho $invite->getInviteNumber();
                if ($invite) {

//print_r($this->customer);
                    //echo $this->customer->getMobileNumber().'AAA';
//                        $invite2 = "assigning bonuss \r\n";
//			// echo " assigning bonuss <br />";
//                         $invite_data_file=sfConfig::get('sf_data_dir').'/invite.txt';
//			file_put_contents($invite_data_file, $invite2, FILE_APPEND);




                    $invite->setInviteStatus(3);

                    $sc = new Criteria();
                    $sc->add(CustomerCommisionPeer::ID, 1);
                    $commisionary = CustomerCommisionPeer::doSelectOne($sc);
                    $comsion = $commisionary->getCommision();



                    $products = new Criteria();
                    $products->add(ProductPeer::ID, 11);
                    $products = ProductPeer::doSelectOne($products);
                    $extrarefill = $products->getInitialBalance();
                    //if the customer is invited, Give the invited customer a bonus of 10dkk

                    $inviteOrder = new CustomerOrder();
                    $inviteOrder->setProductId(11);
                    $inviteOrder->setQuantity(1);
                    $inviteOrder->setOrderStatusId(3);
                    $inviteOrder->setCustomerId($invite->getCustomerId());
                    $inviteOrder->setExtraRefill($extrarefill);
                    $inviteOrder->save();
                    $OrderId = $inviteOrder->getId();
                    // make a new transaction to show in payment history
                    $transaction_i = new Transaction();
                    $transaction_i->setAmount($comsion);
                    $transaction_i->setDescription("Invitation Bonus for Mobile Number: " . $invite->getInviteNumber());
                    $transaction_i->setCustomerId($invite->getCustomerId());
                    $transaction_i->setOrderId($OrderId);
                    $transaction_i->setTransactionStatusId(3);

                    //send fonet query to update the balance of invitee by 10dkk
                    //   Fonet::recharge(CustomerPeer::retrieveByPK($invite->getCustomerId()), $comsion);

                    $this->customers = CustomerPeer::retrieveByPK($invite->getCustomerId());

                    //send Telinta query to update the balance of invite by 10dkk
                    $getFirstnumberofMobile = substr($this->customers->getMobileNumber(), 0, 1);     // bcdef
                    if ($getFirstnumberofMobile == 0) {
                        $TelintaMobile = substr($this->customers->getMobileNumber(), 1);
                        $TelintaMobile = '46' . $TelintaMobile;
                    } else {
                        $TelintaMobile = '46' . $this->customers->getMobileNumber();
                    }
                    $uniqueId = $this->customers->getUniqueid();
                    $OpeningBalance = $comsion;
                    //This is for Recharge the Customer
                    Telienta::recharge($this->customers, $OpeningBalance);
                    //This is for Recharge the Account
                    //this condition for if follow me is Active
                    $getvoipInfo = new Criteria();
                    $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customers->getMobileNumber());
                    $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                    if (isset($getvoipInfos)) {
                        $voipnumbers = $getvoipInfos->getNumber();
                        $voip_customer = $getvoipInfos->getCustomerId();
                       
                    } else {
                        
                    }

            
                    //save transaction & Invite
                    $transaction_i->save();
                    $invite->save();
//                $invite2 .= "transaction & invite saved  \r\n";
//                file_put_contents($invite_data_file, $invite2, FILE_APPEND);
                    $invitevar = $invite->getCustomerId();
                    if (isset($invitevar)) {
                        emailLib::sendCustomerConfirmRegistrationEmail($invite->getCustomerId());
                    }
                }
                //send email

                $message_body = $this->getPartial('payments/order_receipt', array(
                            'customer' => $this->customer,
                            'order' => $order,
                            'transaction' => $transaction,
                            'vat' => $product_price_vat,
                            'wrap' => true
                        ));

                $subject = $this->getContext()->getI18N()->__('Payment Confirmation');
                $sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
                $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

                $recepient_email = trim($this->customer->getEmail());
                $recepient_name = sprintf('%s %s', $this->customer->getFirstName(), $this->customer->getLastName());


                //This Seciton For Make The Log History When Complete registration complete - Agent
                //echo sfConfig::get('sf_data_dir');
                //Send Email --- when Confirm Payment --- 01/15/11

                $agentid = $this->customer->getReferrerId();

                $cp = new Criteria;
                $cp->add(CustomerProductPeer::CUSTOMER_ID, $order->getCustomerId());
                $customerproduct = CustomerProductPeer::doSelectOne($cp);
                $productid = $customerproduct->getId();

                $transactionid = $transaction->getId();
                if (isset($agentid) && $agentid != "") {
                    commissionLib::registrationCommissionCustomer($agentid, $productid, $transactionid);
                }
                //emailLib::sendCustomerConfirmPaymentEmail($this->customer,$message_body);
                emailLib::sendCustomerRegistrationViaWebEmail($this->customer, $order);


                $this->order = $order;
            }//end if
            else {
                $this->logMessage('Error in transaction.');
            } //end else
            //return sfView::NONE;
        }
    }

    public function executeCtpay(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);
        $urlval = $request->getParameter('transact');
        $email2 = new DibsCall();
        $email2->setCallurl($urlval);
        $email2->save();
    }



    public function executeConfirmpaymentus(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);
        $urlval = $request->getParameter('transact');
        $email2 = new DibsCall();
        $email2->setCallurl($urlval);
        $email2->save();
        $dibs = new DibsCall();
        $dibs->setCallurl("Ticket Number:".$request->getParameter('ticket')."-ord-".$request->getParameter('orderid')."-amt-".$request->getParameter('amount'));
        $dibs->save();
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        //print_r($_REQUEST);
        // Store data in the user session
        //$this->getUser()->setAttribute('activelanguage', $getCultue);
        ////load the thankSuccess template

        if ($request->getParameter('transact') != '') {

            $this->logMessage(print_r($_GET, true));

            $is_transaction_ok = false;
            $subscription_id = '';
            $order_id = "";
            $order_amount = "";
            //get the order_id from the session
            //change the status of that order to complete,
            //change the customer status to compete too
            $order_id = $request->getParameter('orderid');
            $ticket_id = $request->getParameter('ticket');
            // echo $order_id.'<br />';
            $subscription_id = $request->getParameter('subscriptionid');
            $this->logMessage('sub id: ' . $subscription_id);
            $order_amount = $request->getParameter('amount') / 100;

            $this->forward404Unless($order_id || $order_amount);

            //get order object
            $order = CustomerOrderPeer::retrieveByPK($order_id);


            if (isset($ticket_id) && $ticket_id != "") {

                $subscriptionvalue = 0;

                $subscriptionvalue = $request->getParameter('subscriptionid');


                if (isset($subscriptionvalue) && $subscriptionvalue > 1) {
//  echo 'is autorefill activated';
                    //auto_refill_amount
                    $auto_refill_amount_choices = array_keys(ProductPeer::getRefillHashChoices());

                    $auto_refill_amount = in_array($request->getParameter('user_attr_2'), $auto_refill_amount_choices) ? $request->getParameter('user_attr_2') : $auto_refill_amount_choices[0];
                    $order->getCustomer()->setAutoRefillAmount($auto_refill_amount);


                    //auto_refill_lower_limit
                    $auto_refill_lower_limit_choices = array_keys(ProductPeer::getAutoRefillLowerLimitHashChoices());

                    $auto_refill_min_balance = in_array($request->getParameter('user_attr_3'), $auto_refill_lower_limit_choices) ? $request->getParameter('user_attr_3') : $auto_refill_lower_limit_choices[0];
                    $order->getCustomer()->setAutoRefillMinBalance($auto_refill_min_balance);

                    $order->getCustomer()->setTicketval($ticket_id);
                    $order->save();
                    $auto_refill_amount = "refill amount" . $auto_refill_amount;
                    $email2d = new DibsCall();
                    $email2d->setCallurl($auto_refill_amount);
                    $email2d->save();
                    $minbalance = "min balance" . $auto_refill_min_balance;
                    $email2dm = new DibsCall();
                    $email2dm->setCallurl($minbalance);
                    $email2dm->save();
                }
            }
            //check to see if that customer has already purchased this product
            $c = new Criteria();
            $c->add(CustomerProductPeer::CUSTOMER_ID, $order->getCustomerId());
            $c->addAnd(CustomerProductPeer::PRODUCT_ID, $order->getProductId());
            $c->addJoin(CustomerProductPeer::CUSTOMER_ID, CustomerPeer::ID);
            $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, sfConfig::get('app_status_new'), Criteria::NOT_EQUAL);

            // echo 'retrieve order id: '.$order->getId().'<br />';

            if (CustomerProductPeer::doCount($c) != 0) {
                echo 'Customer is already registered.';
                //exit the script successfully
                return sfView::NONE;
            }

            //set subscription id
            //$order->getCustomer()->setSubscriptionId($subscription_id);
            //set auto_refill amount
            //if order is already completed > 404
            $this->forward404Unless($order->getOrderStatusId() != sfConfig::get('app_status_completed'));
            $this->forward404Unless($order);

            //  echo 'processing order <br />';

            $c = new Criteria;
            $c->add(TransactionPeer::ORDER_ID, $order_id);
            $transaction = TransactionPeer::doSelectOne($c);

            //  echo 'retrieved transaction<br />';

            if ($transaction->getAmount() > $order_amount || $transaction->getAmount() < $order_amount) {
                //error
                $order->setOrderStatusId(sfConfig::get('app_status_error')); //error in amount
                $transaction->setTransactionStatusId(sfConfig::get('app_status_error')); //error in amount
                $order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_error')); //error in amount
                echo 'setting error <br /> ';
            } else {
                //TODO: remove it
                $transaction->setAmount($order_amount);

                $order->setOrderStatusId(sfConfig::get('app_status_completed')); //completed
                $order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed')); //completed
                $transaction->setTransactionStatusId(3); //completed
                // echo 'transaction=ok <br /> ';
                $is_transaction_ok = true;
            }


            $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();

            $product_price_vat = .20 * $product_price;

            $order->setQuantity(1);
            // $order->getCustomer()->getAgentCompany();
            //set active agent_package in case customer
            if ($order->getCustomer()->getAgentCompany()) {
                $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
                $transaction->setAgentCompanyId($order->getCustomer()->getReferrerId()); //completed
            }





            $order->save();
            $transaction->save();
            if ($is_transaction_ok) {

                // echo 'Assigning Customer ID <br/>';
                //set customer's proudcts in use
                $customer_product = new CustomerProduct();

                $customer_product->setCustomer($order->getCustomer());
                $customer_product->setProduct($order->getProduct());

                $customer_product->save();

                //register to fonet
                $this->customer = $order->getCustomer();



                $cc = new Criteria();
                $cc->add(EnableCountryPeer::ID, $this->customer->getCountryId());
                $country = EnableCountryPeer::doSelectOne($cc);

                $mobile = $country->getCallingCode() . $this->customer->getMobileNumber();
                $uniqueId = $this->customer->getUniqueid();
                $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0, 1);     // bcdef
                if ($getFirstnumberofMobile == 0) {
                    $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                    $TelintaMobile = '46' . $TelintaMobile;
                } else {
                    $TelintaMobile = '46' . $this->customer->getMobileNumber();
                }

              $uniqueId = $this->customer->getUniqueid();
                echo $uniqueId."<br/>";
                $uc = new Criteria();
                $uc->add(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueId);
                $selectedUniqueId = UniqueIdsPeer::doSelectOne($uc);
                echo $selectedUniqueId->getStatus()."<br/>Baran";

                if($selectedUniqueId->getStatus()==0){
                    echo "inside";
                    $selectedUniqueId->setStatus(1);
                    $selectedUniqueId->setAssignedAt(date('Y-m-d H:i:s'));
                    $selectedUniqueId->save();
                    }else{
                        $uc = new Criteria();
                        $uc->add(UniqueIdsPeer::REGISTRATION_TYPE_ID, 3);
                        $uc->addAnd(UniqueIdsPeer::STATUS, 0);
                        $availableUniqueCount = UniqueIdsPeer::doCount($uc);
                        $availableUniqueId = UniqueIdsPeer::doSelectOne($uc);

                        if($availableUniqueCount  == 0){
                            // Unique Ids are not avaialable. Then Redirect to the sorry page and send email to the support.
                            emailLib::sendUniqueIdsShortage();
                            $this->redirect($this->getTargetUrl() .'customer/shortUniqueIds');
                            //$this->redirect('http://landncall.zerocall.com/b2c.php/customer/shortUniqueIds');
                        }
                        $uniqueId = $availableUniqueId->getUniqueNumber();
                        $this->customer->setUniqueid($uniqueId);
                        $this->customer->save();
                        $availableUniqueId->setStatus(1);
                        $availableUniqueId->setAssignedAt(date('Y-m-d H:i:s'));
                        $availableUniqueId->save();



                }

               $uniqueId=$this->customer->getUniqueid();
                 $callbacklog = new CallbackLog();
                $callbacklog->setMobileNumber($TelintaMobile);
                $callbacklog->setuniqueId($uniqueId);
                $callbacklog->setCheckStatus(3);
                $callbacklog->save();


                  $uc = new Criteria();
                $uc->add(UsNumberPeer::ACTIVE_STATUS, 1);
                $selectusnumber = UsNumberPeer::doSelectOne($uc);
                $selectusnumber->setActiveStatus(3);
                $selectusnumber->setCustomerId($this->customer->getId());
                $selectusnumber->save();

 $pakage=$order->getProduct()->getProductTypePackage();
               $unid= $this->customer->getUniqueid();



 $customerID=$this->customer->getId();
                $OpeningBalance=0;
                Telienta::ResgiterCustomer($this->customer, $OpeningBalance,null,true);
                $Tes=ForumTel::registerForumtel($customerID);
                ForumTel::getUsMobileNumber($customerID);
     //////////////////////////rese number registration ///////////////////////////////
                $rs = new Criteria();
                $rs->add(SeVoipNumberPeer::CUSTOMER_ID, $customerID);
                $rs->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 3);
                $voip_customer = '';
                if (SeVoipNumberPeer::doCount($rs) > 0) {
                    $voip_customer = SeVoipNumberPeer::doSelectOne($rs);
                } else {

                    $c = new Criteria();
                    $c->setLimit(1);
                    $c->add(SeVoipNumberPeer::IS_ASSIGNED, 0);
                    if (SeVoipNumberPeer::doCount($c) < 10) {
                        emailLib::sendErrorInTelinta("Resenumber about to Finis", "Resenumbers in the landncall are lest then 10 . ");
                    }
                    if (!$voip_customer = SeVoipNumberPeer::doSelectOne($c)) {
                        emailLib::sendErrorInTelinta("Resenumber Finished", "Resenumbers in the landncall are finished. This error is faced by customer id: " . $customerids);
                        return false;
                    }
                }
                // echo $voip_customer->getId()."Baran here<hr/>";
                $voip_customer->setUpdatedAt(date('Y-m-d H:i:s'));
                $voip_customer->setCustomerId($customerID);
                $voip_customer->setIsAssigned(1);
                $voip_customer->save();

                //  echo $voip_customer->getId()."Baran here<hr/>";
                // die;
                //--------------------------Telinta------------------/
                $getvoipInfo = new Criteria();
                $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customerID);
                $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                if (isset($getvoipInfos)) {
                    $voipnumbers = $getvoipInfos->getNumber();
                    $voipnumbers = substr($voipnumbers, 2);
                    $voip_customer = $getvoipInfos->getCustomerId();
                    
                    $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0, 1);     // bcdef
                    if ($getFirstnumberofMobile == 0) {
                        $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                        $TelintaMobile = '46' . $TelintaMobile;
                    } else {
                        $TelintaMobile = '46' . $this->customer->getMobileNumber();
                    }

                    //$TelintaMobile = '46'.$this->customer->getMobileNumber();
                    $emailId = $this->customer->getEmail();
                    $uniqueId = $this->customer->getUniqueid();

                    //This Condtion for if IC Active
                    $tc = new Criteria();
                    $tc->add(CallbackLogPeer::UNIQUEID, $uniqueId);
                    $tc->addDescendingOrderByColumn(CallbackLogPeer::CREATED);
                    $MaxUniqueRec = CallbackLogPeer::doSelectOne($tc);
                    if (isset($MaxUniqueRec)) {
                        $TelintaMobile = $MaxUniqueRec->getMobileNumber();
                    }
                    //------------------------------
                    $TelintaMobile=$selectusnumber->getUsMobileNumber();
                    Telienta::createReseNumberAccount($voipnumbers, $this->customer, $TelintaMobile);


                 //   $OpeningBalance = '40';

                    //type=<account_customer>&action=manual_charge&name=<name>&amount=<amount>
                    //This is for Recharge the Customer
                  //  Telienta::charge($this->customer, $OpeningBalance);
                }


///////////////////////////////////////////////////////end resenumber registration
               echo "original amout".$amt=$order->getExtraRefill();
                         echo "<hr/>converted amout". $amtt=CurrencyConverter::convertSekToUsd($amt);
                        
                 echo "<hr/>ft response". $Test=ForumTel::rechargeForumtel($customerID,$amtt);

                    $dibsf = new DibsCall();
        $dibsf->setCallurl("original amout SEK:".$amt."converted amout".$amtt."Fr response".$Test);
        $dibsf->save();

                $amt=$amtt;
        
                   $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerID);
        $usnumber = UsNumberPeer::doSelectOne($tc);
               $usnumber=$usnumber->getUsMobileNumber();

                        $sms_text ="Käre kund
Ditt USA mobil nummer är följande: (".$usnumber."), numret är aktiveras och du kan ringa från den när du har nått USA
";
                       $data = array(
                            'S' => 'H',
                            'UN' => 'zapna1',
                            'P' => 'Zapna2010',
                            'DA' => $usnumber,
                            'SA' => 'LandNCall',
                            'M' => $sms_text,
                            'ST' => '5'
                        );


                        $queryString = http_build_query($data, '', '&');
                        $queryString = smsCharacter::smsCharacterReplacement($queryString);
                        // echo $sms_text;
                        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?' . $queryString);



 






                //if the customer is invited, Give the invited customer a bonus of 10dkk
                $invite_c = new Criteria();
                $invite_c->add(InvitePeer::INVITE_NUMBER, $this->customer->getMobileNumber());
                $invite_c->add(InvitePeer::INVITE_STATUS, 2);
                $invite = InvitePeer::doSelectOne($invite_c);

                //echo $this->customer->getMobileNumber().'Yess<br>';
                // e//cho $invite->getInviteNumber();
                if ($invite) {

                    $invite->setInviteStatus(3);
                    $sc = new Criteria();
                    $sc->add(CustomerCommisionPeer::ID, 1);
                    $commisionary = CustomerCommisionPeer::doSelectOne($sc);
                    $comsion = $commisionary->getCommision();
                    $products = new Criteria();
                    $products->add(ProductPeer::ID, 11);
                    $products = ProductPeer::doSelectOne($products);
                    $extrarefill = $products->getInitialBalance();
                    //if the customer is invited, Give the invited customer a bonus of 10dkk
                    $inviteOrder = new CustomerOrder();
                    $inviteOrder->setProductId(11);
                    $inviteOrder->setQuantity(1);
                    $inviteOrder->setOrderStatusId(3);
                    $inviteOrder->setCustomerId($invite->getCustomerId());
                    $inviteOrder->setExtraRefill($extrarefill);
                    $inviteOrder->save();
                    $OrderId = $inviteOrder->getId();
                    // make a new transaction to show in payment history
                    $transaction_i = new Transaction();
                    $transaction_i->setAmount($comsion);
                    $transaction_i->setDescription("Invitation Bonus for Mobile Number: " . $invite->getInviteNumber());
                    $transaction_i->setCustomerId($invite->getCustomerId());
                    $transaction_i->setOrderId($OrderId);
                    $transaction_i->setTransactionStatusId(3);
                    //send fonet query to update the balance of invitee by 10dkk
                    //   Fonet::recharge(CustomerPeer::retrieveByPK($invite->getCustomerId()), $comsion);

                    $this->customers = CustomerPeer::retrieveByPK($invite->getCustomerId());

                    //send Telinta query to update the balance of invite by 10dkk
                    $getFirstnumberofMobile = substr($this->customers->getMobileNumber(), 0, 1);     // bcdef
                    if ($getFirstnumberofMobile == 0) {
                        $TelintaMobile = substr($this->customers->getMobileNumber(), 1);
                        $TelintaMobile = '46' . $TelintaMobile;
                    } else {
                        $TelintaMobile = '46' . $this->customers->getMobileNumber();
                    }
                    
                    $uniqueId = $this->customers->getUniqueid();
                    $OpeningBalance = $comsion;
                    //This is for Recharge the Customer

                  //  Telienta::recharge($this->customers, $OpeningBalance);
                    //This is for Recharge the Account
                    //this condition for if follow me is Active

                    $getvoipInfo = new Criteria();
                    $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customers->getMobileNumber());
                    $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                    if (isset($getvoipInfos)) {
                        $voipnumbers = $getvoipInfos->getNumber();
                        $voip_customer = $getvoipInfos->getCustomerId();
                    } else {


                     }

                    
                    $transaction_i->save();
                    $invite->save();

                    $invitevar = $invite->getCustomerId();
                    if (isset($invitevar)) {
                        emailLib::sendCustomerConfirmRegistrationEmail($invite->getCustomerId());
                    }
                }
                //send email
                $message_body = $this->getPartial('payments/order_receipt_us', array(
                            'customer' => $this->customer,
                            'order' => $order,
                            'transaction' => $transaction,
                            'vat' => $product_price_vat,
                            'wrap' => true
                        ));
                $subject = $this->getContext()->getI18N()->__('Payment Confirmation');
                $sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
                $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

                $recepient_email = trim($this->customer->getEmail());
                $recepient_name = sprintf('%s %s', $this->customer->getFirstName(), $this->customer->getLastName());
                //This Seciton For Make The Log History When Complete registration complete - Agent
                //echo sfConfig::get('sf_data_dir');
                //Send Email --- when Confirm Payment --- 01/15/11
                $agentid = $this->customer->getReferrerId();
                $cp = new Criteria;
                $cp->add(CustomerProductPeer::CUSTOMER_ID, $order->getCustomerId());
                $customerproduct = CustomerProductPeer::doSelectOne($cp);
                $productid = $customerproduct->getId();
                $transactionid = $transaction->getId();
                if (isset($agentid) && $agentid != "") {
                    commissionLib::registrationCommissionCustomer($agentid, $productid, $transactionid);
                }
                //emailLib::sendCustomerConfirmPaymentEmail($this->customer,$message_body);
                emailLib::sendCustomerRegistrationViaWebUSEmail($this->customer, $order);
                $this->order = $order;
            }//end if
            else {
                $this->logMessage('Error in transaction.');
            }
            //   //end else
        }
        return sfView::NONE;
    }

}
