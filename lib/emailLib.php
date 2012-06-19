<?php

class emailLib {

//rs@zapna.com    to    jan.larsson@landncall.com 
//asd@landncall.com  to    okhan@zapna.com
    public static function sendAgentRefilEmail(AgentCompany $agent, $agent_order) {
        $vat = 0;

        //create transaction
        //This Section For Get The Agent Information
        $agent_company_id = $agent->getId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }

        //$this->renderPartial('affiliate/order_receipt', array(
        $agentamount = $agent_order->getAmount();
        $createddate = $agent_order->getCreatedAt('m-d-Y');
        $agentid = $agent_order->getAgentOrderId();
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('affiliate/agent_order_receipt', array(
                    'order' => $agentid,
                    'transaction' => $agentamount,
                    'createddate' => $createddate,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));


        $subject = __('Agent Payment Confirmation');


        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');

        //------------------Sent The Email To Customer
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setEmailType('LandNCall AB refill via agent');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($agent_company_id);
            $email3->setEmailType('LandNCall AB refill via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To cdu
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('LandNCall AB refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    public static function sendRetailRefillEmail(Customer $customer, $order) {
        $vat = 0;

        $tc = new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);

        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt_sms', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));

        $subject = __('Payment Confirmation');
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');
        //---------------------------------------
        //--------------Sent The Email To Support

        $email3 = new EmailQueue();
        $email3->setSubject($subject);
        $email3->setReceipientName($sender_name);
        $email3->setReceipientEmail($sender_email);
        $email3->setEmailType('Retail Refil');
        $email3->setMessage($message_body);
        $email3->save();

        $email3 = new EmailQueue();
        $email3->setSubject($subject);
        $email3->setReceipientName($sender_namecdu);
        $email3->setReceipientEmail($sender_emailcdu);
        $email3->setEmailType('Retail Refil');
        $email3->setMessage($message_body);
        $email3->save();


        //-----------------------------------------
   
    }

