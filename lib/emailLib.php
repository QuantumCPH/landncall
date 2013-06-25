<?php

class emailLib {

//rs@zapna.com    to    jan.larsson@SmartSim.com 
//asd@SmartSim.com  to    okhan@zapna.com
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
        $message_body = get_partial('pScripts/agent_order_receipt', array(
                    'order' => $agentid,
                    'transaction' => $agentamount,
                    'createddate' => $createddate,
                    'vat' => $vat,
                    'agent_name' => $recepient_agent_name,
                    'wrap' => false,
                ));


        $subject = __('Agent Payment Confirmation');
        
        //Support Information
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        
        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email) != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setEmailType('SmartSim refill via agent');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setEmailType('SmartSim refill via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To cdu
        if (trim($recipient_email_cdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('SmartSim refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('SmartSim refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('SmartSim refill via agent');
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
                
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        //---------------------------------------
        //
        //--------------Sent The Email To OKhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setEmailType('Retail Refil');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_jan);
            $email3->setReceipientEmail($recipient_email_jan);
            $email3->setEmailType('Retail Refil');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        
        //--------------Sent The Email To Cdu
        if (trim($recipient_email_cdu) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_cdu);
            $email3->setReceipientEmail($recipient_email_cdu);
            $email3->setEmailType('Retail Refil');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        
        //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_rs);
            $email3->setReceipientEmail($recipient_email_rs);
            $email3->setEmailType('Retail Refil');
            $email3->setMessage($message_body);
            $email3->save();
        endif;

        //-----------------------------------------
   
    }

    public static function sendRefillEmail(Customer $customer, $order) {
        $vat = 0;

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
                
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim refill via agent');
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
            $email2->setEmailType('SmartSim refill via agent');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim refill via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To cdu
        if (trim($recipient_email_cdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim refill via agent');
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        
        //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_rs);
            $email3->setReceipientEmail($recipient_email_rs);
            $email3->setEmailType('Retail Activation');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_jan);
            $email3->setReceipientEmail($recipient_email_jan);
            $email3->setEmailType('Retail Activation');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To OKhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setEmailType('Retail Activation');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
         //--------------Sent The Email To cdu
        if (trim($recipient_email_cdu) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_cdu);
            $email3->setReceipientEmail($recipient_email_cdu);
            $email3->setEmailType('Retail Activation');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim Customer registration via agent');
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
            $email2->setEmailType('SmartSim Customer registration via agent');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To Okhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim Customer registration via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To cdu
        if (trim($recipient_email_cdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via agent');
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
        $sender_email = sfConfig::get('app_email_sender_email', 'support@smartsim.se');
        $sender_name = sfConfig::get('app_email_sender_name', 'SmartSim support');

        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setAgentId($referrer_id);
            $email->setEmailType('SmartSim Forget Password');
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($referrer_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim refill');
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
            $email2->setEmailType('SmartSim refill');
            $email2->setMessage($message_body);
            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To okhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim refill via agent');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim refill via agent');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
          //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email5 = new EmailQueue();
            $email5->setSubject($subject);
            $email5->setReceipientName($recipient_name_rs);
            $email5->setReceipientEmail($recipient_email_rs);
            $email5->setAgentId($referrer_id);
            $email5->setCutomerId($customer_id);
            $email5->setEmailType(' Refill Email');
            $email5->setMessage($message_body);
            $email5->save();
        endif;  
          //--------------Sent The Email To CDU
        if (trim($recipient_email_cdu) != ''):
            $email5 = new EmailQueue();
            $email5->setSubject($subject);
            $email5->setReceipientName($recipient_name_cdu);
            $email5->setReceipientEmail($recipient_email_cdu);
            $email5->setAgentId($referrer_id);
            $email5->setCutomerId($customer_id);
            $email5->setEmailType(' Refill Email');
            $email5->setMessage($message_body);
            $email5->save();
        endif;  
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        
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
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Auto Refill');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Auto Refill');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //--------------Sent The Email To RS
        if (trim($recipient_email_rs) != ''):
            $email5 = new EmailQueue();
            $email5->setSubject($subject);
            $email5->setReceipientName($recipient_name_rs);
            $email5->setReceipientEmail($recipient_email_rs);
            $email5->setAgentId($referrer_id);
            $email5->setCutomerId($customer_id);
            $email5->setEmailType('Auto Refill');
            $email5->setMessage($message_body);
            $email5->save();
       endif;
        //--------------Sent The Email To CDU
        if (trim($recipient_email_cdu) != ''):
            $email5 = new EmailQueue();
            $email5->setSubject($subject);
            $email5->setReceipientName($recipient_name_cdu);
            $email5->setReceipientEmail($recipient_email_cdu);
            $email5->setAgentId($referrer_id);
            $email5->setCutomerId($customer_id);
            $email5->setEmailType('Auto Refill');
            $email5->setMessage($message_body);
            $email5->save();
       endif;


    }

    public static function sendCustomerConfirmPaymentEmail(Customer $customer, $message_body) {


        $subject = __('Payment Confirmation');
                
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');

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
            $email->setEmailType('SmartSim Customer Confirm Payment');

            $email->save();
        endif;

        //send to okhan
        if (trim($recipient_email_ok) != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setMessage($message_body);
            $email2->setReceipientEmail($recipient_email_ok);
            $email2->setReceipientName($recipient_name_ok);
            $email2->setCutomerId($customer_id);
            $email2->setAgentId($referrer_id);
            $email2->setEmailType('SmartSim Customer Confirm Payment');
            $email2->save();
        endif;
        //send to jan
        if (trim($recipient_email_jan) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientEmail($recipient_email_jan);
            $email3->setReceipientName($recipient_name_jan);
            $email3->setCutomerId($customer_id);
            $email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Payment');
            $email3->save();
        endif;
        //send to cdu
        if (trim($recipient_email_cdu) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientEmail($recipient_email_cdu);
            $email3->setReceipientName($recipient_name_cdu);
            $email3->setCutomerId($customer_id);
            $email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Payment');
            $email3->save();
        endif;
        //send to rs
        if (trim($recipient_email_rs) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientEmail($recipient_email_rs);
            $email3->setReceipientName($recipient_name_rs);
            $email3->setCutomerId($customer_id);
            $email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Payment');
            $email3->save();
        endif;
    }

    public static function sendCustomerConfirmRegistrationEmail($inviteuserid) {
        $subject = 'Bonus Bekräftelse Smartsim';

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        
        $recipient_name_landncall = sfConfig::get('app_recipient_name_landncall');
        $recipient_email_landncall = sfConfig::get('app_recipient_email_landncall');
        $message_body = '
Härmed bekräftas att du har fått provision insatt på ditt konto för att du har tipsat en vän om Smartsim från.
Gå in på ”Mina sidor” och gå till ”Övrig historik” under ”Samtalshistorik” så ser du vad du har tjänat.<br/>Med vänlig hälsning,
<br/>
SmartSim<br/>
www.smartsim.se';

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
            $email->setEmailType('SmartSim Customer Confirm Bonus');

            $email->save();
        endif;

        //send to okhan
        if ($recipient_email_landncall != ''):
            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setMessage($message_body);
            $email2->setReceipientEmail($recipient_email_landncall);
            $email2->setReceipientName($sender_name);
            $email2->setCutomerId($customer_id);
            //$email2->setAgentId($referrer_id);
            $email2->setEmailType('SmartSim Customer Confirm Bonus');
            $email2->save();
        endif;
        //////////////////////////////////////////////////////////////////
        if ($recipient_email_jan != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientName($recipient_name_jan);
            $email3->setReceipientEmail($recipient_email_jan);
            $email3->setCutomerId($customer_id);
            //$email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Bonus');
            $email3->save();
        endif;
        
        if ($recipient_email_ok != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setCutomerId($customer_id);
            //$email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Bonus');
            $email3->save();
        endif;
        
        if ($recipient_email_cdu != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientName($recipient_name_cdu);
            $email3->setReceipientEmail($recipient_email_cdu);
            $email3->setCutomerId($customer_id);
            //$email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Bonus');
            $email3->save();
        endif;
        
        if ($recipient_email_rs != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setMessage($message_body);
            $email3->setReceipientName($recipient_name_rs);
            $email3->setReceipientEmail($recipient_email_rs);
            $email3->setCutomerId($customer_id);
            //$email3->setAgentId($referrer_id);
            $email3->setEmailType('SmartSim Customer Confirm Bonus');
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        //------------------Sent The Email To Customer
        if ($recepient_email != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim Customer registration via link');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------
        //------------------Sent the Email To Agent
        //--------------Sent The Email To Support
        if ($recipient_email_ok != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim Customer registration via link');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Support
        if ($recipient_email_jan != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        if ($recipient_email_rs != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        if ($recipient_email_cdu != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
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
            
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');

        //------------------Sent The Email To Customer
        //------------------Sent the Email To Agent

        if (trim($recepient_agent_email) != ''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('SmartSim Customer registration via agent SMS ');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To Okhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim Customer registration via agent SMS ');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via agent SMS ');

            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
         if (trim($recipient_email_rs) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via agent SMS ');

            $email4->setMessage($message_body);
            $email4->save();
        endif;
         if (trim($recipient_email_cdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via agent SMS ');

            $email4->setMessage($message_body);
            $email4->save();
        endif;
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

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        //------------------Sent The Email To Customer
        if (trim($recepient_email) != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim Customer registration via APP');
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
            $email2->setEmailType('SmartSim Customer registration via APP');
            $email2->setMessage($message_body);

            $email2->save();
        endif;
        //---------------------------------------
        //--------------Sent The Email To Okhan
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim Customer registration via APP');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via APP');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        if (trim($recipient_email_rs) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via APP');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        if (trim($recipient_email_cdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via APP');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
    }

    public static function sendvoipemail(Customer $customer, $order, $transaction) {

        //set vat
        $vat = 0;
        $subject = 'Bekräftelse - nytt resenummer frän SmartSim';
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
SmartSim<br/><a href='http://www.smartsim.se'>www.smartsim.se</a></td></tr></table>";

        //Support Information

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
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
        if (trim($recipient_email_ok) != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($referrer_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('Transation for VoIP Purchase');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To jan
        if (trim($recipient_email_jan) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Transation for VoIP Purchase');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        if (trim($recipient_email_rs) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Transation for VoIP Purchase');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        if (trim($recipient_email_cdu) != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($referrer_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('Transation for VoIP Purchase');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
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
            $email->setEmailType('SmartSim Customer Balance');
            $email->setReceipientName($recepient_name);
            $email->save();
        endif;
    }

    public static function sendErrorTelinta(Customer $customer, $message) {

        $subject = 'Error In Telinta';

        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
        $message_body = "<table cellspacing='0' width='600px'>
                         <tr><td>" . $message . " <br/><br/>Med vänlig hälsning<br/><br/>
                         SmartSim<br/><a href='http://www.smartsim.se'>www.smartsim.se</a></td></tr></table>";

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('Error In Telinta');
            $email->setMessage($message_body);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('Error In Telinta');
            $email->setMessage($message_body);
            $email->save();
         endif;
        //**********************************************************************
    }

    public static function sendUniqueIdsShortage() {

        $subject = 'Unique Ids finished.';
        $message_body = "<table cellspacing='0' width='600px'><tr><td>
        Uniuqe Ids finsihed.<br/><br/>
        SmartSim<br/><a href='http://www.smartsim.se'>www.smartsim.se</a></td></tr></table>";

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('Unique Ids Finished');
            $email->setMessage($message_body);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('Unique Ids Finished');
            $email->setMessage($message_body);
            $email->save();
         endif;
        //**********************************************************************
    }

    public static function sendUniqueIdsIssueAgent($uniqueid, Customer $customer) {

        $subject = 'Unique Ids finished.';
        $message_body = "<table cellspacing='0' width='600px'><tr><td>
            Uniuqe Id " . $uniqueid . " has issue while assigning on " . $customer->getMobileNumber() . "<br/><br/>
            SmartSim<br/><a href='http://www.smartsim.se'>www.smartsim.se</a></td></tr></table>";

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('Unique Ids Finished');
            $email->setMessage($message_body);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('Unique Ids Finished');
            $email->setMessage($message_body);
            $email->save();
         endif;
        //**********************************************************************
    }

    public static function sendErrorInTelinta($subject, $message) {

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('Telinta Error');
            $email->setMessage($message);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('Telinta Error');
            $email->setMessage($message);
            $email->save();
         endif;
        //**********************************************************************
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        //------------------Sent The Email To Customer
        if ($recepient_email != '') {
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim Customer registration via link');
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
        if ($recipient_email_ok != ''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim Customer registration via link');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
        //--------------Sent The Email To Support
        if ($recipient_email_cdu != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        if ($recipient_email_jan != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        if ($recipient_email_rs != ''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Customer registration via link');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
    }

///////////////////////////////////////////////////////////////////

    public static function sendLandncallCdrErrorEmail($filename) {

        $subject='SmartSim CDr File Upload Issue';
        $message_body='SmartSim CDr File Upload Issue File Name is ='.$filename;

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('SmartSim CDR Files Error email');
            $email->setMessage($message_body);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('SmartSim CDR Files Error email');
            $email->setMessage($message_body);
            $email->save();
         endif;
        //**********************************************************************
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
        
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan');
        //------------------Sent The Email To Customer

        //----------------------------------------

        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email)!=''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
             $email2->setEmailType('SmartSim Agent refill via admin');
            $email2->setMessage($message_body);

            $email2->save();
         endif;
        //---------------------------------------

       //--------------Sent The Email To okhan
         if (trim($recipient_email_ok)!=''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setEmailType('SmartSim Agent refill via admin');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
          //--------------Sent The Email To jan
         if (trim($recipient_email_jan)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('SmartSim Agent refill via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
         if (trim($recipient_email_rs)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('SmartSim Agent refill via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
         if (trim($recipient_email_cdu)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setEmailType('SmartSim Agent refill via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
    }
 public static function smsNotSentEmail($employeList)
    {

        $subject="SMS Not Working";
        $message_body= "Please investigate  <br/>".$employeList;

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('SMS not sent issue');
            $email->setMessage($message_body);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('SMS not sent issue');
            $email->setMessage($message_body);
            $email->save();
         endif;
        //**********************************************************************

    }
    public static function sendErrorInForumTel($subject, $message) {

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('ForumTel Response Error');
            $email->setMessage($message);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('ForumTel Response Error');
            $email->setMessage($message);
            $email->save();
         endif;
        //**********************************************************************
    }

    public static function sendAdminRefillEmail(Customer $customer,$order)
    {
        $vat = 0;


        if($order){
            $vat = $order->getIsFirstOrder() ?
                    ($order->getProduct()->getPrice() * $order->getQuantity() -
                    $order->getProduct()->getInitialBalance()) * .20 :
                    0;
        }
        //create transaction
        $tc  =new Criteria();
        $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId() );
        $tc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $transaction = TransactionPeer::doSelectOne($tc);

        //This Section For Get The Agent Information
        $agent_company_id = $customer->getReferrerId();
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
        sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');

        $unidc = $customer->getUniqueid();
                $uidcount=0;
                $uc = new Criteria;
                $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $unidc);
                $uc->addAnd(UniqueIdsPeer::REGISTRATION_TYPE_ID, 3);
                $uidcount = UniqueIdsPeer::doCount($uc);

            if ($uidcount==1) {
                $message_body = get_partial('customer/order_receipt_us', array(
                    'customer'=>$customer,
                    'order'=>$order,
                    'transaction'=>$transaction,
                    'vat'=>$vat,
                    'agent_name'=>$recepient_agent_name,
                    'wrap'=>false,
                ));
            }else{
                $message_body = get_partial('customer/order_receipt', array(
                    'customer'=>$customer,
                    'order'=>$order,
                    'transaction'=>$transaction,
                    'vat'=>$vat,
                    'agent_name'=>$recepient_agent_name,
                    'wrap'=>false,
                ));
                
            }
        

        $subject = __('Payment Confirmation');
        $recepient_email    = trim($customer->getEmail());
        $recepient_name     = sprintf('%s %s', $customer->getFirstName(), $customer->getLastName());
        $customer_id        = trim($customer->getId());

        //Support Information

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');
        
        $recipient_name_cdu = sfConfig::get('app_recipient_name_cdu');
        $recipient_email_cdu = sfConfig::get('app_recipient_email_cdu');
        
        $recipient_name_ok = sfConfig::get('app_recipient_name_ok');
        $recipient_email_ok = sfConfig::get('app_recipient_email_ok');
        
        $recipient_name_jan = sfConfig::get('app_recipient_name_jan');
        $recipient_email_jan = sfConfig::get('app_recipient_email_jan'); 
        //------------------Sent The Email To Customer
        if(trim($recepient_email)!=''){
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recepient_name);
            $email->setReceipientEmail($recepient_email);
            $email->setAgentId($agent_company_id);
            $email->setCutomerId($customer_id);
            $email->setEmailType('SmartSim refill/charge via admin');
            $email->setMessage($message_body);
            $email->save();
        }
        //----------------------------------------

        //------------------Sent the Email To Agent
        if (trim($recepient_agent_email)!=''):

            $email2 = new EmailQueue();
            $email2->setSubject($subject);
            $email2->setReceipientName($recepient_agent_name);
            $email2->setReceipientEmail($recepient_agent_email);
            $email2->setAgentId($agent_company_id);
            $email2->setCutomerId($customer_id);
            $email2->setEmailType('SmartSim  Refill/charge via admin');
            $email2->setMessage($message_body);

            $email2->save();
         endif;
        //---------------------------------------

       //--------------Sent The Email To okhan
         if (trim($recipient_email_ok)!=''):
            $email3 = new EmailQueue();
            $email3->setSubject($subject);
            $email3->setReceipientName($recipient_name_ok);
            $email3->setReceipientEmail($recipient_email_ok);
            $email3->setAgentId($agent_company_id);
            $email3->setCutomerId($customer_id);
            $email3->setEmailType('SmartSim  Refill/charge via admin');
            $email3->setMessage($message_body);
            $email3->save();
        endif;
        //-----------------------------------------
      //--------------Sent The Email To cdu
         if (trim($recipient_email_cdu)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_cdu);
            $email4->setReceipientEmail($recipient_email_cdu);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim Refill/charge via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
         //--------------Sent The Email To RS
         if (trim($recipient_email_rs)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_rs);
            $email4->setReceipientEmail($recipient_email_rs);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim refill/charge via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
        //-----------------------------------------
        if (trim($recipient_email_jan)!=''):
            $email4 = new EmailQueue();
            $email4->setSubject($subject);
            $email4->setReceipientName($recipient_name_jan);
            $email4->setReceipientEmail($recipient_email_jan);
            $email4->setAgentId($agent_company_id);
            $email4->setCutomerId($customer_id);
            $email4->setEmailType('SmartSim refill/charge via admin');
            $email4->setMessage($message_body);
            $email4->save();
        endif;
    }
    
    public static function sendSMSRegistrationErrorEmail($customer_mobile,$subject, $message_body) {

        if($subject=="") $subject = 'Error Email';
        $recepient_name = '';
        $recepient_email = '';
        //// Customer
        $mobileNumber = substr($customer_mobile, 2, strlen($customer_mobile) - 2);
        if ($mobileNumber[0] != "0") {
           $mobileNumber = "0" . $mobileNumber;
        }
               
        //Support Information
        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');
        
        
        ////// Email to Support
//        if (trim($recipient_email_support) != ''):
//            $email = new EmailQueue();
//            $email->setSubject($subject);
//            $email->setMessage($message_body);
//            $email->setReceipientEmail($recipient_email_support);
//            $email->setEmailType('SmartSim-'.$subject);
//            $email->setReceipientName($recipient_name_support);
//            $email->save();
//        endif;
//        
//        ///// Email to RS
//        if (trim($recipient_email_rs) != ''):
//            $email = new EmailQueue();
//            $email->setSubject($subject);
//            $email->setMessage($message_body);
//            $email->setReceipientEmail($recipient_email_rs);
//            $email->setEmailType('SmartSim-'.$subject);
//            $email->setReceipientName($recipient_name_rs);
//            $email->save();
//        endif;
    }

    public static function sendErrorInAutoReg($subject, $message) {

        $recipient_name_rs = sfConfig::get('app_recipient_name_rs');
        $recipient_email_rs = sfConfig::get('app_recipient_email_rs');

        $recipient_name_support = sfConfig::get('app_recipient_name_support');
        $recipient_email_support = sfConfig::get('app_recipient_email_support');

        //**********************Sent The Email To RS****************************
        if ($recipient_email_rs != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_rs);
            $email->setReceipientEmail($recipient_email_rs);
            $email->setEmailType('Auto Registration Error');
            $email->setMessage($message);
            $email->save();
        endif;
        //**********************************************************************

        //*******************Sent The Email To Support**************************
         if ($recipient_email_support != ''):
            $email = new EmailQueue();
            $email->setSubject($subject);
            $email->setReceipientName($recipient_name_support);
            $email->setReceipientEmail($recipient_email_support);
            $email->setEmailType('Auto Registration Error');
            $email->setMessage($message);
            $email->save();
         endif;
        //**********************************************************************

    }
}

?>