    public static function sendRefillEmail(Customer $customer, $order) {
        $vat = 0;

        //create transaction
//        $transaction = new Transaction();
//        $transaction->setOrderId($order->getId());
//        $transaction->setCustomer($customer);
//        $transaction->setAmount($order->getExtraRefill());


        $tc = new Criteria();
        $tc->add(TransactionPeer::ORDER_ID, $order->getId());
        $transaction = TransactionPeer::doSelectOne($tc);

        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }
        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('affiliate/order_receipt', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));

        $subject = __('Payment Confirmation');
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('LandNCall AB refill via agent');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('LandNCall AB refill via agent');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB refill via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To cdu
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    public static function sendCustomerRegistrationViaRetail(Customer $customer, $order) {
        $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
        $vat = .20 * $product_price;



        $tc = new Criteria();
        $tc->add(TransactionPeer::ORDER_ID, $order->getId());
        $transaction = TransactionPeer::doSelectOne($tc);


        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt_sms', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));

        $subject = __('Payment Confirmation');



        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');
        //---------------------------------------
        //--------------Sent The Email To Support

        $email3 = new EmailQueue();
        $email3->setSubject($subject);
        $email3->setReceipientName($sender_name);
        $email3->setReceipientEmail($sender_email);
        $email3->setEmailType('Retail Activation');
        $email3->setMessage($message_body);
        $email3->save();

        $email3 = new EmailQueue();
        $email3->setSubject($subject);
        $email3->setReceipientName($sender_namecdu);
        $email3->setReceipientEmail($sender_emailcdu);
        $email3->setEmailType('Retail Activation');
        $email3->setMessage($message_body);
        $email3->save();
        //-----------------------------------------
    }

    public static function sendCustomerRegistrationViaAgentEmail(Customer $customer, $order) {

        echo 'sending email';
        echo '<br/>';
        $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
        echo $product_price;
        echo '<br/>';
        $vat = .20 * $product_price;
        echo $vat;
        echo '<br/>';

//        //create transaction
//        $transaction = new Transaction();
//        $transaction->setOrderId($order->getId());
//        $transaction->setCustomer($customer);
//        $transaction->setAmount($form['extra_refill']);

        $tc = new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);


        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }
        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('affiliate/order_receipt', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));

        $subject = __('Payment Confirmation');
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('LandNCall AB Customer registration via agent');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('LandNCall AB Customer registration via agent');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To Okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB Customer registration via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB Customer registration via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    public static function sendForgetPasswordEmail(Customer $customer, $message_body) {
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');

        $subject = "Begäran om lösenord";
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());
        $referrer_id = trim($customer->getReferrerId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'support@LandNCall AB.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setAgentId($referrer_id);
            $email->setEmailType('LandNCall AB Forget Password');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
    }

    public static function sendCustomerRefillEmail(Customer $customer, $order, $transaction) {

        //set vat
        $vat = 0;
        $subject ='Payment Confirmation';
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());
        $referrer_id = trim($customer->getReferrerId());

        if ($referrer_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $referrer_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }

        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('payments/order_receipt', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));


        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_emailcdu = sfConfig::get('app_email_sender_email', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($referrer_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('LandNCall AB refill');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($referrer_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('LandNCall AB refill');
            $email2->setMessage($message_body);
            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB refill via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
          $email5 = new EmailQueue();
            $email5->setSubject($subject);
            $email5->setReceipientName($sender_namecdu);
            $email5->setReceipientEmail('rs@zapna.com');
            $email5->setAgentId($referrer_id);
            $email5->setCutomerId($customer_id);
            $email5->setEmailType(' Refill Email');
            $email5->setMessage($message_body);
            $email5->save();
    }

    public static function sendCustomerAutoRefillEmail(Customer $customer, $order, $transaction) {

             $vat = 0;
        $subject ='Payment Confirmation';
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());
        $referrer_id = trim($customer->getReferrerId());

        if ($referrer_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $referrer_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }

        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
                    $unid= $customer->getUniqueid();
        $unid=substr($unid,0,2);
        if($unid=="us"){
              $message_body = get_partial('pScripts/order_receipt_us', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));
        }else{

        $message_body = get_partial('pScripts/order_receipt', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));


        }


        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_emailcdu = sfConfig::get('app_email_sender_email', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($referrer_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('Auto Refill');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($referrer_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('Auto Refill');
            $email2->setMessage($message_body);
            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Auto Refill');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Auto Refill');
            $email4->setMessage($message_body);
            $email4->save();
        endif;

         $email5 = new EmailQueue();
            $email5->setSubject($subject);
            $email5->setReceipientName($sender_namecdu);
            $email5->setReceipientEmail('rs@zapna.com');
            $email5->setAgentId($referrer_id);
            $email5->setCutomerId($customer_id);
            $email5->setEmailType('Auto Refill');
            $email5->setMessage($message_body);
            $email5->save();



    }

    public static function sendCustomerConfirmPaymentEmail(Customer $customer, $message_body) {


        $subject = __('Payment Confirmation');
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB ');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB ');

        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());
        $referrer_id = trim($customer->getReferrerId());


        //send to user
        if (trim($recepient_email) != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setMessage($message_body);
            $email->setReceipientEmail($recepient_email);
            $email->setReceipientName($recepient_name);
            $email->setCutomerId($customer_id);
            $email->setAgentId($referrer_id);
            $email->setEmailType('LandNCall AB Customer Confirm Payment');

            $email->save();
        endif;

        //send to okhan
        if (trim($sender_email) != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setMessage($message_body);
            $email2->setReceipientEmail($sender_email);
            $email2->setReceipientName($sender_name);
            $email2->setCutomerId($customer_id);
            $email2->setAgentId($referrer_id);
            $email2->setEmailType('LandNCall AB Customer Confirm Payment');
            $email2->save();
        endif;
        //send to cdu
        if (trim($sender_emailcdu) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientEmail($sender_emailcdu);
            $email3->setReceipientName($sender_namecdu);
            $email3->setCutomerId($customer_id);
            $email3->setAgentId($referrer_id);
            $email3->setEmailType('LandNCall AB Customer Confirm Payment');
            $email3->save();
        endif;
    }

    public static function sendCustomerConfirmRegistrationEmail($inviteuserid) {
        $subject = 'Bonus Bekräftelse Smartsim';

        $sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB support');
        $message_body = '
Härmed bekräftas att du har fått provision insatt på ditt konto för att du har tipsat en vän om Smartsim från LandNCall.
Gå in på ”Mina sidor” och gå till ”Övrig historik” under ”Samtalshistorik” så ser du vad du har tjänat.<br/>Med vänlig hälsning,
<br/>
LandNCall<br/>
www.landncall.com';

        $c = new Criteria();
        $c->add(CustomerPeer::ID, $inviteuserid);
        $customer = CustomerPeer::doSelectOne($c);
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());
        //$referrer_id        = trim($customer->getReferrerId());
        //send to user
        if ($recepient_email != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setMessage($message_body);
            $email->setReceipientEmail($recepient_email);
            $email->setReceipientName($recepient_name);
            $email->setCutomerId($customer_id);
            //$email->setAgentId($referrer_id);
            $email->setEmailType('LandNCall AB Customer Confirm Bonus');

            $email->save();
        endif;

        //send to okhan
        if ($sender_email != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setMessage($message_body);
            $email2->setReceipientEmail($sender_email);
            $email2->setReceipientName($sender_name);
            $email2->setCutomerId($customer_id);
            //$email2->setAgentId($referrer_id);
            $email2->setEmailType('LandNCall AB Customer Confirm Bonus');
            $email2->save();
        endif;
        //////////////////////////////////////////////////////////////////
        if ($sender_emailcdu != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientName($sender_namecdu);
            $email3->setReceipientEmail($sender_emailcdu);
            $email3->setCutomerId($customer_id);
            //$email3->setAgentId($referrer_id);
            $email3->setEmailType('LandNCall AB Customer Confirm Bonus');
            $email3->save();
        endif;
    }

//////////////////////////////////////////////////////////////

    public static function sendCustomerRegistrationViaWebEmail(Customer $customer, $order) {

        //echo 'sending email';
        echo '<br/>';
        $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
        // echo $product_price;
        echo '<br/>';
        $vat = .20 * $product_price;
        // echo $vat;
        echo '<br/>';

//        //create transaction
//        $transaction = new Transaction();
//        $transaction->setOrderId($order->getId());
//        $transaction->setCustomer($customer);
//        $transaction->setAmount($form['extra_refill']);

        $tc = new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);


        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }
        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => true,
                ));


        $subject = __('Registration Confirmation');
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB ');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB ');

        //------------------Sent The Email To Customer
        if ($recepient_email != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setEmailType('LandNCall AB Customer registration via link');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        //--------------Sent The Email To Support
        if ($sender_email != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB Customer registration via link');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Support
        if ($sender_emailcdu != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    ///////////////////////////////////////////////////////////

    public static function sendCustomerRegistrationViaAgentSMSEmail(Customer $customer, $order) {

        echo 'sending email';
        echo '<br/>';
        $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
        echo $product_price;
        echo '<br/>';
        $vat = .20 * $product_price;
        echo $vat;
        echo '<br/>';
        echo 'ID:' . $customer->getId();
        echo '<br/>';
        echo '<br/>' . $customer->getReferrerId();


//        //create transaction
//        $transaction = new Transaction();
//        $transaction->setOrderId($order->getId());
//        $transaction->setCustomer($customer);
//        $transaction->setAmount($form['extra_refill']);

        $tc = new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);


        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }

        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt_sms', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));

        $subject = __('Registration  Confirmation');
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');

        //------------------Sent The Email To Customer
        //------------------Sent the Email To Agent

        if (trim($recepient_agent_email) != ''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('LandNCall AB Customer registration via agent SMS ');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To Okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB Customer registration via agent SMS ');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB Customer registration via agent SMS ');

            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    public static function sendCustomerRegistrationViaAgentAPPEmail(Customer $customer, $order) {

        echo 'sending email';
        echo '<br/>';
        $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
        echo $product_price;
        echo '<br/>';
        $vat = .20 * $product_price;
        echo $vat;
        echo '<br/>';

//        //create transaction
//        $transaction = new Transaction();
//        $transaction->setOrderId($order->getId());
//        $transaction->setCustomer($customer);
//        $transaction->setAmount($form['extra_refill']);

        $tc = new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);


        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }
        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt_sms', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));

        $subject = __('Registration Confirmation');
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('LandNCall AB Customer registration via APP');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('LandNCall AB Customer registration via APP');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To Okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB Customer registration via APP');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB Customer registration via APP');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    public static function sendvoipemail(Customer $customer, $order, $transaction) {

        //set vat
        $vat = 0;
        $subject = 'Bekräftelse - nytt resenummer frän LandNCall';
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());
        $referrer_id = trim($customer->getReferrerId());

        if ($referrer_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $referrer_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }

        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
//        $message_body = get_partial('payments/order_receipt', array(
//                'customer'=>$customer,
//                'order'=>$order,
//                'transaction'=>$transaction,
//                'vat'=>$vat,
//                'agent_name'=>$recepient_agent_name,
//                'wrap'=>false,
//        ));
        // Please remove the receipt that is sent out when activating
        $getvoipInfo = new Criteria();
        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customer->getId());
        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
        if (isset($getvoipInfos)) {
            $voipnumbers = $getvoipInfos->getNumber();
            $voip_customer = $getvoipInfos->getCustomerId();
        } else {
            $voipnumbers = '';
            $voip_customer = '';
        }



        $message_body = "<table width='600px'><tr style='border:0px solid #fff'><td colspan='4' align='right' style='text-align:right; border:0px solid #fff'>" . image_tag('http://landncall.zerocall.com/images/logo.gif') . "</tr></table><table cellspacing='0' width='600px'><tr><td>Grattis till ditt nya resenummer. Detta nummer är alltid kopplat till den telefon där du har Smartsim aktiverat. Med resenumret blir du nådd utomlands då du har ett lokalt SIM-kort. Se prislistan för hur mycket det kostar att ta emot samtal.
Ditt resenummer är $voipnumbers.<br/><br/>
Med vänlig hälsning<br/><br/>
LandNCall<br/><a href='http://www.landncall.com'>www.landncall.com</a></td></tr></table>";

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_emailcdu = sfConfig::get('app_email_sender_email', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($referrer_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('Transation for VoIP Purchase');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):
//            $email2 = new EmailQueue();
//            $email2->setSubject($subject);
//            $email2->setReceipientName($recepient_agent_name);
//            $email2->setReceipientEmail($recepient_agent_email);
//            $email2->setAgentId($referrer_id);
//            $email2->setCutomerId($customer_id);
//            $email2->setEmailType('Transation for VoIP Purchase');
//            $email2->setMessage($message_body);
//            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Transation for VoIP Purchase');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Transation for VoIP Purchase');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

    public static function sendCustomerBalanceEmail(Customer $customer, $message_body) {

        $subject = ' Låg Kredit Varning';
        $recepient_name = '';
        $recepient_email = '';

        $recepient_name = $customer->getFirstName() . ' ' . $customer->getLastName();
        $recepient_email = $customer->getEmail();
        $customer_id = trim($customer->getId());
        $referrer_id = trim($customer->getReferrerId());

        if (trim($recepient_email) != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setMessage($message_body);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setAgentId($referrer_id);
            $email->setEmailType('LandnCall Customer Balance');
            $email->setReceipientName($recepient_name);
            $email->save();
        endif;
    }

    public static function sendErrorTelinta(Customer $customer, $message) {

        $subject = 'Error In Telinta';
        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = "<table width='600px'><tr style='border:0px solid #fff'><td colspan='4' align='right' style='text-align:right; border:0px solid #fff'></tr></table><table cellspacing='0' width='600px'><tr><td>
             " . $message . " <br/><br/>
Med vänlig hälsning<br/><br/>
LandNCall<br/><a href='http://www.landncall.com'>www.landncall.com</a></td></tr></table>";

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'rs@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        //$sender_emailcdu = sfConfig::get('app_email_sender_email', 'zerocallengineering@googlegroups.com');
        //$sender_namecdu = sfConfig::get('app_email_sender_name', 'LandNCall AB');


        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Error In Telinta');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
       
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName("Baran Khan");
            $email4->setReceipientEmail("bk@zapna.com");
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Error In Telinta');
            $email4->setMessage($message_body);
            $email4->save();
        
        //-----------------------------------------
    }

    public static function sendUniqueIdsShortage() {

        $subject = 'Unique Ids finished.';
        $message_body = "<table width='600px'><tr style='border:0px solid #fff'><td colspan='4' align='right' style='text-align:right; border:0px solid #fff'></tr></table><table cellspacing='0' width='600px'><tr><td>
             " . $message . " <br/><br/>
Uniuqe Ids finsihed.<br/><br/>
LandNCall<br/><a href='http://www.landncall.com'>www.landncall.com</a></td></tr></table>";

        //Support Informationt
        $sender_email = sfConfig::get('app_email_sender_email', 'rs@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        //$sender_emailcdu = sfConfig::get('app_email_sender_email', 'zerocallengineering@googlegroups.com');
        //$sender_namecdu = sfConfig::get('app_email_sender_name', 'LandNCall AB');


        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Unique Ids Finished');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        /*if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Unique Ids Finished');
            $email4->setMessage($message_body);
            $email4->save();
        endif;*/
        //-----------------------------------------
    }

    public static function sendUniqueIdsIssueAgent($uniqueid, Customer $customer) {

        $subject = 'Unique Ids finished.';
        $message_body = "<table width='600px'><tr style='border:0px solid #fff'><td colspan='4' align='right' style='text-align:right; border:0px solid #fff'></tr></table><table cellspacing='0' width='600px'><tr><td>
             " . $message . " <br/><br/>
Uniuqe Id " . $uniqueid . " has issue while assigning on " . $customer->getMobileNumber() . "<br/><br/>
LandNCall<br/><a href='http://www.landncall.com'>www.landncall.com</a></td></tr></table>";

        //Support Informationt
        $sender_email = sfConfig::get('app_email_sender_email', 'rs@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        //$sender_emailcdu = sfConfig::get('app_email_sender_email', 'zerocallengineering@googlegroups.com');
        //$sender_namecdu = sfConfig::get('app_email_sender_name', 'LandNCall AB');


        //--------------Sent The Email To okhan
        if (trim($sender_email) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Unique Ids Finished');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To CDU
        /*if (trim($sender_emailcdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Unique Ids Finished');
            $email4->setMessage($message_body);
            $email4->save();
        endif;*/
        //-----------------------------------------
    }

    public static function sendErrorInTelinta($subject, $message) {

        //To RS.
        $email = new EmailQueue();
        $email->setSubject($subject);
        $email->setReceipientName("Raheel Safdar");
        $email->setReceipientEmail("rs@zapna.com");
        $email->setEmailType('Telinta Error');
        $email->setMessage($message);
        $email->save();

        //To Support @ Zerocall
        $email = new EmailQueue();
        $email->setSubject($subject);
        $email->setReceipientName("Baran Khan");
        $email->setReceipientEmail("bk@zapna.com");
        $email->setEmailType('Telinta Error');
        $email->setMessage($message);
        $email->save();
    }

//////////////////////////////////////////////////////////////////////////////////

    public static function sendCustomerRegistrationViaWebUSEmail(Customer $customer, $order) {

        //echo 'sending email';
        echo '<br/>';
        $product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
        // echo $product_price;
        echo '<br/>';
        $vat = .20 * $product_price;
        // echo $vat;
        echo '<br/>';

//        //create transaction
//        $transaction = new Transaction();
//        $transaction->setOrderId($order->getId());
//        $transaction->setCustomer($customer);
//        $transaction->setAmount($form['extra_refill']);

        $tc = new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);


        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_email = '';
            $recepient_agent_name = '';
        }
        //$this->renderPartial('affiliate/order_receipt', array(
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt_us', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => true,
                ));


        $subject = __('Registration Confirmation');
        $recepient_email = trim($customer->getEmail());
        $recepient_name = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id = trim($customer->getId());

        //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB ');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB ');

        //------------------Sent The Email To Customer
        if ($recepient_email != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setEmailType('LandNCall AB Customer registration via link');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('pScripts/order_receipt_us_admin', array(
                    'customer' => $customer,
                    'order' => $order,
                    'transaction' => $transaction,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => true,
                ));
        //------------------Sent the Email To Agent
        //--------------Sent The Email To Support
        if ($sender_email != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('LandNCall AB Customer registration via link');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Support
        if ($sender_emailcdu != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('LandNCall AB Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }

///////////////////////////////////////////////////////////////////

    public static function sendLandncallCdrErrorEmail($filename) {
    $sender_namecdu='rs@zapna.com';
        $sender_emailcdu='landncall@zapna.com';
        $subject='Landncall CDr File Upload Issue';
        $message_body='Landncall CDr File Upload Issue File Name is ='.$filename;
        $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setEmailType('LandNCall CDR Files Error email');
            $email4->setMessage($message_body);
            $email4->save();
    }

public static function sendAdminRefilEmail(AgentCompany $agent,$agent_order)

    {
        $vat = 0;

        //create transaction





        //This Section For Get The Agent Information
        $agent_company_id = $agent->getId();
        if($agent_company_id!=''){
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_email  = AgentCompanyPeer::doSelectOne($c)->getEmail();
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        }else{
            $recepient_agent_email  = '';
            $recepient_agent_name = '';
        }

        //$this->renderPartial('affiliate/order_receipt', array(
        $agentamount=$agent_order->getAmount();
        $createddate=$agent_order->getCreatedAt('m-d-Y');
        $agentid=$agent_order->getAgentOrderId();
        $order_des=$agent_order->getOrderDescription();
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = get_partial('agent_company/agent_order_receipt', array(
                'order'=>$agentid,
                'transaction'=>$agentamount,
                'createddate'=>$createddate,
                'description'=>$order_des,
                'vat'=>$vat,
                'agent_name'=>$recepient_agent_name,
                'wrap'=>false,
                'agent' => $agent
        ));


        $subject = __('Agent Payment Confirmation');


       //Support Information
        $sender_email = sfConfig::get('app_email_sender_email', 'okhan@zapna.com');
        $sender_emailcdu = sfConfig::get('app_email_sender_email_cdu', 'jan.larsson@landncall.com');
        $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB');
        $sender_namecdu = sfConfig::get('app_email_sender_name_cdu', 'LandNCall AB');

        //------------------Sent The Email To Customer

        //----------------------------------------

        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email)!=''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
             $email2->setEmailType('LandNCall Agent refill via admin');
            $email2->setMessage($message_body);

            $email2->save();
         endif;
        //---------------------------------------

       //--------------Sent The Email To okhan
         if (trim($sender_email)!=''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($sender_name);
            $email3->setReceipientEmail($sender_email);
            $email3->setAgentId($agent_company_id);
            $email3->setEmailType('LandNCall Agent refill via admin');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
          //--------------Sent The Email To cdu
         if (trim($sender_emailcdu)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($sender_emailcdu);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('LandNCall Agent refill via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
    }
 public static function smsNotSentEmail($employeList)
    {

$subject="SMS Not Working";
$sender_namecdu="LandNCall";
$message_body= "Please investigate  <br/>".$employeList;
$rs_email='rs@zapna.com';
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($sender_namecdu);
            $email4->setReceipientEmail($rs_email);
             $email4->setEmailType('SMS not sent issue');
            $email4->setMessage($message_body);
            $email4->save();
            $rs_email='khan.muhammad@zerocall.com';
               $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($sender_namecdu);
            $email->setReceipientEmail($rs_email);
             $email->setEmailType('SMS not sent issue');
            $email->setMessage($message_body);
            $email->save();

    }
    public static function sendErrorInForumTel($subject, $message) {

        //To RS.
//        $email = new EmailQueue();
//        $email->setSubject($subject);
//        $email->setReceipientName("Raheel Safdar");
//        $email->setReceipientEmail("rs@zapna.com");
//        $email->setEmailType('Telinta Error');
//        $email->setMessage($message);
//        $email->save();

        //To Support @ Zerocall
        $email = new EmailQueue();
        $email->setSubject($subject);
        $email->setReceipientName("Rubab");
        $email->setReceipientEmail("rr@zerocall.com");
        $email->setEmailType('ForumTel Response Error');
        $email->setMessage($message);
        $email->save();
    }
}

?>