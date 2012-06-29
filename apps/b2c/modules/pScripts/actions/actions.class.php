<?php
set_time_limit(10000000);
require_once(sfConfig::get('sf_lib_dir').'/changeLanguageCulture.php');
require_once(sfConfig::get('sf_lib_dir').'/emailLib.php');
require_once(sfConfig::get('sf_lib_dir').'/ForumTel.php');
require_once(sfConfig::get('sf_lib_dir').'/commissionLib.php');
require_once(sfConfig::get('sf_lib_dir').'/curl_http_client.php');
require_once(sfConfig::get('sf_lib_dir').'/smsCharacterReplacement.php');
/**
 * scripts actions.
 *
 * @package    zapnacrm
 * @subpackage scripts
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.5 2010-09-19 22:20:12 orehman Exp $
 */
class pScriptsActions extends sfActions
{

    
    /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeClearTestData(sfWebRequest $request)
  {


      //clear fonet balance of test fonet accounts
      foreach (FonetCustomerPeer::doSelect(new Criteria()) as $fonet_customer) {
            foreach ($fonet_customer->getCustomers() as $customer)
            {
              if (Fonet::unregister($customer, false))
                      echo sprintf("%s is unregistered<br />", $customer->getMobileNumber());
              if ($current_balance = Fonet::getBalance($customer, false))
                      echo sprintf("Current balance of custoemr is %s<br />", $current_balance);

              if (Fonet::recharge($customer, -$current_balance, false))
                      echo sprintf("current balance is made 0<br />");
            }
      }
      $con = Propel::getConnection();

      $con->exec('DELETE FROM transaction');
      $con->exec('DELETE FROM customer_order');
      $con->exec('DELETE FROM customer_product');
      $con->exec('DELETE FROM customer');
      $con->exec('TRUNCATE zerocall_cdr');
      $con->exec('TRUNCATE cloud9_data');

      $con->exec("UPDATE fonet_customer SET activated_on = NULL");


      echo "all data is flushed... customers, trnsactions, orders deleted. fonet customer id disconnected... their balances made to 0 on fonet";

    return sfView::NONE;
  }

  public function executeBannerTest(){

  }


 public function executeAppPasswordRecovery(sfWebrequest $request){
            //$this->forward404Unless($request->isMethod('post'));
            $mobile_number = "";
            $mobile_number = $request->getParameter('mobile');

            if(strlen($mobile_number)==13){
                    $arr  = explode("001", $mobile_number);

                foreach($arr as $ar){

                    if ($ar!="")
                        $mobile_number = $ar;
                }
                }else{

                if (strlen($mobile_number) == 10 ){

                    $arr  = explode("45", $mobile_number);

                foreach($arr as $ar){

                    if ($ar!="")
                        $mobile_number = $ar;
                }

                }

                }

  	$c = new Criteria();
  	$c->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
  	$c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
  	//echo $c->toString(); exit;
  	$customer = CustomerPeer::doSelectOne($c);

  	if ($customer)
  	{
  		//change the password to some thing uniuque and complex
  		$new_password = substr(base64_encode($customer->getPassword()),0,8);
  		$customer->setPassword($new_password);
                $customer->setPlainText($new_password);
                $customer->save();

  		$message_body = $this->getContext()->getI18N()->__('Hi'). ' ' . $customer->getFirstName().'!';
  		$message_body .= '<br /><br />';
  		$message_body .= $this->getContext()->getI18N()->__('Dit password er blevet ?ndret. Benyt venligst f?lgende oplysninger til at logge p? din Zerocall konto.');
  		$message_body .= '<br /><br />';
  		$message_body .= sprintf('Mobil nummer: %s', $customer->getMobileNumber());
  		$message_body .= '<br />';
  		$message_body .= $this->getContext()->getI18N()->__('Adgangskode'). ': '.$new_password;

                //Send Email to User --- when Forget Password Request Come --- 01/15/11
                emailLib::sendForgetPasswordEmail($customer,$message_body);

                $this->getUser()->setFlash('send_password_message', 'Your account details have been sent to your email address.');



                echo 'success';
        }
         else{
             echo 'error, mobile number does not exists';
             $this->getUser()->setFlash('send_password_error_message', 'No customer is registered with this email.');
         }

         return sfView::NONE;
        }







//
    public function executeMobAccepted(sfWebRequest $request)
	{
    $order_id = $request->getParameter("orderid");

	  	$this->forward404Unless($order_id || $order_amount);

		$order = CustomerOrderPeer::retrieveByPK($order_id);

		$subscription_id = $request->getParameter("subscriptionid");
	  	$order_amount = ((double)$request->getParameter('amount'))/100;

	  	$this->forward404Unless($order);

	  	$c = new Criteria;
	  	$c->add(TransactionPeer::ORDER_ID, $order_id);

	  	$transaction = TransactionPeer::doSelectOne($c);

	  	//echo var_dump($transaction);

	  	$order->setOrderStatusId(sfConfig::get('app_status_completed', 3)); //completed
	  	//$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 3)); //completed
	  	$transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3)); //completed




		if($transaction->getAmount() > $order_amount){
	  		//error
	  		$order->setOrderStatusId(sfConfig::get('app_status_error', 5)); //error in amount
	  		$transaction->setTransactionStatusId(sfConfig::get('app_status_error', 5)); //error in amount
	  		//$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 5)); //error in amount


	  	} else if ($transaction->getAmount() < $order_amount){
	  		//$extra_refill_amount = $order_amount;
	  		$order->setExtraRefill($order_amount);
	  		$transaction->setAmount($order_amount);
	  	}





		 //set active agent_package in case customer was registerred by an affiliate
		  if ($order->getCustomer()->getAgentCompany())
		  {
		  	$order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
		  }


		  //set subscription id in case 'use current c.c for future auto refills' is set to 1
		  if ($request->getParameter('USER_ATTR_20')=='1')
			$order->getCustomer()->setSubscriptionId($subscription_id);

		//set subscription id also when there is was no subscription for old customers
		if (!$order->getCustomer()->getSubscriptionId())
			$order->getCustomer()->setSubscriptionId($subscription_id);

	  	//set auto_refill amount
	  	if ($is_auto_refill_activated = $request->getParameter('USER_ATTR_1')=='1')
	  	{
	  		//set subscription id
			$order->getCustomer()->setSubscriptionId($subscription_id);

			//auto_refill_amount
	  		$auto_refill_amount_choices = array_keys(ProductPeer::getRefillHashChoices());

	  		$auto_refill_amount = in_array($request->getParameter('USER_ATTR_2'), $auto_refill_amount_choices)?$request->getParameter('USER_ATTR_2'):$auto_refill_amount_choices[0];
	  		$order->getCustomer()->setAutoRefillAmount($auto_refill_amount);


	  		//auto_refill_lower_limit
	  		$auto_refill_lower_limit_choices = array_keys(ProductPeer::getAutoRefillLowerLimitHashChoices());

	  		$auto_refill_min_balance = in_array($request->getParameter('USER_ATTR_3'), $auto_refill_lower_limit_choices)?$request->getParameter('USER_ATTR_3'):$auto_refill_lower_limit_choices[0];
	  		$order->getCustomer()->setAutoRefillMinBalance($auto_refill_min_balance);
	  	}
                else {
                    //disable the auto-refill feature
                    $order->getCustomer()->setAutoRefillAmount(0);

                }



	  	$order->save();
	  	$transaction->save();



	$this->customer = $order->getCustomer();
                $c = new Criteria;
	  	$c->add(CustomerPeer::ID, $order->getCustomerId());
	  	$customer = CustomerPeer::doSelectOne($c);
                $agentid=$customer->getReferrerId();
                $productid=$order->getProductId();
                $transactionid=$transaction->getId();
                if(isset($agentid) && $agentid!=""){
                commissionLib::refilCustomer($agentid,$productid,$transactionid);

                }

	//TODO ask if recharge to be done is same as the transaction amount
	Fonet::recharge($this->customer, $transaction->getAmount());





// Update cloud 9
        c9Wrapper::equateBalance($this->customer);


	//set vat
	$vat = 0;
        $subject = $this->getContext()->getI18N()->__('Payment Confirmation');
	$sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
	$sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

	$recepient_email = trim($this->customer->getEmail());
	$recepient_name = sprintf('%s %s', $this->customer->getFirstName(), $this->customer->getLastName());
        $referrer_id = trim($this->customer->getReferrerId());

        if($referrer_id):
        $c = new Criteria();
        $c->add(AgentCompanyPeer::ID, $referrer_id);

        $recepient_agent_email  = AgentCompanyPeer::doSelectOne($c)->getEmail();
        $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        endif;

	//send email
  	$message_body = $this->getPartial('payments/order_receipt', array(
  						'customer'=>$this->customer,
  						'order'=>$order,
  						'transaction'=>$transaction,
  						'vat'=>$vat,
  						'wrap'=>false
  					));



	/*
  	require_once(sfConfig::get('sf_lib_dir').'/swift/lib/swift_init.php');

	$connection = Swift_SmtpTransport::newInstance()
				->setHost(sfConfig::get('app_email_smtp_host'))
				->setPort(sfConfig::get('app_email_smtp_port'))
				->setUsername(sfConfig::get('app_email_smtp_username'))
				->setPassword(sfConfig::get('app_email_smtp_password'));

	$mailer = new Swift_Mailer($connection);

	$message_1 = Swift_Message::newInstance($subject)
	         ->setFrom(array($sender_email => $sender_name))
	         ->setTo(array($recepient_email => $recepient_name))
	         ->setBody($message_body, 'text/html')
	         ;

	$message_2 = Swift_Message::newInstance($subject)
	         ->setFrom(array($sender_email => $sender_name))
	         ->setTo(array($sender_email => $sender_name))
	         ->setBody($message_body, 'text/html')
	         ;

            if (!($mailer->send($message_1) && $mailer->send($message_2)))
                $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__(
                            "Email confirmation is not sent" ));
            */

            //This Seciton For Make The Log History When Complete registration complete - Agent
            //echo sfConfig::get('sf_data_dir');
            $invite_data_file = sfConfig::get('sf_data_dir').'/invite.txt';
            $invite2 = "Customer Refill Account \n";
            $invite2 .= "Recepient Email: ".$recepient_email.' \r\n';
            $invite2 .= " Agent Email: ".$recepient_agent_email.' \r\n';
            $invite2 .= " Sender Email: ".$sender_email.' \r\n';

            file_put_contents($invite_data_file, $invite2, FILE_APPEND);


            //Send Email to User/Agent/Support --- when Customer Refilll --- 01/15/11
            emailLib::sendCustomerRefillEmail($this->customer,$order,$transaction);
    $this->setLayout(false);
	}

     
        public function executeAppRefill(sfWebRequest $request)
	{

    //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11
            changeLanguageCulture::languageCulture($request,$this);
            //-----------------------

        //$this->redirectUnless($this->getUser()->isAuthenticated(),'@b2c_homepage');

		//$customer_id = $this->getUser()->getAttribute('customer_id',null, 'usersession');

		//TODO: authentication is missing
        $mobile_number = $request->getParameter('mobile_number');


        $mobile = "";
        $country_code="";
        $this->customer=NULL;


         if(strlen($mobile_number)==13){
//                    echo 'number is 13 digit';
//                    echo '<br/>';
                    $arr  = explode("001", $mobile_number);
                    foreach($arr as $ar){
//                    echo $ar;
//                    echo '<br/>';
                    if ($ar!="")
                        $mobile_number = $ar;
                    }
                }else if (strlen($mobile_number) == 10 ){
//                     echo 'number is 10 digit';
//                     echo '<br/>';

                    $arr  = explode("45", $mobile_number);

                foreach($arr as $ar){
//                    echo $ar;
//                    echo '<br/>';
                    if ($ar!="")
                        $mobile_number = $ar;
                }

                }

         $c = new Criteria();
	 $c->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
	 $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
	 $customer = CustomerPeer::doSelectOne($c);

        if ($customer!=NULL){
//                echo 'customer found';
		$customer_id = $customer->getId();
//                echo $customer_id ;

		$this->customer = $customer;

		//$this->redirectUnless($this->customer, "@homepage");

		$this->form = new ManualRefillForm($customer_id);

                //echo $this->customer->getId();
		//new order
		$this->order = new CustomerOrder();

		$customer_products = $this->customer->getProducts();

		$this->order->setProduct($customer_products[0]);
		$this->order->setCustomerId($this->customer->getId());
		$this->order->setQuantity(0);
		$refills_options = ProductPeer::getRefillChoices();
		$this->order->setExtraRefill($refills_options[0]);
		$this->order->save();

		//new transaction
                $transaction = new Transaction();

                $transaction->setAmount($this->order->getExtraRefill());
                $transaction->setDescription($this->getContext()->getI18N()->__('LandNCall AB Refill'));
                $transaction->setOrderId($this->order->getId());
                $transaction->setCustomerId($this->order->getCustomerId());

		//save
		$transaction->save();
             $this->setLayout(false);

	}else{
            echo 'error, customer not found';
             return sfView::NONE;
        }
        }




  public function executeSetAllFonetCustomersBalance0() {
      foreach (FonetCustomerPeer::doSelect(new Criteria()) as $fonet_customer) {
                $customer = new Customer();
                $customer->setFonetCustomerId($fonet_customer->getFonetCustomerId());

              if ($current_balance = Fonet::getBalance($customer, false))
                      echo sprintf("Current balance of custoemr is %s<br />", $current_balance);

              if (Fonet::recharge($customer, -$current_balance, false))
                      echo sprintf("Now new balance is %s<br />", Fonet::getBalance($customer, false));
      }

      return sfView::NONE;
  }

public function executeUnregisterFonetCustomer(sfWebRequest $request) {
      $customer = new Customer();
      $customer->setMobileNumber($request->getParameter('mobile_number'));
      $customer->setFonetCustomerId($request->getParameter('fonet_id'));

      echo sprintf("Unregistering customer with mobile number %s, and fonet ID %s <br />", $request->getParameter('mobile_number'), $request->getParameter('fonet_id'));

      if (Fonet::unregister($customer, false))
              echo sprintf("%s is unregistered<br />", $customer->getMobileNumber());
      if ($current_balance = Fonet::getBalance($customer, false))
              echo sprintf("Current balance of custoemr is %s<br />", $current_balance);

      if (Fonet::recharge($customer, -$current_balance, false))
              echo sprintf("current balance is made 0<br />");

      return sfView::NONE;
  }


  public function executeAutoRefill(sfWebRequest $request)
  {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request,$this);
            
  	//get customers to refill
  	$c = new Criteria();

  	$c->add(CustomerPeer::CUSTOMER_STATUS_ID, sfConfig::get('app_status_completed'));
  	$c->add(CustomerPeer::AUTO_REFILL_AMOUNT, 0, Criteria::NOT_EQUAL);
  	$c->add(CustomerPeer::SUBSCRIPTION_ID, null, Criteria::ISNOTNULL);

  	//$c1 = $c->getNewCriterion(CustomerPeer::LAST_AUTO_REFILL, 'TIMESTAMPDIFF(MINUTE, LAST_AUTO_REFILL, NOW()) > 1' , Criteria::CUSTOM);
        $c1 = $c->getNewCriterion(CustomerPeer::ID, null, Criteria::ISNOTNULL); //just accomodate missing disabled $c1
  	$c2 = $c->getNewCriterion(CustomerPeer::LAST_AUTO_REFILL, null, Criteria::ISNULL);

  	$c1->addOr($c2);

  	$c->add($c1);

  	$epay_con = new EPay();

  	$customer = new Customer();

        var_dump(CustomerPeer::doCount($c));


        try {
            foreach (CustomerPeer::doSelect($c) as $customer)   	{

                $customer_balance = Fonet::getBalance($customer);

                var_dump($customer_balance);
                //if customer balance is less than 10
                if ($customer_balance != null && $customer_balance <= $customer->getAutoRefillMinBalance())   		{



                    //create an order and transaction
                    $customer_order = new CustomerOrder();
                    $customer_order->setCustomer($customer);

                    //select order product
                    $c = new Criteria();
                    $c->add(CustomerProductPeer::CUSTOMER_ID, $customer->getId());
                    $customer_product = CustomerProductPeer::doSelectOne($c);

                    var_dump(CustomerProductPeer::doCount($c));



                    $customer_order->setProduct($customer_product->getProduct());
                    $customer_order->setQuantity(1);
                    $customer_order->setExtraRefill($customer->getAutoRefillAmount());


                    //create a transaction
                    $transaction = new Transaction();
                    $transaction->setCustomer($customer);
                    $transaction->setAmount($customer->getAutoRefillAmount());
                    $transaction->setDescription('Auto refill');



                    //associate transaction with customer order
                    $customer_order->addTransaction($transaction);

                    //save order to get order_id that is required to create a transaction via epay api
                    $customer_order->save();



                    if ($epay_con->authorize(sfConfig::get('app_epay_merchant_number'), $customer->getSubscriptionId(), $customer_order->getId(), $customer->getAutoRefillAmount(), 208, 1)) 			{
                        $customer->setLastAutoRefill(date('Y-m-d H:i:s'));
                        $customer_order->setOrderStatusId(sfConfig::get('app_status_completed'));
                        $transaction->setTransactionStatusId(sfConfig::get('app_status_completed'));
                    }
                    else {
                        die('unauthorized epay');
                    }

                    $customer->save();
                    $customer_order->save();

                    if ($customer_order->getOrderStatusId() == sfConfig::get('app_status_completed') &&
                            Fonet::recharge($customer, $customer->getAutoRefillAmount()))   			{

                        $this->customer = $customer;
                        $TelintaMobile = '46'.$this->customer->getMobileNumber();
                        $emailId = $this->customer->getEmail();
                        $OpeningBalance = $customer->getAutoRefillAmount();
                        $customerPassword = $this->customer->getPlainText();
                        $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
                        if($getFirstnumberofMobile==0){
                            $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                            $TelintaMobile =  '46'.$TelintaMobile ;
                        }else{
                            $TelintaMobile = '46'.$this->customer->getMobileNumber();
                        }
                        $uniqueId = $this->customer->getUniqueid();
                      //This is for Recharge the Customer
                       $MinuesOpeningBalance = $OpeningBalance*3;
                       Telienta::recharge($this->customer, $OpeningBalance);
                      //This is for Recharge the Account
                      //this condition for if follow me is Active
                        $getvoipInfo = new Criteria();
                        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customer->getMobileNumber());
                        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                        if(isset($getvoipInfos)){
                            $voipnumbers = $getvoipInfos->getNumber() ;
                            $voip_customer = $getvoipInfos->getCustomerId() ;
                        }else{


                           }
                      
                     
                      $MinuesOpeningBalance = $OpeningBalance*3;
                      

			//update cloud 9
			c9Wrapper::equateBalance($customer);


						//send invoices

                        $message_body = $this->getPartial('customer/order_receipt', array(
                                    'customer' => $customer,
                                    'order' => $customer_order,
                                    'transaction' => $transaction,
                                    'vat' => 0,
                                    'wrap' => false
                                ));

                            $subject = $this->getContext()->getI18N()->__('Payment Confirmation');
                            $sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
                            $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

                            $recepient_email = trim($this->customer->getEmail());
                            $recepient_name = sprintf('%s %s', $this->customer->getFirstName(), $this->customer->getLastName());


                            //This Seciton For Make The Log History When Complete registration complete - Agent
                            //echo sfConfig::get('sf_data_dir');
                            $invite_data_file = sfConfig::get('sf_data_dir').'/invite.txt';
                            $invite2 = " AutoRefill - pScript \n";
                            $invite2 = "Recepient Email: ".$recepient_email.' \r\n';


                            //Send Email to User/Agent/Support --- when Agent register Customer --- 01/15/11
                            emailLib::sendCustomerAutoRefillEmail($this->customer,$message_body);

  			}
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        return sfView::NONE;
  }

public function executeTest(sfWebrequest $request){
        $form = new Curl_HTTP_Client();

  $form->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
                    $form->set_referrer("http://landncall.zerocall.com");
                    $post_data = array(
                        'Account' =>'cb4529900000',
                        'Password' =>'asdf1asd',
                        'Action' =>'Connect Us Now!',
                        'First_Phone_Number' =>'923006826451',
                        'Second_Phone_Number' =>'923218478166'
                       
                    );
 

                  $html_data = $form->send_post_data("https://mybilling.zerocall.com:8900/cgi/web/receive.pl", $post_data);





 
    return sfView::NONE;
}
  public function executeTest1(sfWebRequest $request)
  {

  	if (true && $this->run())
  	{
  		echo 'sadi';
  	}

  	echo sha1('test1');

  	exit;

  	$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
	$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
	$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
	$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';

	$epay_con = new EPay();

	$result = $epay_con->authorize(8884184, 66529, 3923, 400, 208, 1);

	if ($result)
		var_dump($result);
	else
		echo "<pre>".$epay_con->getLastError()."</pre>";

  	exit;

  	$field_value = '1280820350';
  	$field_value_2 = '1280820403';

  	$date1 = date("Y:m:d H:i:s", $field_value);
  	$date2 = date("Y:m:d H:i:s", $field_value_2);

  	$date_dur = date("H:i:s", $field_value_2-$field_value);

  	echo '<br />';

  	echo sprintf("%s<br />%s<br />%s", $date1, $date2, $date_dur );

  	return sfView::NONE;
  }

  public function executeProcessCdr(sfWebRequest $request)
  {

   //list any files in data/new folder

 //$cdr_root_dir = 'c:/zerocall_cdr';
 $cdr_root_dir = '/home/fonet_cdr/landncall_cdr';

 //TODO: need to fix the new_dir path on server
 $new_dir = $cdr_root_dir;
 $error_dir = $cdr_root_dir.'/error';
 $backup_dir = $cdr_root_dir.'/backup';


 $files = scandir($new_dir);
 
$ignore = array('.', '..', 'backup_staging_error','backup_staging', 'backup', 'error');
$files = array_diff($files, $ignore);

if (count($files)==0)
{
        echo 'No file to process in "'.$new_dir.'"';
        exit(1);
}

echo sprintf("There are %d files <br/>", count($files));

print_r($files);


 //get connection
 $con = Propel::getConnection();

        $cdr_rows = array();

 foreach ($files as $file)
 {

  $splits = explode('.', $file);
  $file_extension = end($splits);

  //start transaction;

  if( $file_extension=='csv' )
  {
   try
   {
      $reader = new sfCsvReader($new_dir.'/'.$file);

      $reader->open();


      //begin transaction
      $con->beginTransaction();

    while ($data = $reader->read())
    {
     /*
     0 CdrKey
     1 CustomID
     2 AnswerTimeB
     3 EndTimeB
     4 BillSec
     5 BillingTime
     6 Extension
     7 SourceCty
     8 Ani
     9 DestCty
     10 Rounding
     11 UsedValue
     12 InitialAccount
     13 DST_CustomID
     14 DestinationName
     15 COST_RateMatchPhno
     16 COST_DestinationName
     17 COST_RateValue
     18 COST_RateValueFirst
     19 COST_CcsConnectCharge
     20 COST_UsedValue
     21 BZ2_Rate1Minute
     22 BZ1_RateAddMinute
     */

     $cdr_row = new ZerocallCdr();

     $cdr_row->setCdrkey($data[0]);
     $cdr_row->setCustomid($data[1]);
     $cdr_row->setAnswertimeb($data[2]);
     $cdr_row->setEndtimeb($data[3]);
     $cdr_row->setBillsec($data[4]);
     $cdr_row->setBillingtime($data[5]);
     $cdr_row->setExtension($data[6]);
     $cdr_row->setSourcecty($data[7]);
     $cdr_row->setAni($data[8]);
     $cdr_row->setDestcty($data[9]);
     $cdr_row->setRounding($data[10]);
     $cdr_row->setUsedvalue($data[11]);
     $cdr_row->setInitialaccount($data[12]);
     $cdr_row->setDstCustomid($data[13]);
     $cdr_row->setDestinationname($data[14]);
     $cdr_row->setCostRatematchphno($data[15]);
     $cdr_row->setCostDestinationname($data[16]);
     $cdr_row->setCostRatevalue($data[17]);
     $cdr_row->setCostRatevaluefirst($data[18]);
     $cdr_row->setCostCcsconnectcharge($data[19]);
     $cdr_row->setCostUsedvalue($data[20]);
     $cdr_row->setBz2Rate1minute($data[21]);
     $cdr_row->setBz1Rateaddminute($data[22]);

     //save the row
     try {
     $cdr_row->save($con);
     $cdr_rows[] = $cdr_row;
     }
     catch (FileException  $ex) {
         throw $ex->getMessage();
     }



    } // end while

    $reader->close();


    //commit
    $con->commit();



    rename("$new_dir/$file", "$backup_dir/$file");

   }
   catch (Exception $ex)
   {
    //rollback
    $con->rollback();
    
    //move the file to error
    rename("$new_dir/$file", "$error_dir/$file");

    echo "<br />";
    echo $ex->getLine();
    echo " : ";
    echo $ex->getCode();
    echo " : ";
    echo $ex->getMessage();
    echo "<br />";



   }


  } //end if

 } //end for each

//foreach ($cdr_rows as $cdr_row) {

	//try deducting the c9 balance
	//$c = new Criteria();
	//$c->add(CustomerPeer::FONET_CUSTOMER_ID, $cdr_row->getCustomid());

	//$c9_customer = CustomerPeer::doSelectOne($c);

        //$ch = new CallHistory();
	//c9Wrapper::equateBalance($c9_customer);

        //only change c9 balance if there are fonet and c9 id for a customer
//        if ($customer && $customer->getFonetCustomerId() && $customer->getC9CustomerNumber()) {
//            $c9_customer_number = $customer->getC9CustomerNumber();
//
//            //convert SEK to ggp
//            $conversion_rate = CurrencyConversionPeer::retrieveByPK(1);
//
//            $exchange_rate = $conversion_rate->getDkkBpp();
//
//            $fonet_balance_dkk = Fonet::getBalance($customer);
//
//            $fonet_balance_bpp = $fonet_balance_dkk / $exchange_rate;
//
//            //subtract c9 balance from fonet balance
//            //subtract fonet call charge from c9 userbalance
//            $c9_balance = floatval(c9Wrapper::getBalance('12345', $customer->getC9CustomerNumber()));
//            $c9_new_balance = $fonet_balance_bpp - $c9_balance;
//
//
//            //c9Wrapper::updateBalance('12345', $customer->getC9CustomerNumber(), $c9_new_balance);
//
//        }

//}
//end c9 update

 echo 'Each CDR is iterated from ' . $new_dir;


   return sfView::NONE;
}



  public function executeRemoveInactiveUsers(sfWebRequest $request)
  {
  	$c = new Criteria();

  	$c->add(CustomerOrderPeer::CUSTOMER_ID,
  		'customer_id IN (SELECT id FROM customer WHERE TIMESTAMPDIFF(MINUTE, NOW(), created_at) >= -30 AND customer_status_id = 1)'
  	, Criteria::CUSTOM);

  	$this->remove_propel_object_list(CustomerOrderPeer::doSelect($c));

  	//now transaction
  	$c = new Criteria();

  	$c->add(TransactionPeer::CUSTOMER_ID,
  		'customer_id IN (SELECT id FROM customer WHERE TIMESTAMPDIFF(MINUTE, NOW(), created_at) >= -30 AND customer_status_id = 1)'
  	, Criteria::CUSTOM);

  	$this->remove_propel_object_list(TransactionPeer::doSelect($c));

  	//now customer
   	$c = new Criteria();

  	$c->add(CustomerPeer::ID,
  		'id IN (SELECT id FROM customer WHERE TIMESTAMPDIFF(MINUTE, NOW(), created_at) >= -30 AND customer_status_id = 1)'
  	, Criteria::CUSTOM);

  	$this->remove_propel_object_list(CustomerPeer::doSelect($c));

  	$this->renderText('last deleted on '. date(DATE_RFC822));

  	return sfView::NONE;

  }


  private function is_mobile_number_exists($mobile_number)
  {
  	$c = new Criteria();

  	$c->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);

  	 if (CustomerPeer::doSelectOne($c))
  	 	return true;
  }

  private function remove_propel_object_list($list)
  {
  	foreach($list as $list_item)
  	{
  		$list_item->delete();
  	}
  }

  public function executeSendEmails(sfWebRequest $request)
  {

  require_once(sfConfig::get('sf_lib_dir').'/swift/lib/swift_init.php');


        echo 'starting the debug';
        echo '<br/>';
        echo sfConfig::get('app_email_smtp_host');
        echo '<br/>';
        echo sfConfig::get('app_email_smtp_port');
        echo '<br/>';
        echo sfConfig::get('app_email_smtp_username');
        echo '<br/>';
        echo sfConfig::get('app_email_smtp_password');
        echo '<br/>';
        echo sfConfig::get('app_email_sender_email', 'support@landncall.com');
        echo '<br/>';
        echo sfConfig::get('app_email_sender_name', 'LandNCall AB support');
        

  	$connection = Swift_SmtpTransport::newInstance()
			->setHost(sfConfig::get('app_email_smtp_host'))
			->setPort(sfConfig::get('app_email_smtp_port'))
			->setUsername(sfConfig::get('app_email_smtp_username'))
			->setPassword(sfConfig::get('app_email_smtp_password'));




	$sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
	$sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

        echo '<br/>';
        echo $sender_email ;
        echo '<br/>';
        echo $sender_name ;


	$mailer = new Swift_Mailer($connection);

  	$c = new Criteria();
  	$c->add(EmailQueuePeer::EMAIL_STATUS_ID, sfConfig::get('app_status_completed'), Criteria::NOT_EQUAL);
        $emails = EmailQueuePeer::doSelect($c);
  try{
  	foreach( $emails as $email)
  	{
                

		$message = Swift_Message::newInstance($email->getSubject())
		         ->setFrom(array($sender_email => $sender_name))
		         ->setTo(array($email->getReceipientEmail() => $email->getReceipientName()))
		         ->setBody($email->getMessage(), 'text/html')
		         ;

//                $message = Swift_Message::newInstance($email->getSubject())
//		         ->setFrom(array("support@landncall.com"))
//		         ->setTo(array("mohammadali110@gmail.com"=>"Mohammad Ali"))
//		         ->setBody($email->getMessage(), 'text/html')
//		         ;
                echo 'inside loop';
                echo '<br/>';
               
                echo $email->getId();
                echo '<br/>';
                echo '<br/>';

                //This Conditon Add Update Row Which Have the 
		 if($email->getReceipientEmail()!=''){
                    @$mailer->send($message);
                    $email->setEmailStatusId(sfConfig::get('app_status_completed'));
                    //TODO:: add sent_at too
                    $email->save();
                    echo sprintf("Send to %s<br />", $email->getReceipientEmail());
		}

                

  	}
        }catch (Exception $e){

                    echo $e->getLine();
                    echo $e->getMessage();
                }
  	return sfView::NONE;
  }


public function executeTest2(sfWebRequest $request){
        echo("test test");
       return sfView::NONE;
  }


  public function executeC9invoke(sfWebRequest $request)
  {

        $this->logMessage(print_r($_POST, true));

    // creating model object
	$c9Data = new cloud9_data();

	//setting data in model
        $c9Data->setRequestType($request->getParameter('request_type'));
        $c9Data->setC9Timestamp($request->getParameter('timestamp'));
        $c9Data->setTransactionID($request->getParameter('transactionid'));
        $c9Data->setCallDate($request->getParameter('call_date'));
        $c9Data->setCdr($request->getParameter('cdr_id'));
        $c9Data->setCid($request->getParameter('carrierid'));
        $c9Data->setMcc($request->getParameter('mcc'));
        $c9Data->setMnc($request->getParameter('mnc'));
        $c9Data->setImsi($request->getParameter('imsi'));
        $c9Data->setMsisdn($request->getParameter('msisdn'));
        $c9Data->setDestination($request->getParameter('destination'));
        $c9Data->setLeg($request->getParameter('leg'));
        $c9Data->setLegDuration($request->getParameter('leg_duration'));
        $c9Data->setResellerCharge($request->getParameter('reseller_charge'));
        $c9Data->setClientCharge($request->getParameter('client_charge'));
        $c9Data->setUserCharge($request->getParameter('user_charge'));
        $c9Data->setIot($request->getParameter('IOT'));
        $c9Data->setUserBalance($request->getParameter('user_balance'));

//saving model object in Database	
        $c9Data->save();



        $conversion_rate = CurrencyConversionPeer::retrieveByPK(1);

        $exchange_rate = $conversion_rate->getBppDkk();

        $amt_bpp = $c9Data->getUserBalance();

        $amt_dkk = $amt_bpp * $exchange_rate;

//find the customer.

            $c = new Criteria();
            $c->add(CustomerPeer::C9_CUSTOMER_NUMBER, $c9Data->getMsisdn());            
            $customer = CustomerPeer::doSelectOne($c);

            
//get fonet balance

            $fonet = new Fonet();
            $balance = $fonet->getBalance($customer, true);

//update Balance on Fonet if there's a difference

        if ($fonet->recharge($customer,number_format($amt_dkk-$balance, 2) , true)){

//if fonet customer found, send success response.

        $this->getResponse()->setContentType("text/xml");
        $this->getResponse()->setContent("<?xml version=\"1.0\"?>
        <CDR_response>
        <cdr_id>".$request->getParameter('cdr_id')."</cdr_id>
        <cdr_status>1</cdr_status>
        </CDR_response> ");
            }
      
        return sfView::NONE;
  }


  public function c9_follow_up(Cloud9Data $c9Data){

         echo("inside follow up \n: ");



        echo("calculcated amount: ");
        echo($amt_dkk);

//
//        $balance = $amt * $exchange_rate->getBppDkk();
//
//        echo($balance);
//
//        //echo($user_balance_dkk);
//
//        $cust = CustomerPeer::retrieveByPK(22);
//
//        $cust->setC9CustomerNumber($balance);
//
//        $cust->save();
//
//        return $cust;


//            echo('hello/');
//            $customer = CustomerPeer::retrieveByPK(1);
//            echo('world/');
//
//            $fonet = new Fonet();
//            $balance = $fonet->getBalance($customer, true);
//            echo('hilo/');
//            echo($balance);
//            echo('verden/');
//
//            $fonet->recharge($customer, -20, true);
//            echo('hilo 2/');
//            $balance = $fonet->getBalance($customer, true);
//            echo('hilo 3/');
//            echo($balance);

//            echo('world');
            //echo($balance->getBalance(&$customer));


  }

public function unregisterFonet(sfWebRequest $request){


$fonetId = $request->getParameter('fonetId');

			$c = new Criteria();
            $c->add(CustomerPeer::FONET_CUSTOMER_ID, $fonetId);
            $customer = CustomerPeer::doSelectOne($c);

			$result = Fonet::unregister($customer, true);

			echo $result;

			return sfView::NONE;

}
 public function executeBalanceAlert(sfWebRequest $request)
  {
      $username= 'zerocall' ;
      $password= 'ok20717786';
      //$c=new Criteria();
      //$fonet=new Fonet();
    //  $customers=CustomerPeer::doSelect($c);
      $balance = $request->getParameter('balance');
      $mobileNo = $request->getParameter('mobile');
      //foreach($customers as $customer)
      //{
      $balance_data_file = sfConfig::get('sf_data_dir').'/balanceTest.txt';
      $baltext = "";
      $baltext .= "Mobile No: {$mobileNo} , Balance: {$balance} \r\n";

      file_put_contents($balance_data_file, $baltext, FILE_APPEND);

          if($mobileNo)
          {
            if($balance < 25 && $balance > 10)
            {
               
               $baltext .= "balance < 25 && balance > 10";
                $data = array(
		      'username' => $username,
                      'password' => $password,
                      'mobile'=>$mobileNo,
                      'message'=>"You balance is below 25 SEK, Please refill your account. LandNCall AB - Support "
			  );
		$queryString = http_build_query($data,'', '&');
		$this->response_text =  file_get_contents('http://sms.gratisgateway.dk/send.php?'.$queryString);
                echo $this->response_text;
            }
            else  if($balance< 10.00 && $balance>0.00)
            {
              
               $data = array(
		      'username' => $username,
                      'password' => $password,
                      'mobile'=>$mobileNo,
                      'message'=>"You balance is below 10 SEK, Please refill your account. LandNCall AB - Support"
			  );
		$queryString = http_build_query($data,'', '&');
		$this->response_text =  file_get_contents('http://sms.gratisgateway.dk/send.php?'.$queryString);
                $baltext .= "balance < 10 && balance > 0";
              
            }
            else if($balance<= 0.00)
            {
                
                
                    $data = array(
                      'username' => $username,
                      'password' => $password,
                      'mobile'=>$mobileNo,
                      'message'=>"You balance is 0 SEK, Please refill your account. LandNCall AB - Support "
			  );
                    $queryString = http_build_query($data,'', '&');
                    $this->response_text =  file_get_contents('http://sms.gratisgateway.dk/send.php?'.$queryString);
                    $baltext .= "balance 0";
                
            }
          }


      $baltext .= $this->response_text;
      file_put_contents($balance_data_file, $baltext, FILE_APPEND);

      
      $data = array(
            'mobile' => $mobileNo,
            'balance' => $balance
            );

      $queryString = http_build_query($data,'', '&');
      $this->redirect('pScripts/balanceAlert?'.$queryString);

      

      return sfView::NONE;

  }
  
  public function executeUsageAlerttest(sfWebRequest $request)
  {
            $delievry="";
            $number = "923214745120";
            $sms_text = "Its Test Message";
            $data = array(
              'S' => 'H',
              'UN'=>'zapna1',
              'P'=>'Zapna2010',
              'DA'=>$number,
              'SA' => 'LandNcall',
              'M'=>$sms_text,
              'ST'=>'5'
            );
            $queryString = http_build_query($data,'', '&');
            //   die;
            sleep(0.5);

            $queryString=smsCharacter::smsCharacterReplacement($queryString);

            $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
            $this->res_cbf = 'Response from CBF is: ';
            $this->res_cbf .= $res;
            //echo $res;
            $delievry .= 'Destination: '.$number.', Status: '.$res.'<br/>';
            
            $this->delievry = $delievry;
  }
  public function executeBalanceEmail(sfWebRequest $request)
  {
      

      $balance = $request->getParameter('balance');
      $mobileNo = $request->getParameter('mobile');

      $email_data_file = sfConfig::get('sf_data_dir').'/EmailAlert.txt';
      $email_msg = "";
      $email_msg .= "Mobile No: {$mobileNo} , Balance: {$balance} \r\n";
	  file_put_contents($email_data_file, $email_msg, FILE_APPEND);

      //$fonet=new Fonet();
      //
      
      $c=new Criteria();
      $c->add(CustomerPeer::MOBILE_NUMBER,$mobileNo);
      $customers=CustomerPeer::doSelect($c);
      $recepient_name='';
      $recepient_email='';
      foreach($customers as $customer)
      {
        $recepient_name=$customer->getFirstName().' '.$customer->getLastName();
        $recepient_email=$customer->getEmail();
      }

      
      //$recepient_name=
      //foreach($customers as $customer)
      //{
     
     file_put_contents($email_data_file, $email_msg, FILE_APPEND);
     
          if($mobileNo)
          {
            if($balance < 25.00 && $balance > 10.00)
            {
                   $email_msg .= "\r\n balance < 25 && balance > 10";
                    //echo 'mail sent to you';
                   $subject         = 'Test Email: Balance Email ' ;
                   $message_body    = "Test Email:  Your balance is below 25dkk , please refill otherwise your account will be closed. \r\n - Zerocall Support \r\n Company Contact Info";

                    //This Seciton For Make The Log History When Complete registration complete - Agent
                    //echo sfConfig::get('sf_data_dir');
                    $invite_data_file = sfConfig::get('sf_data_dir').'/invite.txt';
                    $invite2 = " Balance Email - pScript \n";
                    if ($recepient_email):
                        $invite2 = "Recepient Email: ".$recepient_email.' \r\n';
                    endif;

                    //Send Email to Customer For Balance --- 01/15/11
                    emailLib::sendCustomerBalanceEmail($customers,$message_body);

                                     
            }
            else  if($balance< 10.00 && $balance>0.00)
            {

               $email_msg .= "\r\n balance < 10 && balance > 0";
               $subject= 'Test Email: Balance Email ' ;
               $message_body= "Test Email:  Your balance is below 10dkk , please refill otherwise your account will be closed. \r\n - Zerocall Support \r\n Company Contact Info";

                    //This Seciton For Make The Log History When Complete registration complete - Agent
                    //echo sfConfig::get('sf_data_dir');
                    $invite_data_file = sfConfig::get('sf_data_dir').'/invite.txt';
                    $invite2 = " Balance Email - pScript \n";
                    if ($recepient_email):
                        $invite2 = "Recepient Email: ".$recepient_email;
                    endif;

                    //Send Email to Customer For Balance --- 01/15/11
                    emailLib::sendCustomerBalanceEmail($customers,$message_body);
                    
            }
            else if($balance<= 0.00)
            {
                $email_msg .= "\r\n balance < 10 && balance > 0";
                $subject= 'Test Email: Balance Email ' ;
                $message_body= "Test Email:  Your balance is 0 SEK, please refill otherwise your account will be closed. \r\n - LandNCall AB Support \r\n Company Contact Info";

                //This Seciton For Make The Log History When Complete registration complete - Agent
                //echo sfConfig::get('sf_data_dir');
                $invite_data_file = sfConfig::get('sf_data_dir').'/invite.txt';
                $invite2 = " Balance Email - pScript \n";
                if ($recepient_email):
                    $invite2 = "Recepient Email: ".$recepient_email;
                endif;
                    
                //Send Email to Customer For Balance --- 01/15/11
                emailLib::sendCustomerBalanceEmail($customers,$message_body);
            }
          }


      $email_msg .= $message_body;
      $email_msg .= "\r\n Email Sent";
      file_put_contents($email_data_file, $email_msg, FILE_APPEND);
      return sfView::NONE;

  }

   public function executeWebSms(sfWebRequest $request)
	{
            require_once(sfConfig::get('sf_lib_dir').'\SendSMS.php');
            require_once(sfConfig::get('sf_lib_dir').'\IncomingFormat.php');
            require_once(sfConfig::get('sf_lib_dir').'\ClientPolled.php');


            //$sms_username = "zapna01";
            //$sms_password = "Zapna2010";

            


            $replies = send_sms_full("923454375829","CBF", "Test SMS: Taisys Test SMS form test.Zerocall.com"); //or die ("Error: " .$errstr. " \n");

            //$replies = send_sms("44123456789,44987654321,44214365870","SMS_Service", "This is a message from me.") or die ("Error: " . $errstr . "\n");

            echo "<br /> Response from Taisys <br />";
            echo $replies;
            echo $errstr;
            echo "<br />";

            file_get_contents("http://sms1.cardboardfish.com:9001/HTTPSMS?S=H&UN=zapna1&P=Zapna2010&DA=923454375829&ST=5&SA=Zerocall&M=Test+SMS%3A+Taisys+Test+SMS+form+test.Zerocall.com");

            return sfView::NONE;
        }

        public function executeTaisys(sfWebrequest $request){

            $taisys = new Taisys();

            $taisys->setServ($request->getParameter('serv'));
            $taisys->setImsi($request->getParameter('imsi'));
            $taisys->setDn($request->getParameter('dest'));
            $taisys->setSmscontent($request->getParameter('content'));
            $taisys->setChecksum($request->getParameter('mac'));
            $taisys->setChecksumVerification(true);

            $taisys->save();

			$data = array(
              'S' => 'H',
              'UN'=>'zapna1',
              'P'=>'Zapna2010',
              'DA'=>$taisys->getDn(),
              'SA' => 'Zerocall',
              'M'=>$taisys->getSmscontent(),
              'ST'=>'5'
	);


		$queryString = http_build_query($data,'', '&');
 $queryString=smsCharacter::smsCharacterReplacement($queryString);
		$res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
                $this->res_cbf = 'Response from CBF is: ';
                $this->res_cbf .= $res;

            echo $this->res_cbf;
            return sfView::NONE;


        }



public function executeSmsCode(sfWebRequest $request){

    $c= new Criteria();
    $agents = AgentCompanyPeer::doSelect($c);

    $count=1;
    foreach($agents as $agent){
        $cvr = $agent->getCvrNumber();
        if (strlen($cvr)==4){
        $agent->setSmsCode($cvr);
        $agent->save();
        }
        else{
            $cvr = substr($cvr,0,4);
            $agent->setSmsCode($cvr);
            $agent->save();
        }
        echo $agent->getCvrNumber();
        echo ' : ';
        echo $cvr;
        echo '<br/>';
        $count = $count+1;
    }

    return sfView::NONE;


}

public function executeDeleteValues(sfWebRequest $request){

    $c = new Criteria();
    $orders = CustomerOrderPeer::doSelect($c);

    foreach($orders as $order){
        $cr = new Criteria();
        $cr->add(CustomerPeer::ID, $order->getCustomerId());
        $customer=CustomerPeer::doSelectOne($cr);

        if(!$customer){
            //$order->delete();
            echo $order->getCustomerId();
            echo "<br/>";
        }
    }

    echo "transactions";
    $ct = new Criteria();
    $transactions = TransactionPeer::doSelect($ct);

    foreach($transactions as $transaction){
        $cr = new Criteria();
        $cr->add(CustomerPeer::ID, $transaction->getCustomerId());
        $customer=CustomerPeer::doSelectOne($cr);

        if(!$customer){
            //$transaction->delete();
            echo $transaction->getCustomerId();
            echo "<br/>";
        }
    }

    echo "customer products";
    $cp = new Criteria();
    $cps = CustomerProductPeer::doSelect($cp);

    foreach($cps as $cp){
        $cr = new Criteria();
        $cr->add(CustomerPeer::ID, $cp->getCustomerId());
        $customer=CustomerPeer::doSelectOne($cr);

        if(!$customer){
            //$cp->delete();
            echo $cp->getCustomerId();
            echo "<br/>";
        }
    }

       return sfView::NONE;


}

public function executeRegistrationType(sfWebRequest $request){

    $c = new Criteria();
    $customers=CustomerPeer::doSelect($c);

    foreach($customers as $customer){
        if($customer->getReferrerId()){
            if(!$customer->getRegistrationTypeId() ){
            $customer->setRegistrationTypeId(2);
            $customer->save();
            }

            
        }else{
             $customer->setRegistrationTypeId(1);
             $customer->save();
        }
     
    }
       return sfView::NONE;
}

public function executeGetBalanceAll(){

    $balance=0;
    $total_unassigned=0;
    $total_assigned = 0;

    $c = new Criteria();
    $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
    $customers = CustomerPeer::doSelect($c);

    echo "Total customers: ".count($customers);
    foreach($customers as $customer){
        $balance = Fonet::getBalance($customer);
        if ($balance > 0){
            echo "<br/>";
            echo "Registered: ".$customer->getMobileNumber().", Balance: ".$balance;
            $total_assigned ++;
        }else{
            echo "<br/>";
            echo "Not Registered: ".$customer->getMobileNumber().", Balance: ".$balance;
            $total_unassigned++;
        }
    }

    echo "<br/>";
    echo "Total UnRegistered: ".$total_unassigned++;
    echo "<br/>";
    echo "Total Registered: ".$total_assigned++;
}

public function executeRescueRegister(){

    $balance=0;    
    $already_registered = 0;
    $newly_registered=0;
    $not_registered=0;

    $c = new Criteria();
    $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
    $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 0, Criteria::GREATER_THAN);
    $customers = CustomerPeer::doSelect($c);

    echo "Total customers: ".count($customers);
    
    foreach($customers as $customer){
        
        $balance = Fonet::getBalance($customer);
        if ($balance > 0){
            echo "<br/>";
            echo ++$already_registered.") Already Registered: ".$customer->getMobileNumber().", Balance: ".$balance;
            echo "<br/>";
            
        }else{
            echo "<br/>";
            echo ++$not_registered.") Not Registered: ".$customer->getMobileNumber().", Balance: ".$balance;
            

		$query_vars = array(
			'Action'=>'Activate',
			'ParentCustomID'=>1393238,
	  		'AniNo'=>$customer->getMobileNumber(),
	  		'DdiNo'=>25998893,
			'CustomID'=>$customer->getFonetCustomerId()
	  	);

		$url = 'http://fax.fonet.dk/cgi-bin/ZeroCallV2Control.pl'.'?'.http_build_query($query_vars);
                $res = file_get_contents($url);
                echo "<br/>";
                echo 'Registered :'.$customer->getMobileNumber().", status: ".substr($res,0,2);
                echo ++$newly_registered;

            }
            
            
        }
    
}

public function executeRescueDefaultBalance(sfWebRequest $request){

    $balance=0;
    $already_registered = 0;
    $newly_registered=0;
    $not_registered=0;

    $c = new Criteria();
    $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
    $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 0, Criteria::GREATER_THAN);
    $customers = CustomerPeer::doSelect($c);

    echo "Total customers: ".count($customers);

    foreach($customers as $customer){

        $balance = Fonet::getBalance($customer);
        if ($balance > 0){
            echo "<br/>";
            echo ++$already_registered.") Already Registered: ".$customer->getMobileNumber().", Balance: ".$balance;
            echo "<br/>";

        }else{
            $cp = new Criteria();
            $cp->add(CustomerProductPeer::PRODUCT_ID, 7, Criteria::NOT_EQUAL);
            $cp->add(CustomerProductPeer::CUSTOMER_ID, $customer->getId());
            $customer_product = CustomerProductPeer::doSelectOne($cp);

            if($customer_product){
                $query_vars = array(
                            'Action'=>'Recharge',
                            'ParentCustomID'=>1393238,
                            'CustomID'=>$customer->getFonetCustomerId(),
                            'ChargeValue'=>20*100
                    );

                    $url = 'http://fax.fonet.dk/cgi-bin/ZeroCallV2Control.pl'.'?'.http_build_query($query_vars);
                    $res = file_get_contents($url);
                    echo "<br/>";
                    echo ++$balance_assigned.')Recharged :'.$customer->getMobileNumber().", status: ".substr($res,0,2);
                    echo "<br/>";

        }

}

    }
}

public function getEnableCountryId($calingcode){
      // echo $full_mobile_number = $calingcode;
       $enableCountry = new Criteria();
       $enableCountry->add(EnableCountryPeer::STATUS, 1);
       $enableCountry->add(EnableCountryPeer::LANGUAGE_SYMBOL,'en',Criteria::NOT_EQUAL);
       $enableCountry->add(EnableCountryPeer::CALLING_CODE, '%'.$calingcode.'%', Criteria::LIKE);
       $country_id = EnableCountryPeer::doSelectOne($enableCountry);
       $countryId = $country_id->getId();
       return $countryId;
 
}


public function executeSmsRegisterationwcb(sfWebrequest $request) {
        $urlval = "WCR-" . $request->getURI();
        $dibsCall = new DibsCall();
        $dibsCall->setCallurl($urlval);
        $dibsCall->save();


        $number = $request->getParameter('from');
        $mobileNumber = substr($number, 2, strlen($number) - 2);
        if ($mobileNumber[0] != "0") {
            $mobileNumber = "0" . $mobileNumber;
        }
        $textParamter = $request->getParameter('text');
        $requestType = substr($textParamter, 0, 2);
        $requestType = strtolower($requestType);



        if ($requestType == "hc") {

            $dialerIdLenght = strlen($textParamter);
            $uniqueId = substr($textParamter, $dialerIdLenght - 6, $dialerIdLenght - 1);
            $mnc = new Criteria();
            $mnc->add(CustomerPeer::MOBILE_NUMBER, $mobileNumber);
            $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $cusCount = CustomerPeer::doCount($mnc);
            if ($cusCount < 1) {
                $uc = new Criteria();
                $uc->add(UniqueIdsPeer::UNIQUEID, $uniqueId);
                $uc->addAnd(UniqueIdsPeer::STATUS, 0);
                $callbackq = UniqueIdsPeer::doCount($uc);
            if ($callbackq== 1) {
               $availableUniqueId = UniqueIdsPeer::doSelectOne($uc);
                    $pc = new Criteria();
                    $pc->add(ProductPeer::SMS_CODE, "50");
                    $product = ProductPeer::doSelectOne($pc);
                    $calingcode = sfConfig::get('app_country_code');
                    $password = $this->randomNumbers(6);
                    $customer = new Customer();
                    $customer->setFirstName($mobileNumber);
                    $customer->setLastName($mobileNumber);
                    $customer->setMobileNumber($mobileNumber);
                    $customer->setPassword($password);
                    $customer->setEmail("retail@example.com");
                    $customer->setCountryId(2);
                    $customer->setCity("");
                    $customer->setAddress("");
                    $customer->setTelecomOperatorId(1);
                    $customer->setDeviceId(1474);
                    $customer->setUniqueId($uniqueId);
                    $customer->setCustomerStatusId(3);
                    $customer->setPlainText($password);
                    $customer->setRegistrationTypeId(6);
                    $customer->save();

                    $order = new CustomerOrder();
                    $order->setProductId($product->getId());
                    $order->setCustomerId($customer->getId());
                    $order->setExtraRefill($order->getProduct()->getInitialBalance());
                    $order->setIsFirstOrder(1);
                    $order->setOrderStatusId(3);
                    $order->save();

                    $transaction = new Transaction();
                    $transaction->setAgentCompanyId($customer->getReferrerId());
                    $transaction->setAmount($order->getProduct()->getPrice());
                    $transaction->setDescription('Registration of Retail');
                    $transaction->setOrderId($order->getId());
                    $transaction->setCustomerId($customer->getId());
                    $transaction->setTransactionStatusId(3);
                    $transaction->save();

                    $customer_product = new CustomerProduct();
                    $customer_product->setCustomer($order->getCustomer());
                    $customer_product->setProduct($order->getProduct());
                    $customer_product->save();

                    $callbacklog = new CallbackLog();
                    $callbacklog->setMobileNumber($number);
                    $callbacklog->setuniqueId($uniqueId);
                    $callbacklog->setImei($splitedText[1]);
                    $callbacklog->setImsi($splitedText[2]);
                    $callbacklog->setCheckStatus(3);
                    $callbacklog->save();

                    if (Telienta::ResgiterCustomer($customer, $order->getExtraRefill())) {
                        $availableUniqueId->setAssignedAt(date("Y-m-d H:i:s"));
                        $availableUniqueId->setStatus(1);
                        $availableUniqueId->setRegistrationTypeId(4);
                        $availableUniqueId->save();
                        Telienta::createAAccount($number, $customer);
                        Telienta::createCBAccount($number, $customer);
                    }

                    $sms = SmsTextPeer::retrieveByPK(9);
                    $smsText = $sms->getMessageText();
                    $smsText = str_replace("(balance)", $order->getExtraRefill(), $smsText);
                    ROUTED_SMS::Send($number, $smsText);

                    $sms = SmsTextPeer::retrieveByPK(11);
                    $smsText = $sms->getMessageText();
                    $smsText = str_replace("(username)", $mobileNumber, $smsText);
                    $smsText = str_replace("(password)", $password, $smsText);
                    ROUTED_SMS::Send($number, $smsText);
                    emailLib::sendCustomerRegistrationViaRetail($customer, $order);
                die;
            }

                
                $smstext = SmsTextPeer::retrieveByPK(2);
                echo $smstext->getMessageText();
                ROUTED_SMS::Send($number, $smstext->getMessageText());
                die;
            }
            $customer = CustomerPeer::doSelectOne($mnc);

            $callbackq = new Criteria();
            $callbackq->add(CallbackLogPeer::UNIQUEID, $uniqueId);
            $callbackq = CallbackLogPeer::doCount($callbackq);

            if ($callbackq < 1) {
                $smstext = SmsTextPeer::retrieveByPK(7);
                echo $smstext->getMessageText();
                ROUTED_SMS::Send($number, $smstext->getMessageText());
                die;
            }

            $callbacklog = new CallbackLog();
            $callbacklog->setMobileNumber($number);
            $callbacklog->setuniqueId($uniqueId);
            $callbacklog->save();

            $getvoipInfo = new Criteria();
            $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customer->getId());
            $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
            if (isset($getvoipInfos)) {
                $voipnumbers = $getvoipInfos->getNumber();
                $voipnumbers = substr($voipnumbers, 2);
            }


            $tc = new Criteria();
            $tc->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
            $tc->add(TelintaAccountsPeer::STATUS, 3);
            if (TelintaAccountsPeer::doCount($tc) > 0) {
                $telintaAccount = TelintaAccountsPeer::doSelectOne($tc);
                Telienta::terminateAccount($telintaAccount);
            }

            Telienta::createReseNumberAccount($voipnumbers, $customer, $number);

            $smstext = SmsTextPeer::retrieveByPK(1);
            ROUTED_SMS::Send($number, $smstext->getMessageText());
            die;
            return sfView::NONE;
        } elseif ($requestType == "ic") {

            $dialerIdLenght = strlen($textParamter);
            $uniqueId = substr($textParamter, 3);
            echo "<br/>";
            echo $uniqueId."<hr/>";



            $callbackq = new Criteria();
            $callbackq->add(CallbackLogPeer::UNIQUEID, $uniqueId);
            $callbackq = CallbackLogPeer::doCount($callbackq);

            if ($callbackq < 1) {
                $smstext = SmsTextPeer::retrieveByPK(7);
                ROUTED_SMS::Send($number, $smstext->getMessageText());
                die;
            }

            $mnc = new Criteria();
            $mnc->add(CustomerPeer::UNIQUEID, $uniqueId);
            $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $cusCount = CustomerPeer::doCount($mnc);

            if ($cusCount < 1) {
                $smstext = SmsTextPeer::retrieveByPK(2);
                ROUTED_SMS::Send($number, $smstext->getMessageText());
                die;
            }
            $customer = CustomerPeer::doSelectOne($mnc);

            $callbacklog = new CallbackLog();
            $callbacklog->setMobileNumber($number);
            $callbacklog->setuniqueId($uniqueId);
            $callbacklog->setcallingCode(46);
            $callbacklog->save();

            Telienta::createCBAccount($number, $customer,11648);  //11648 is Call back product for IC call

            $telintaGetBalance = Telienta::getBalance($customer);

            $getvoipInfo = new Criteria();
            $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customer->getId());
            $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
            if (isset($getvoipInfos)) {
                $voipnumbers = $getvoipInfos->getNumber();
                $voipnumbers = substr($voipnumbers, 2);

                $tc = new Criteria();
                $tc->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
                $tc->add(TelintaAccountsPeer::STATUS, 3);
                if (TelintaAccountsPeer::doCount($tc) > 0) {
                    $telintaAccount = TelintaAccountsPeer::doSelectOne($tc);
                    Telienta::terminateAccount($telintaAccount);
                }

                Telienta::createReseNumberAccount($voipnumbers, $customer, $number);
            }

            $smstext = SmsTextPeer::retrieveByPK(3);
            ROUTED_SMS::Send($number, $smstext->getMessageText());
            die;
            return sfView::NONE;
        } else {

            $text = $this->hextostr($request->getParameter('text'));
            $splitedText = explode(";", $text);
            if ($splitedText[3] != sfConfig::get("app_dialer_pin") && $splitedText[3] != "9998888999" && $splitedText[4] != sfConfig::get("app_dialer_pin") && $splitedText[4] != "9998888999" ) {
                echo "Invalid Request Dialer Pin<br/>";
                $sms = SmsTextPeer::retrieveByPK(7);
                ROUTED_SMS::Send($number, $sms->getMessageText());
                die;
            }
            $mobileNumber = substr($number, 2, strlen($number) - 2);
            if ($mobileNumber[0] != "0") {
                $mobileNumber = "0" . $mobileNumber;
            }
            echo "<hr/>";
            echo count($splitedText);
            echo "<hr/>";
            if(count($splitedText)==4){
                $dialerIdLenght = strlen($splitedText[0]);
                $uniqueId = substr($splitedText[0], $dialerIdLenght - 6, $dialerIdLenght - 1);
                echo "uniqueid:". $uniqueId;
            }else{
                echo strtolower(substr($splitedText[0],0,2));
                echo "<br/>";
                echo $splitedText[0];
                if(strtolower(substr($splitedText[0],0,2))=="re" && strlen($splitedText[0])==12){
                    $dialerIdLenght = strlen($splitedText[0]);
                    echo $location=4;
                    echo "<br/>";
                    $uniqueId = substr($splitedText[0], $dialerIdLenght - 6, $dialerIdLenght - 1);
                    echo "uniqueid:". $uniqueId;
                }else{
                    $dialerIdLenght = strlen($splitedText[1]);
                    echo "DialerLenght:".$dialerIdLenght."<br/>";
                    $uniqueId = substr($splitedText[1], $dialerIdLenght - 6, $dialerIdLenght - 1);
                    echo $location=5;
                    echo "<br/>";
                    echo "uniqueid:". $uniqueId;
                }
            }
            
            $c = new Criteria();
            $c->add(CustomerPeer::MOBILE_NUMBER, $mobileNumber);
            $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $c->addAnd(CustomerPeer::UNIQUEID, $uniqueId);


            if ($dialerIdLenght == 10 && count($splitedText)==4) {
                echo "Register Customer<br/>";
                //Registration Call, Register Customer In this block
                $uc = new Criteria();
                $uc->addAnd(UniqueIdsPeer::STATUS, 0);
                $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueId);

                $ucc = new Criteria();
                $ucc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueId);

                if (UniqueIdsPeer::doCount($ucc) == 0) {
                    echo "Unique Id Not Found";
                    $sms = SmsTextPeer::retrieveByPK(13);
                    ROUTED_SMS::Send($number, $sms->getMessageText());
                    die;
                }



                $cc = new Criteria();
                $cc->add(CustomerPeer::MOBILE_NUMBER, $mobileNumber);
                $cc->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, 3);

                if (CustomerPeer::doCount($cc) > 0) {
                    echo "Already Registerd";
                    $sms = SmsTextPeer::retrieveByPK(10);
                    ROUTED_SMS::Send($number, $sms->getMessageText());
                    die;
                }

                if (UniqueIdsPeer::doCount($uc) > 0) {
                    $availableUniqueId = UniqueIdsPeer::doSelectOne($uc);

                    $pc = new Criteria();
                    $pc->add(ProductPeer::SMS_CODE, "50");
                    $product = ProductPeer::doSelectOne($pc);

                    $calingcode = sfConfig::get('app_country_code');
                    $password = $this->randomNumbers(6);
                    $customer = new Customer();
                    $customer->setFirstName($mobileNumber);
                    $customer->setLastName($mobileNumber);
                    $customer->setMobileNumber($mobileNumber);
                    $customer->setPassword($password);
                    $customer->setEmail("retail@example.com");
                    $customer->setCountryId(2);
                    $customer->setCity("");
                    $customer->setAddress("");
                    $customer->setTelecomOperatorId(1);
                    $customer->setDeviceId(1474);
                    $customer->setUniqueId($uniqueId);
                    $customer->setCustomerStatusId(3);
                    $customer->setPlainText($password);
                    $customer->setRegistrationTypeId(6);
                    $customer->save();

                    $order = new CustomerOrder();
                    $order->setProductId($product->getId());
                    $order->setCustomerId($customer->getId());
                    $order->setExtraRefill($order->getProduct()->getInitialBalance());
                    $order->setIsFirstOrder(1);
                    $order->setOrderStatusId(3);
                    $order->save();

                    $transaction = new Transaction();
                    $transaction->setAgentCompanyId($customer->getReferrerId());
                    $transaction->setAmount($order->getProduct()->getPrice());
                    $transaction->setDescription('Registration of Retail');
                    $transaction->setOrderId($order->getId());
                    $transaction->setCustomerId($customer->getId());
                    $transaction->setTransactionStatusId(3);
                    $transaction->save();

                    $customer_product = new CustomerProduct();
                    $customer_product->setCustomer($order->getCustomer());
                    $customer_product->setProduct($order->getProduct());
                    $customer_product->save();

                    $callbacklog = new CallbackLog();
                    $callbacklog->setMobileNumber($number);
                    $callbacklog->setuniqueId($uniqueId);
                    $callbacklog->setImei($splitedText[1]);
                    $callbacklog->setImsi($splitedText[2]);
                    $callbacklog->setCheckStatus(3);
                    $callbacklog->save();

                    if (Telienta::ResgiterCustomer($customer, $order->getExtraRefill())) {
                        $availableUniqueId->setAssignedAt(date("Y-m-d H:i:s"));
                        $availableUniqueId->setStatus(1);
                        $availableUniqueId->setRegistrationTypeId(4);
                        $availableUniqueId->save();
                        Telienta::createAAccount($number, $customer);
                        Telienta::createCBAccount($number, $customer);
                    }

                    $sms = SmsTextPeer::retrieveByPK(9);
                    $smsText = $sms->getMessageText();
                    $smsText = str_replace("(balance)", $order->getExtraRefill(), $smsText);
                    ROUTED_SMS::Send($number, $smsText);

                    $sms = SmsTextPeer::retrieveByPK(11);
                    $smsText = $sms->getMessageText();
                    $smsText = str_replace("(username)", $mobileNumber, $smsText);
                    $smsText = str_replace("(password)", $password, $smsText);
                    ROUTED_SMS::Send($number, $smsText);
                    emailLib::sendCustomerRegistrationViaRetail($customer, $order);
                } else {
                    $sms = SmsTextPeer::retrieveByPK(6);
                    $smsText = $sms->getMessageText();
                    ROUTED_SMS::Send($number, $smsText);
                    die;
                }

                //End of Registration.
            } else {
                $c = new Criteria();
                $c->add(CustomerPeer::MOBILE_NUMBER, $mobileNumber);
                $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, 3);
                $c->addAnd(CustomerPeer::UNIQUEID, $uniqueId);

                if (CustomerPeer::doCount($c) > 0) {

                    $command = substr($splitedText[0], 0, 2);


                    $command = strtolower($command);
                    echo "<hr/>";
                    echo $command;
                    echo "<hr/>";
                    $customer = CustomerPeer::doSelectOne($c);
                    if ($command == "cb") {
                      
                        echo "Check Balance Request<br/>";
                        $balance = Telienta::getBalance($customer);
                        $sms = SmsTextPeer::retrieveByPK(5);
                        $smsText = $sms->getMessageText();
                        $smsText = str_replace("(balance)", $balance, $smsText);
                     //   echo $number;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                      $c = new Criteria();
                      $c->add(SmsLogPeer::MOBILE_NUMBER, $mobileNumber);
                      $c->addAnd(SmsLogPeer::SMS_TYPE, 2);
                      $c->addDescendingOrderByColumn(SmsLogPeer::CREATED_AT);
                     $value=SmsLogPeer::doCount($c);
                        
                      if($value>0){


                         $smsRow=SmsLogPeer::doSelectOne($c);
                        $createdAtValue= $smsRow->getCreatedAt();
                        $date1 =$createdAtValue;
                        $asd=0;
$d1=$date1;
$d2=date("Y-m-d h:m:s");
$asd=((strtotime($d2)-strtotime($d1))/3600);
  $asd=intval($asd);
if($asd>3){
    ROUTED_SMS::Send($number, $smsText,null,2);
     
              die;
}
 
                      }else{
              
  ROUTED_SMS::Send($number, $smsText,null,2);
  die;
                      }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                      
                      
                    } elseif ($command == "re") {
                        echo "Recharge Request<br/>";
                        $cc = new Criteria();

                        if(count($splitedText)==5){
                           $cardNumber= $splitedText[4];
                        }else{
                            $cardNumber= $splitedText[$location];
                        }

                        $cc->add(CardNumbersPeer::CARD_NUMBER,"00880".$cardNumber);
                        $cc->addAnd(CardNumbersPeer::STATUS, 0);
                        if (CardNumbersPeer::doCount($cc) == 1) {
                            $scratchCard = CardNumbersPeer::doSelectOne($cc);
                            //new order
                            $order = new CustomerOrder();
                            $customer_products = $customer->getProducts();
                            $order->setProduct($customer_products[0]);
                            $order->setCustomer($customer);
                            $order->setQuantity(1);
                            $order->setExtraRefill($scratchCard->getCardPrice());
                            $order->save();

                            //new transaction
                            $transaction = new Transaction();
                            $transaction->setAmount($scratchCard->getCardPrice());
                            $transaction->setDescription('Refill Via Pin Sr #' . $scratchCard->getCardSerial());
                            $transaction->setOrderId($order->getId());
                            $transaction->setCustomerId($order->getCustomerId());
                            $transaction->save();

                            if (Telienta::recharge($customer, $scratchCard->getCardPrice())) {
                                $scratchCard->setStatus(1);
                                $scratchCard->setUsedAt(date("Y-m-d H:i:s"));
                                $scratchCard->setCustomerId($customer->getId());
                                $scratchCard->save();
                                $order->setOrderStatusId(3);
                                $order->save();
                                $transaction->setTransactionStatusId(3);
                                $transaction->save();

                                // Send Customer Balance SMS after succesful recharge
                                $balance = Telienta::getBalance($customer);
                                $sms = SmsTextPeer::retrieveByPK(5);
                                $smsText = $sms->getMessageText();
                                $smsText = str_replace("(balance)", $balance, $smsText);
                                ROUTED_SMS::Send($number, $smsText);
                                // Send email to Support after Recharge
                                emailLib::sendRetailRefillEmail($customer, $order);
                            } else {
                                echo "Unable to charge";
                                $sms = SmsTextPeer::retrieveByPK(8);
                                ROUTED_SMS::Send($number, $sms->getMessageText());
                            }
                        } else {
                            echo "CARD ALREADY USED<br/>";
                            $sms = SmsTextPeer::retrieveByPK(7);
                            ROUTED_SMS::Send($number, $sms->getMessageText());
                        }
                        die;
                    }
                } else {
                    echo "Invalid Command 1";
                    $sms = SmsTextPeer::retrieveByPK(7);
                    ROUTED_SMS::Send($number, $sms->getMessageText());
                    die;
                }
            }
        }
         return sfView::NONE;
    }

public function executeSmsRegisterationsmscb(sfWebrequest $request){



     $urlval = "WCR-CB-" . $request->getURI();

        $email2 = new DibsCall();
        $email2->setCallurl($urlval);

        $email2->save();



       
    $sms_text="";
   $number = $request->getParameter('from');
    $mtnumber = $request->getParameter('from');
    $frmnumberTelinta = $request->getParameter('from');
	 $text = $request->getParameter('text');


          if(isset($number) && $number!=""){


        }else{
echo "Error,Cannot make callback! from number is missing";
die;
    }
     if(isset($text) && $text!=""){


        }else{
echo "Error,Cannot make callback! To number is missing";
die;
    }

      $caltype=substr($text,0,2);

     $numberlength=strlen($number);

      $endnumberlength=$numberlength-2;





            $mobile = "";
            $number = $request->getParameter('from');
            $message = $request->getParameter('text');


            if (isset($number) && $number != "") {
                $mnc = new Criteria();
                $mnc->add(CallbackLogPeer::MOBILE_NUMBER, $number);
                $cus = CallbackLogPeer::doSelectOne($mnc);
            }
            if (isset($cus) && $cus != "") {

                 
                $customerid = $cus->getId();
             
                if (isset($customerid) && $customerid != "") {

 

                    $fromcbnumber = 'cb' . $number;
                    $firstnumbernumber =$number;
                    $secondnumber =$message;
                    $form = new Curl_HTTP_Client();

                    $form->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
                    $form->set_referrer("http://landncall.zerocall.com");
                    $post_data = array(
                        'Account' => $fromcbnumber,
                        'Password' => 'asdf1asd',
                        'Action' => 'Connect Us Now!',
                        'First_Phone_Number' => $firstnumbernumber,
                        'Second_Phone_Number' => $secondnumber
                    );


                    echo $html_data = $form->send_post_data("https://mybilling.zerocall.com:8900/cgi/web/receive.pl", $post_data);


                    die;
                }
            }

            if (!$cus) {
               

               // $sms_text = "Hej,\n Ditt telefonnummer är inte registrerat hos LandNCall.Vänligen registrera telefonen eller kontakta support på support@landncall.com \n MVH \n LandNCall";
                $sm = new Criteria();
                $sm->add(SmsTextPeer::ID, 4);
                    $smstext = SmsTextPeer::doSelectOne($sm);
                    $sms_text = $smstext->getMessageText();
                $data = array(
                    'S' => 'H',
                    'UN' => 'zapna1',
                    'P' => 'Zapna2010',
                    'DA' => $number,
                    'SA' => 'LandNcall',
                    'M' => $sms_text,
                    'ST' => '5'
                );

                $queryString = http_build_query($data, '', '&');
                $queryString = smsCharacter::smsCharacterReplacement($queryString);
                echo  "Error,Cannot make callback!";
                $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?' . $queryString);
               die;
            }
            

 
 return sfView::NONE;

}

public function executeWebcall(sfWebrequest $request){

  
  $dest="";
  $mac="";
  $imsi = "";

      $imsi = $request->getParameter('imsi');
	$dest = $request->getParameter('dest');


  

   $mac = $request->getParameter('mac');
  	//$dest = str_replace('+','000',$dest);


	//echo $imsi;
	$mnc = new Criteria();
	$mnc->add(CallbackLogPeer::IMSI, $imsi);
	$cus = CallbackLogPeer::doSelectOne($mnc);
	$mbno=$cus->getMobileNumber();






       if(isset($mbno) && $mbno!=""){


           $calllog = new CallLog();
			$calllog->setMobileNumber($mbno);
			$calllog->setmac($mac);
                        $calllog->setimsi($imsi);
			$calllog->setdest($dest);
			$calllog->save();
           
		   $mobileno=$cus->getMobileNumber();
			
    		 echo $url = 'http://im.zerocall.com/webcall/webcall.php?serv=wcb&IMSI=00'.$mobileno.'&dest=00'.$dest.'&MAC';
                echo $res = file_get_contents($url);
			echo  "Please hold, we are connecting your call<br/><br/>";
			?><img src="http://landncall.zapna.com/images/logo.gif" /><?php
  		}else{
			echo 'We are not able to process your call, because you are not a registered customer';
		}

      return sfView::NONE;
      
}

public function executeCustomerBilling(sfWebRequest $request){

                
		
                $cc = new Criteria();
                $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
                $customers = CustomerPeer::doSelect($cc);

                foreach ($customers as $customer){

                echo "customer id: ".$customer->getId();
                echo "<br/>";
                
                $unid   =  $customer->getUniqueid();
                if(isset($unid) && $unid!=""){
                $un = new Criteria();
                $un->add(CallbackLogPeer::UNIQUEID, $unid);
                $un -> addDescendingOrderByColumn(CallbackLogPeer::CREATED);
                $unumber = CallbackLogPeer::doSelectOne($un);


                $cdrc = new Criteria();
                $cdrc->add(ZerocallCdrPeer::ANI, "00".$unumber->getMobileNumber());
                $cdrc->add(ZerocallCdrPeer::EXECUTE_STATUS,1);                
                $cdrs = ZerocallCdrPeer::doSelect($cdrc);

                
                foreach ($cdrs as $cdr){
                
                
                $rateId=0;
                $terminal=10;
                $count=1;
                $ratings=NULL;
                $number = $cdr->getExtension();
                echo "number ".$number;
                echo "<br/>";
                //$number=str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$number))));
                while($terminal>1){
                     $tcnumber=substr($number,2,$count);
                     echo "partial number ".$tcnumber;
                     echo "<br/>";

                      $r = new Criteria();
                      $r->add(CallRateTablePeer::DESTINATION_NO_FROM,  '%'.$tcnumber.'%', Criteria::LIKE);
                      $terminal = CallRateTablePeer::doCount($r);
                      echo "terminal ".$terminal;
                      echo "<br/>";
                    if($terminal>=1){
                        $ratings=CallRateTablePeer::doSelect($r);
                        foreach ($ratings as $rating){
                            $rateId =$rating->getCallRateTableId();

                        }
                    }
                    if($terminal==0){

                    }
                    $count=$count+1;
                }


                $rc = new Criteria();
                $rc->add(CallRateTablePeer::CALL_RATE_TABLE_ID, $rateId);
                $call_rate_table = CallRateTablePeer::doSelectOne($rc);
                $billing = new Billing();
                $billing->setCostPerMinute($call_rate_table->getRate());
                $billing->setRateTableDescription($call_rate_table->getDestinationName());
                $billing->setRateTableId($call_rate_table->getCallRateTableId());





                $cost_per_minute = $billing->getCostPerMinute();
                $seconds = $cdr->getEndTimeB() - $cdr->getAnswerTimeB();
                if($seconds < 0) {
                      $seconds = $seconds * (-1);
                }
                    $minutes = floor($seconds/60);
                    $secondsleft = $seconds%60;
                if($minutes<10)
                        $minutes = "0" . $minutes;
                if($secondsleft<10)
                        $secondsleft = "0" . $secondsleft;
                 
                $duration_minutes = $minutes.":".$secondsleft;
                $billing_minutes = ceil($seconds/60);

                $time =  date('Y-m-d H:i:s',$cdr->getEndTimeB());
                echo "time: ".$time;
                echo "<br/>";

                echo "fonet id: ".$customer->getFonetCustomerId();
                echo "<br/>";

                $current_balance = Fonet::getBalance($customer);

                echo "current balance: ".$current_balance;
                echo "<br/>";



                $call_cost = $billing_minutes * $cost_per_minute;

                
                echo "call cost: ".$call_cost;
                echo "<br/>";
                $recharge_amount = $call_cost;
                 echo "recharge amount: ".$recharge_amount;
                 echo "<br/>";
                echo Fonet::recharge($customer, -($recharge_amount));
                 echo "<br/>";
                echo Fonet::getBalance($customer);
                 echo "<br/>";
                $cost = $billing_minutes * $billing->getCostPerMinute();
                $vat = $cost/5;

                $billing->setTime($time);
                $billing->setCustomerId($customer->getId());
                $billing->setMobileNumber($cdr->getAni());
                $billing->setToNumber($cdr->getExtension());
                $billing->getDurationSecond($seconds);
                $billing->setDurationMinutes($duration_minutes);
                $billing->setBillingMinutes($billing_minutes);
                $billing->setBalanceBefore($current_balance);
                $billing->setBalanceAfter($current_balance - $recharge_amount);
                $billing->setBillingStatus(3);
                $billing->setCdrId($cdr->getCdrKey());
                $billing->setCallCost($cost);
                $billing->setVat($vat);
                $billing->save();
                


                    $cdr->setExecuteStatus(false);
                    $cdr->save();
                }
     
                }
}

return sfView::NONE;
}

 public static function updateLandncallBilling($number){

                         
                

                $rateId=0;
                $terminal=10;
                $count=1;
                $ratings=NULL;

                //$number=str_replace(' ','',str_replace('-','',str_replace(')','',str_replace('(','',$number))));
                while($terminal>1){
                     $tcnumber=substr($number,2,$count);
                      $r = new Criteria();
                      $r->add(CallRateTablePeer::DESTINATION_NO_FROM,  '%'.$tcnumber.'%', Criteria::LIKE);
                      $terminal = CallRateTablePeer::doCount($r);

                    if($terminal==1){
                        $ratings=CallRateTablePeer::doSelect($r);
                    }
                    if($terminal==0){

                    }
                    $count=$count+1;
                }

                


                $billing = new Billing();
                $billing->setCostPerMinute($rating->getRate());                
                $billing->setRateTableDescription($ratings->getDestinationName());
                $billing->setRateTableId($ratings->getCallRateTableId());

                return $billing;
}
  public function executeAutorefil(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request, $this);

        //echo "get customers to refill";
        $c = new Criteria();

        $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
        $c->addAnd(CustomerPeer::AUTO_REFILL_AMOUNT, 0, Criteria::NOT_EQUAL);
        //$c->addAnd(CustomerPeer::UNIQUEID, 99999, Criteria::GREATER_EQUAL);
        $c->addAnd(CustomerPeer::TICKETVAL, null, Criteria::ISNOTNULL);
        $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
        //$c1 = $c->getNewCriterion(CustomerPeer::LAST_AUTO_REFILL, 'TIMESTAMPDIFF(MINUTE, LAST_AUTO_REFILL, NOW()) > 1' , Criteria::CUSTOM);
        $c1 = $c->getNewCriterion(CustomerPeer::ID, null, Criteria::ISNOTNULL); //just accomodate missing disabled $c1
        $c2 = $c->getNewCriterion(CustomerPeer::LAST_AUTO_REFILL, null, Criteria::ISNULL);

        //$c1->addOr($c2);
        //$c->add($c1);

        $vt = 0;

        $customer = new Customer();

        $vt = CustomerPeer::doCount($c);


        if ($vt > 0) {

            $i = 0;
            $customers = CustomerPeer::doSelect($c);

            foreach ($customers as $customer) {
                  
//                echo "UniqueID:";
                  $uniqueId = $customer->getUniqueid();
//                echo  $uniqueId ;
//                echo "<br />";
                $usid="";
                    $usid=substr($uniqueId,0,2);
                if ($usid=="us") {
                     $Tes = ForumTel::getBalanceForumtel($customer->getId());
                     if($Tes!=""){  
                        $customer_balance = CurrencyConverter::convertUsdToSek($Tes);
//                        echo "$uniqueId--ForumTel balance----".$Tes;                        
                     } else{
                        $customer_balance = null;
//                        echo "$uniqueId--customer_balance----null---".$Tes;
                     }  
                     
                  //  echo "ForumTel balance----".$Tes;
                    echo "<br />";
                } else {
                    //echo "This is for Retrieve balance From Telinta"."<br/>";
                   
                 //  $customer_balance=Telienta::getBalance($customer);

                     $retries = 0;
                     $maxRetries = 5;
                        do {
                            $customer_balance = Telienta::getBalance($customer);
                            $retries++;
                          //  echo $customer->getId().":".$customer_balance.":".$retries."<br/>";
                            
                        } while (!$customer_balance && $retries <= $maxRetries);

                    if($retries==$maxRetries){
                        continue;
                    }
//                   echo "$uniqueId--Telinta balance----".$customer_balance;
//                   echo "<br />";
                }
                //   echo $uniqueId.":".$customer_balance."<br/>";

                // $customer_balance = Fonet::getBalance($customer);
                //if customer balance is less than 10
//                echo "$customer->getAutoRefillMinBalance() customer mini autorefill balance<br />";
                if ($customer_balance != null && (float)$customer_balance <= (float)$customer->getAutoRefillMinBalance()) {


                     echo "success condition---".$uniqueId.":".$customer_balance."<br/>";
                     

                    $customer_id = $customer->getId();

                    $this->customer = CustomerPeer::retrieveByPK($customer_id);

                    $this->order = new CustomerOrder();

                    $customer_products = $this->customer->getProducts();

                    $this->order->setProduct($customer_products[0]);
                    $this->order->setCustomer($this->customer);
                    $this->order->setQuantity(1);
                    $this->order->setExtraRefill($customer->getAutoRefillAmount());
                    $this->order->save();


                    $transaction = new Transaction();

                    $transaction->setAmount($this->order->getExtraRefill());
                    $transaction->setDescription($this->getContext()->getI18N()->__('Auto Refill'));
                    $transaction->setOrderId($this->order->getId());
                    $transaction->setCustomerId($this->order->getCustomerId());

                    $transaction->save();

                    $order_id = $this->order->getId();
                    $total = 100 * $this->order->getExtraRefill();
                    $tickvalue = $this->customer->getTicketval();
                    $form = new Curl_HTTP_Client();


//echo "pretend to be IE6 on windows";
///////$post_data = array(
//    'merchant' => '90049676',
//    'amount' => $total,
//    'currency' => '752',
//    'orderid' => $order_id,
//    'textreply' => true,
//    'test' => 'foo',
//    'account' => 'YTIP',
//    'status' => '',
//    'ticket' =>$tickvalue,
//    'lang' => 'sv',
//    'HTTP_COOKIE' => getenv("HTTP_COOKIE"),
//    'cancelurl' => "http://landncall.zerocall.com/b2c.php/",
//    'callbackurl' => "http://landncall.zerocall.com/b2c_dev.php/pScripts/autorefilconfirmation?accept=yes&subscriptionid=&orderid=$order_id&amount=$total",
//    'accepturl' => "http://landncall.zerocall.com/b2c.php/"
//);

                    //  'textreply' => true,
                    $form->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
                    $form->set_referrer("http://landncall.zerocall.com");
                    $post_data = array(
                        'merchant' => '90049676',
                        'amount' => $total,
                        'currency' => '752',
                        'orderid' => $order_id,
                  
                        'account' => 'YTIP',
                        'status' => '',
                        'ticket' => $tickvalue,
                        'lang' => 'sv',
                        'HTTP_COOKIE' => getenv("HTTP_COOKIE"),
                        'cancelurl' => "http://landncall.zerocall.com/b2c.php/",
                        'callbackurl' => "http://landncall.zerocall.com/b2c_dev.php/pScripts/autorefilconfirmation?accept=yes&subscriptionid=&orderid=$order_id&amount=$total",
                        'accepturl' => "http://landncall.zerocall.com/b2c.php/"
                    );
//var_dump($post_data);
//echo "<br/>Baran<br/>";

                    $html_data = $form->send_post_data("https://payment.architrade.com/cgi-ssl/ticket_auth.cgi", $post_data);
                    echo $html_data;
                    echo "<br/>";
                    // die("khan");
                }
                sleep(0.5);
            }
        }

        return sfView::NONE;
        // $this->setLayout(false);
    }
         
         ///////////////////////////////////////////////////////////////////////////////////////////////////////
         
      public function executeAutorefilconfirmation(sfWebRequest $request)
  {
    
          //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
            changeLanguageCulture::languageCulture($request,$this);

//
//              $urlval = "autorefill-" . $request->getURI();
//
//        $email21 = new DibsCall();
//        $email21->setCallurl($urlval);
//
//        $email21->save();

           $urlval=0;
            $Parameters="Parameters-autorefil-URL-transactionNumber-OrderNumber-Amount".$request->getURI()."?transact=".$request->getParameter('transact')."&orderid=".$request->getParameter("orderid")."&amount=".$request->getParameter('amount');
   
         $email2 = new DibsCall();
         $email2->setCallurl($Parameters);

            $email2->save();
           $urlval=$request->getParameter('transact');
            if(isset($urlval) && $urlval>0){
         $order_id = $request->getParameter("orderid");

	  	$this->forward404Unless($order_id || $order_amount);
                $orderscount=0;
                $cr = new Criteria;
               	$cr->add(CustomerOrderPeer::ID, $order_id);
                $cr->addAnd(CustomerOrderPeer::ORDER_STATUS_ID, 1);
	  	$orderscount = CustomerOrderPeer::doCount($cr);

                if($orderscount>0){


		$order = CustomerOrderPeer::retrieveByPK($order_id);

	  	$order_amount = ((double)$request->getParameter('amount'))/100 ;

	  	$this->forward404Unless($order);

	  	$c = new Criteria;
	  	$c->add(TransactionPeer::ORDER_ID, $order_id);

	  	$transaction = TransactionPeer::doSelectOne($c);

	  	//echo var_dump($transaction);

	  	$order->setOrderStatusId(3); //completed
	  	//$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 3)); //completed
	  	$transaction->setTransactionStatusId(3); //completed




		if($transaction->getAmount() > $order_amount){
	  		//error
	  		$order->setOrderStatusId(5); //error in amount
	  		$transaction->setTransactionStatusId(5); //error in amount
	  		//$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 5)); //error in amount


	  	} else if ($transaction->getAmount() < $order_amount){
	  		//$extra_refill_amount = $order_amount;
	  		$order->setExtraRefill($order_amount);
	  		$transaction->setAmount($order_amount);
	  	}
		 //set active agent_package in case customer was registerred by an affiliate
		  if ($order->getCustomer()->getAgentCompany())
		  {
		  	$order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
		  }

		  //set subscription id in case 'use current c.c for future auto refills' is set to 1
			  	//set auto_refill amount
	  	 
	  	$order->save();
	  	$transaction->save();

	$this->customer = $order->getCustomer();
          $c = new Criteria;
	  	$c->add(CustomerPeer::ID, $order->getCustomerId());
	  	$customer = CustomerPeer::doSelectOne($c);
               
                 $customer->setLastAutoRefill(date('Y-m-d H:i:s'));
  $customer->save();
             echo "ag". $agentid=$customer->getReferrerId();
                echo  "prid".   $productid=$order->getProductId();
                echo  "trid".   $transactionid=$transaction->getId();
                if(isset($agentid) && $agentid!=""){
                    echo "getagentid";
                commissionLib::refilCustomer($agentid,$productid,$transactionid);
                }
	//TODO ask if recharge to be done is same as the transaction amount
	//die;
      //  Fonet::recharge($this->customer, $transaction->getAmount());
                        $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
                        if($getFirstnumberofMobile==0){
                          $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                          $TelintaMobile =  '46'.$TelintaMobile ;
                        }else{
                          $TelintaMobile = '46'.$this->customer->getMobileNumber();
                        }
                        //$TelintaMobile = '46'.$this->customer->getMobileNumber();
                        $emailId = $this->customer->getEmail();
                        $uniqueId = $this->customer->getUniqueid();
                        $OpeningBalance = $transaction->getAmount();
                        //This is for Recharge the Customer
                       $usid="";
                    $usid=substr($uniqueId,0,2);
                if ($usid=="us") {
                            
                            $cuserid = $this->customer->getId();
                          $amt=$OpeningBalance;
                  $amt=CurrencyConverter::convertSekToUsd($amt);
                $Test=ForumTel::rechargeForumtel($cuserid,$amt);
                        }else{


                        $description="Auto Refill";
                        Telienta::recharge($this->customer, $OpeningBalance,$description);
                        
                        }
                        //This is for Recharge the Account
                          //this condition for if follow me is Active
                            $getvoipInfo = new Criteria();
                            $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customer->getMobileNumber());
                            $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                            if(isset($getvoipInfos)){
                                $voipnumbers = $getvoipInfos->getNumber() ;
                                $voip_customer = $getvoipInfos->getCustomerId() ;
                           }else{
                              }                            
                       
                      
  
 



            emailLib::sendCustomerAutoRefillEmail($this->customer,$order,$transaction);
                }
          
            }
              return sfView::NONE;
  }    

////////////////////////////////////////
    public function executeUsageAlert(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request, $this);
        //-----------------------
        $langSym = $this->getUser()->getCulture();
        $enableCountry = new Criteria();
        $enableCountry->add(EnableCountryPeer::LANGUAGE_SYMBOL, $langSym);
        $country_id = EnableCountryPeer::doSelectOne($enableCountry); //->getId();
        if ($country_id) {
            $CallCode = $country_id->getCallingCode();
            $countryId = $country_id->getId();
        } else {
            $CallCode = '46';
            $countryId = "2";
        }


        $usagealerts = new Criteria();
        $usagealerts->add(UsageAlertPeer::SMS_ACTIVE, 1);
        $usagealerts->addAnd(UsageAlertPeer::COUNTRY, $countryId);
        $usageAlerts = UsageAlertPeer::doSelect($usagealerts);
        $c = new Criteria();
        $c->addJoin(CustomerPeer::ID, CustomerProductPeer::CUSTOMER_ID, Criteria::LEFT_JOIN);
        $c->addJoin(CustomerProductPeer::PRODUCT_ID,ProductPeer::ID, Criteria::LEFT_JOIN);
        $c->addAnd(ProductPeer::PRODUCT_COUNTRY_US,1, Criteria::NOT_EQUAL);
        $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, 3);
        $c->addAnd(CustomerPeer::COUNTRY_ID, $countryId);
        $customers = CustomerPeer::doSelect($c);

        foreach ($customers as $customer) {
            $retries = 0;
            $maxRetries = 5;
            do {
                $customer_balance = Telienta::getBalance($customer);
                $retries++;
                echo $customer->getId().":".$customer_balance.":".$retries."<br/>";
            } while (!$customer_balance && $retries <= $maxRetries);

            if($retries==$maxRetries){
                continue;
            }

            $customer_balance = (double) $customer_balance;

            $actual_balance = $customer_balance;
            if ($customer_balance < 1) {
                $customer_balance = 0;
            }
            foreach ($usageAlerts as $usageAlert) {
                //echo "<hr/>".$usageAlert->getId()."<hr/>";
                if ($customer_balance >= $usageAlert->getAlertAmountMin() && $customer_balance < $usageAlert->getAlertAmountMax()) {

                    $sender = new Criteria();
                    $sender->add(UsageAlertSenderPeer::ID, $usageAlert->getSenderName());
                    $senders = UsageAlertSenderPeer::doSelectOne($sender);
                    echo $senderName = $senders->getName();


                    $regType = RegistrationTypePeer::retrieveByPK($customer->getRegistrationTypeId()); // && $customer->getFonetCustomerId()!=''
                    $referer = $customer->getReferrerId();
                    if (isset($referer) && $referer > 0) {
                        $Cname = new Criteria();
                        $Cname->add(AgentCompanyPeer::ID, $referer);
                        $Companies = AgentCompanyPeer::doSelectOne($Cname);
                        $comName = $Companies->getName();
                    } else {
                        $comName = "";
                    }
                    $Prod = new Criteria();
                    $Prod->addJoin(ProductPeer::ID, CustomerProductPeer::PRODUCT_ID, Criteria::LEFT_JOIN);
                    $Prod->add(CustomerProductPeer::CUSTOMER_ID, $customer->getId());
                    $Product = ProductPeer::doSelectOne($Prod);

                    $cSMSent = new Criteria();
                    $cSMSent->add(SmsAlertSentPeer::USAGE_ALERT_STATUS_ID, $usageAlert->getId());
                    $cSMSent->addAnd(SmsAlertSentPeer::CUSTOMER_ID, $customer->getId());
                    $cSMSentCount = SmsAlertSentPeer::doCount($cSMSent);

                    if ($usageAlert->getSmsActive() && $cSMSentCount == 0) {
                        echo "Sms Alert Sent:";
                        $msgSent = new SmsAlertSent();
                        $msgSent->setCustomerId($customer->getId());
                        $msgSent->setCustomerName($customer->getFirstName());
                        $msgSent->setCustomerProduct($Product->getName());
                        $msgSent->setRegistrationType($regType->getDescription());
                        $msgSent->setAgentName($comName);
                        $msgSent->setCustomerEmail($customer->getEmail());
                        $msgSent->setMobileNumber($customer->getMobileNumber());
                        $msgSent->setUsageAlertStatusId($usageAlert->getId());
                        $msgSent->setAlertActivated($customer->getUsageAlertSMS());
                        //$msgSent->setFonetCustomerId($customer->getFonetCustomerId());
                        $msgSent->setMessageDescerption("Current Balance: " . $actual_balance);
                        //$msgSent->save();
                        /**
                         * SMS Sending Code
                         * */
                        if ($customer->getUsageAlertSMS()) {
                            echo "SMS Active<br/>";
                            $customerMobileNumber = $CallCode . substr($customer->getMobileNumber(), 1);
                            $sms_text = $usageAlert->getSmsAlertMessage();
                            $response = ROUTED_SMS::Send($customerMobileNumber, $sms_text, $senderName);

                            if ($response) {
                                $msgSent->setAlertSent(1);
                            }
                        }
                        $msgSent->save();

                    }

                    $cEmailSent = new Criteria();
                    $cEmailSent->add(EmailAlertSentPeer::USAGE_ALERT_STATUS_ID, $usageAlert->getId());
                    $cEmailSent->addAnd(EmailAlertSentPeer::CUSTOMER_ID, $customer->getId());
                    $cEmailSentCount = EmailAlertSentPeer::doCount($cEmailSent);

                    if ($usageAlert->getEmailActive() && $cEmailSentCount == 0) {
                        echo "Email Alert Sent:";
                        $msgSentE = new EmailAlertSent();
                        $msgSentE->setCustomerId($customer->getId());
                        $msgSentE->setCustomerName($customer->getFirstName());
                        $msgSentE->setCustomerProduct($Product->getName());
                        $msgSentE->setRegistrationType($regType->getDescription());
                        $msgSentE->setAgentName($comName);
                        $msgSentE->setCustomerEmail($customer->getEmail());
                        $msgSentE->setMobileNumber($customer->getMobileNumber());
                        $msgSentE->setUsageAlertStatusId($usageAlert->getId());
                        $msgSentE->setAlertActivated($customer->getUsageAlertEmail());
                        //$msgSentE->setFonetCustomerId($customer->getFonetCustomerId());
                        $msgSentE->setMessageDescerption("Current Balance: " . $actual_balance);
                        //$msgSentE->save();

                        if ($customer->getUsageAlertEmail()) {
                            echo "Email Active<br/>";
                            $message = '<img src="http://landncall.zerocall.com/images/logo.gif" /><br>' . $usageAlert->getEmailAlertMessage() . '<br>Hälsningar <br>' . $senderName;
                            emailLib::sendCustomerBalanceEmail($customer, $message);
                            $msgSentE->setAlertSent(1);
                        }
                        $msgSentE->save();
                    }
                }
            }
        }

        return sfView::NONE;
    }
//////////

 public function executeSendEmailstt(sfWebRequest $request)
  {

	$sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
	$sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

        echo '<br/>';
        echo $sender_email ;
        echo '<br/>';
        echo $sender_name ;
  	$c = new Criteria();
  	$c->add(EmailQueuePeer::EMAIL_STATUS_ID, sfConfig::get('app_status_completed'), Criteria::NOT_EQUAL);
        $emails = EmailQueuePeer::doSelect($c);
  try{
  	foreach( $emails as $email)
  	{
////////////////////////////////////////////////////

//		$message = Swift_Message::newInstance($email->getSubject())
//		         ->setFrom(array($sender_email => $sender_name))
//		         ->setTo(array($email->getReceipientEmail() => $email->getReceipientName()))
//		         ->setBody($email->getMessage(), 'text/html')
//		         ;

            
$to =  $email->getReceipientName()."<".$email->getReceipientEmail().">";
$from =  $sender_name."<".$sender_email.">";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$headers .= "From:" . $from;
@mail($to,$email->getSubject(),$email->getMessage(),$headers);


//                $message = Swift_Message::newInstance($email->getSubject())
//		         ->setFrom(array("support@landncall.com"))
//		         ->setTo(array("mohammadali110@gmail.com"=>"Mohammad Ali"))
//		         ->setBody($email->getMessage(), 'text/html')
//		         ;
                echo 'inside loop';
                echo '<br/>';

                echo $email->getId();
                echo '<br/>';
                echo '<br/>';

                //This Conditon Add Update Row Which Have the
		 if($email->getReceipientEmail()!=''){
//                    @$mailer->send($message);
                    $email->setEmailStatusId(sfConfig::get('app_status_completed'));
                    //TODO:: add sent_at too
                    $email->save();
                    echo sprintf("Send to %s<br />", $email->getReceipientEmail());
		}



  	}
        }catch (Exception $e){

                    echo $e->getLine();
                    echo $e->getMessage();
                }
  	return sfView::NONE;
  }

  public function executeAssignICustomerNumber(sfWebRequest $request)
  {
       $c = new Criteria();
       $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
       $c->addAnd(CustomerPeer::UNIQUEID, null, Criteria::ISNOTNULL);
       $c->addAnd(CustomerPeer::I_CUSTOMER, null, Criteria::ISNULL);
       //echo CustomerPeer::doCount($c);
      // die;
       $customers = CustomerPeer::doSelect($c);
       foreach ($customers as $customer){
           $iCustomer= Telienta::getCustomerInfo($customer->getUniqueid());
           $customer->setICustomer($iCustomer);
           $customer->save();
       }
  }

  public function executeCreateIAccounts(sfWebRequest $request)
  {
       $c = new Criteria();
       $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
       $c->addAnd(CustomerPeer::I_CUSTOMER, null, Criteria::ISNOTNULL);
       $customers = CustomerPeer::doSelect($c);
       foreach ($customers as $customer){
           $iAccountList= Telienta::getCustomerAccountList($customer->getICustomer());

           foreach($iAccountList->account_list as $iAccount){
              $telintaAccount = new TelintaAccounts();
              $telintaAccount->setAccountTitle($iAccount->id);
              $telintaAccount->setIAccount($iAccount->i_account);
              $telintaAccount->setICustomer($iAccount->i_customer);
              $telintaAccount->setParentId($customer->getId());
              $telintaAccount->setParentTable('customer');
              $telintaAccount->save();
           }
       }
  }

 public function executeCardNumber(sfWebRequest $request) {


        function random($len) {


            $return = '';
            for ($i = 0; $i < $len; ++$i) {
                if (!isset($urandom)) {
                    if ($i % 2 == 0)
                        mt_srand(time() % 2147 * 1000000 + (double) microtime() * 1000000);
                    $rand = 48 + mt_rand() % 64;
                } else
                    $rand=48 + ord($urandom[$i]) % 64;

                if ($rand > 57)
                    $rand+=7;
                if ($rand > 90)
                    $rand+=6;
                if ($rand > 80)
                    $rand-=5;


                if ($rand == 123)
                    $rand = 45;
                if ($rand == 124)
                    $rand = 46;
                $return.=$rand;
            }
            return $return;
        }

        $cardcount = 0;
        $serial = 100000;
        $i = 1;
        while ($i <= 20000) {


            $val = random(20);

            $randLength = strlen($val);

            if ($randLength > 9) {
                $resultvalue = (int) $randLength - 9;

                $rtvalue = mt_rand(1, $resultvalue);

                $resultvalue = substr($val, $rtvalue, 9);

                $cardnumber = "00880" . $resultvalue;
            }

            $CRcardcount = 0;
            $cq = new Criteria();
            $cq->add(CardNumbersPeer::CARD_NUMBER, $cardnumber);
            $CRcardcount = CardNumbersPeer::doCount($cq);

            if ($CRcardcount == 1) {
                
            } else {

                $cardTotalcount = 0;
                $ct = new Criteria();
                $cardTotalcount = CardNumbersPeer::doCount($ct);
                if ($cardTotalcount < 10000) {
                    $cardcount = 0;

                    $c = new Criteria();
                    $c->add(CardNumbersPeer::CARD_PRICE, 50);
                    $cardcount = CardNumbersPeer::doCount($c);
                    if ($cardcount < 5000) {

                        $price = 50;
                        $cr = new CardNumbers();
                        $cr->setCardNumber($cardnumber);
                        $cr->setCardPrice($price);
                        $cr->setCardSerial($serial);
                        $cr->save();
                        $serial++;
                    } else {

                        $price = 100;
                        $crp = new CardNumbers();
                        $crp->setCardNumber($cardnumber);
                        $crp->setCardPrice($price);
                        $crp->setCardSerial($serial);
                        $crp->save();
                        $serial++;
                    }
                } else {
                    $i = 20000;
                }
            }
            $i++;
        }


        return sfView::NONE;
    }


    private function hextostr($hex) {
        $str = '';
        for ($i = 0; $i < strlen($hex) - 1; $i+=2) {
            $str .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $str;
    }

    private function randomNumbers($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    public function executeGenrateTestString(sfWebRequest $request){

        if($request->isMethod('post')){
            if($request->getParameter("hex")=="on"){
                echo $this->hexToStr($request->getParameter("inputstr"));
            }else{
                echo $this->strToHex($request->getParameter("inputstr"));
            }
        }
        
    }


    private function strToHex($string) {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }


   public function executeCsvFiles(sfWebRequest $request)
  {

        $fromDate = date("Y-m-d 00:00:00", strtotime('-1 day'));
        $toDate = date("Y-m-d 23:59:59", strtotime('-1 day'));

        $filename = "LandnCall_" . time() . ".csv";
        $cdrlog = new LandncallCdrLog();
        $cdrlog->setName($filename);
        $cdrlog->setFromTime($fromdate);
        $cdrlog->setToTime($todate);
        $cdrlog->save();

        $myFile = "/var/www/landncall/data/landncall_cdr/" . $filename;
        $fh = fopen($myFile, 'w') or die("can't open file");
        $comma = ",";
        $stringData = "company_vat,CLI,CLD,charged_amount,charged_quantity,country,subdivision,description,disconnect_cause,bill_status,unix_connect_time,disconnect_time,unix_disconnect_time,bill_time,Samtalstyp";
        $stringData.= "\n";
        fwrite($fh, $stringData);

        $companies = CompanyPeer::doSelect(new Criteria());
        foreach($companies as $company){
            $tilentaCallHistryResult = CompanyEmployeActivation::callHistory($company, $fromDate, $toDate,true);
            if($tilentaCallHistryResult){
                foreach ($tilentaCallHistryResult->xdr_list as $xdr) {
                    $callerTyper = "";
                     $typecall = substr($xdr->account_id, 0, 1);
                                if ($typecall == 'a') {
                                    $callerTyper =  "Int.";
                                }
                                if ($typecall == '4') {
                                    $callerTyper =  "R";
                                }
                                if ($typecall == 'c') {
                                      $cbtypecall = substr($xdr->account_id, 2);
                                    if ($xdr->CLD ==$cbtypecall) {
                                        $callerTyper =  "Cb M";
                                    } else {
                                        $callerTyper =  "Cb S";
                                    }
                                }

                    $stringData = $company->getVatNo(). $comma .$xdr->CLI . $comma . $xdr->CLD . $comma . $xdr->charged_amount . $comma . $xdr->charged_quantity . $comma . $xdr->country . $comma . $xdr->subdivision . $comma . $xdr->description . $comma . $xdr->disconnect_cause . $comma . $xdr->bill_status . $comma . $xdr->unix_connect_time . $comma . $xdr->disconnect_time . $comma . $xdr->unix_disconnect_time . $comma . $xdr->bill_time. $comma.$typecall;
                    $stringData.= "\n";
                    fwrite($fh, $stringData);
                }
            }
        }




        $destination_file = "/zapna/zapna/" . $filename;
        $ftp_server = "79.138.0.134";  //address of ftp server (leave out ftp://)
        $ftp_user_name = "zapna"; // Username
        $ftp_user_pass = "2s7G3Ms4";   // Password
        $conn_id = ftp_connect($ftp_server);        // set up basic connection
       
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<h1>You do not have access to this ftp server!</h1>");
        ftp_pasv($conn_id, true);
        $upload = ftp_put($conn_id, $destination_file, $myFile, FTP_BINARY);  // upload the file



        if (!$upload) {
            emailLib::sendLandncallCdrErrorEmail($filename);
        } else {

            $cdrlog->setStatus(3);
        }
        $cdrlog->save();
        fclose($fh);


        return sfView::NONE;
   }



     public function executeResendFailedCsvFiles(sfWebRequest $request)
  {

        // Resend CDRs of Employees whos Data was not fetched.

        $c= new Criteria();
        $c->add(CompanyCdrFetchFailedLogPeer::STATUS,1);

        if(CompanyCdrFetchFailedLogPeer::doCount($c)>0){
            $missingCompanies = CompanyCdrFetchFailedLogPeer::doSelect($c);

            $filename = "LandnCall_" . time() . ".csv";
            
            $myFile = "/var/www/landncall/data/landncall_cdr/" . $filename;
            
            $fh = fopen($myFile, 'w') or die("can't open file");
            $comma = ",";
            $stringData = "company_vat,CLI,CLD,charged_amount,charged_quantity,country,subdivision,description,disconnect_cause,bill_status,unix_connect_time,disconnect_time,unix_disconnect_time,bill_time,Samtalstyp";
            $stringData.= "\n";
            fwrite($fh, $stringData);
            $calls = false;
            foreach($missingCompanies as $missingCompany){
                $fromDate = $missingCompany->getFromDate();
                $toDate =  $missingCompany->getToDate();
                $company = CompanyPeer::retrieveByPK($missingCompany->getCompanyId());
                $tilentaCallHistryResult = CompanyEmployeActivation::callHistory($company, $fromDate, $toDate,true);
                if($tilentaCallHistryResult){
                    $missingCompany->setStatus(3);
                    $missingCompany->save();
                    foreach ($tilentaCallHistryResult->xdr_list as $xdr) {
                        $callerTyper = "";
                         $typecall = substr($xdr->account_id, 0, 1);
                                    if ($typecall == 'a') {
                                        $callerTyper =  "Int.";
                                    }
                                    if ($typecall == '4') {
                                        $callerTyper =  "R";
                                    }
                                    if ($typecall == 'c') {
                                          $cbtypecall = substr($xdr->account_id, 2);
                                        if ($xdr->CLD ==$cbtypecall) {
                                            $callerTyper =  "Cb M";
                                        } else {
                                            $callerTyper =  "Cb S";
                                        }
                                    }

                        $stringData = $company->getVatNo(). $comma .$xdr->CLI . $comma . $xdr->CLD . $comma . $xdr->charged_amount . $comma . $xdr->charged_quantity . $comma . $xdr->country . $comma . $xdr->subdivision . $comma . $xdr->description . $comma . $xdr->disconnect_cause . $comma . $xdr->bill_status . $comma . $xdr->unix_connect_time . $comma . $xdr->disconnect_time . $comma . $xdr->unix_disconnect_time . $comma . $xdr->bill_time. $comma.$typecall;
                        $stringData.= "\n";
                        fwrite($fh, $stringData);
                    }
                    $calls = true;
                }
            }


            if($calls){
                $cdrlog = new LandncallCdrLog();
                $cdrlog->setName($filename);
                $cdrlog->save();
                $destination_file = "/zapna/zapna/" . $filename;
                $ftp_server = "79.138.0.134";  //address of ftp server (leave out ftp://)
                $ftp_user_name = "zapna"; // Username
                $ftp_user_pass = "2s7G3Ms4";   // Password
                $conn_id = ftp_connect($ftp_server);        // set up basic connection

                $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<h1>You do not have access to this ftp server!</h1>");
                ftp_pasv($conn_id, true);
                $upload = ftp_put($conn_id, $destination_file, $myFile, FTP_BINARY);  // upload the file



                if (!$upload) {
                    emailLib::sendLandncallCdrErrorEmail($filename);
                } else {

                    $cdrlog->setStatus(3);
                }
                $cdrlog->save();
            }
        fclose($fh);


        }

        $cdrq = new Criteria();
        $cdrq->add(LandncallCdrLogPeer::STATUS,1);
        $cdrrecords= LandncallCdrLogPeer::doSelect($cdrq);
        foreach($cdrrecords as $cdrrecord){
            $filename =$cdrrecord->getName();
            $myFile = "/var/www/landncall/data/landncall_cdr/" . $filename;
            $destination_file = "/zapna/zapna/" . $filename;
            $ftp_server = "79.138.0.134";  //address of ftp server (leave out ftp://)
            $ftp_user_name = "zapna"; // Username
            $ftp_user_pass = "2s7G3Ms4";   // Password
            $conn_id = ftp_connect($ftp_server);        // set up basic connection
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) or die("<h1>You do not have access to this ftp server!</h1>");
            ftp_pasv($conn_id, true);
            $upload = ftp_put($conn_id, $destination_file, $myFile, FTP_BINARY);  // upload the file
            if (!$upload) {
                emailLib::sendLandncallCdrErrorEmail($filename);
            } else {
                $cdrrecord->setStatus(3);
                  $cdrrecord->save();
            }
             fclose($fh);
             sleep(1);
        }
        return sfView::NONE;
   }


       public function executeGetBalanceFromTelienta(sfWebRequest $request){
        $c = new Criteria();
        $c->add(CustomerPeer::I_CUSTOMER, null, Criteria::ISNOTNULL);
        $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, 3);
        $customers = CustomerPeer::doSelect($c);
        foreach($customers as $customer){
            Telienta::getBalance($customer);
        }
    }



       public function executeGetSms(sfWebRequest $request){
       $smstext ="Du är registrerad framgångsrikt. ditt saldo är 99";
       ROUTED_SMS::Send(46732801013, $smstext);
         return sfView::NONE;
}

public function executeActivateAutoRefill(sfWebRequest $request) {

        changeLanguageCulture::languageCulture($request, $this);

        $urlval = $request->getParameter('transact');
        $customerid = $request->getParameter('customerid');
        $user_attr_3 = $request->getParameter('user_attr_3');
        $user_attr_2 = $request->getParameter('user_attr_2');
        $db1 = new DibsCall();
        $db1->setCallurl($urlval);
        $db1->save();
        $db2 = new DibsCall();
        $db2->setCallurl($customerid);
        $db2->save();
        $db3 = new DibsCall();
        $db3->setCallurl($user_attr_3);
        $db3->save();
        $db4 = new DibsCall();
        $db4->setCallurl($user_attr_2);
        $db4->save();
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        //-----------------------

        $ca = new Criteria();
        $ca->add(CustomerPeer::ID, $customerid);
        $customer = CustomerPeer::doSelectOne($ca);

        $customer->setAutoRefillMinBalance($user_attr_3);
        $customer->setAutoRefillAmount($user_attr_2);
        $customer->setTicketval($urlval);
        $customer->save();
        $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Auto Refill is Activated.'));
        return $this->redirect('customer/dashboard');
    }


    public function executeCalbackrefill(sfWebRequest $request) {
        $order_id = $request->getParameter("orderid");
        $urlval = $order_id . " refill page-qqqqqqqqq" . $request->getParameter('transact');

        $email2 = new DibsCall();
        $email2->setCallurl($urlval);

        $email2->save();


        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request, $this);
        //-----------------------



        $this->forward404Unless($order_id || $order_amount);

        $order = CustomerOrderPeer::retrieveByPK($order_id);

        $subscription_id = $request->getParameter("subscriptionid");
        $order_amount = ((double) $request->getParameter('amount')) / 100;
        $this->forward404Unless($order);
        $c = new Criteria;
        $c->add(TransactionPeer::ORDER_ID, $order_id);
        $transaction = TransactionPeer::doSelectOne($c);
        //echo var_dump($transaction);
        $order->setOrderStatusId(sfConfig::get('app_status_completed', 3)); //completed
        //$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 3)); //completed
        $transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3)); //completed
        if ($transaction->getAmount() > $order_amount) {
            //error
            $order->setOrderStatusId(sfConfig::get('app_status_error', 5)); //error in amount
            $transaction->setTransactionStatusId(sfConfig::get('app_status_error', 5)); //error in amount
            //$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 5)); //error in amount
        } else if ($transaction->getAmount() < $order_amount) {
            //$extra_refill_amount = $order_amount;
            $order->setExtraRefill($order_amount);
            $transaction->setAmount($order_amount);
        }
        //set active agent_package in case customer was registerred by an affiliate
        if ($order->getCustomer()->getAgentCompany()) {
            $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
        }
        $ticket_id = $request->getParameter('transact');
        //set subscription id in case 'use current c.c for future auto refills' is set to 1
        //set auto_refill amount



        $order->save();
        $transaction->save();

        $this->customer = $order->getCustomer();
        $c = new Criteria;
        $c->add(CustomerPeer::ID, $order->getCustomerId());
        $customer = CustomerPeer::doSelectOne($c);
        echo "ag" . $agentid = $customer->getReferrerId();
        echo "prid" . $productid = $order->getProductId();
        echo "trid" . $transactionid = $transaction->getId();
        if (isset($agentid) && $agentid != "") {
            echo "getagentid";
            commissionLib::refilCustomer($agentid, $productid, $transactionid);
        }

        //TODO ask if recharge to be done is same as the transaction amount
        //die;
        $exest = $order->getExeStatus();
        if ($exest == 1) {

        } else {
            //  Fonet::recharge($this->customer, $transaction->getAmount());
            $vat = 0;

            $TelintaMobile = '46' . $this->customer->getMobileNumber();
            $emailId = $this->customer->getEmail();
            $OpeningBalance = $transaction->getAmount();
            $customerPassword = $this->customer->getPlainText();
            $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0, 1);     // bcdef
            if ($getFirstnumberofMobile == 0) {
                $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                $TelintaMobile = '46' . $TelintaMobile;
            } else {
                $TelintaMobile = '46' . $this->customer->getMobileNumber();
            }

            $unidc = $this->customer->getUniqueid();
            $uidcount=0;
              $uc = new Criteria;
        $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $unidc);
         $uc->addAnd(UniqueIdsPeer::REGISTRATION_TYPE_ID, 3);
        $uidcount = UniqueIdsPeer::doCount($uc);
            echo $unidc;
            echo "<br/>";

            if ($uidcount==1) {
                $cuserid = $this->customer->getId();
                $amt = $OpeningBalance;
                $amtt = CurrencyConverter::convertSekToUsd($amt);
                $Test = ForumTel::rechargeForumtel($cuserid, $amtt);

                $dibsf = new DibsCall();
        $dibsf->setCallurl("refill  original amout SEK:".$amt."converted amout".$amtt."Fr response".$Test);
        $dibsf->save();

                $amt=$amtt;

                $email2 = new DibsCall();
                $email2->setCallurl($amt . $cuserid);

                $email2->save();
            } else {
                //echo $OpeningBalance."Balance";
                //echo "<br/>";
                //This is for Recharge the Customer
                $MinuesOpeningBalance = $OpeningBalance * 3;
                Telienta::recharge($this->customer, $OpeningBalance);
                $email2 = new DibsCall();
                $email2->setCallurl('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name=' . $unidc . '&amount=' . $OpeningBalance . '&type=customer');
                $email2->save();
                $email2 = new DibsCall();
                $email2->setCallurl($telintaAddAccountCB);
                $email2->save();


                //This is for Recharge the Account
                //this condition for if follow me is Active
                $getvoipInfo = new Criteria();
                $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customer->getMobileNumber());
                $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                if (isset($getvoipInfos)) {
                    $voipnumbers = $getvoipInfos->getNumber();
                    $voip_customer = $getvoipInfos->getCustomerId();
                } else {

                }
            }
            $MinuesOpeningBalance = $OpeningBalance * 3;


            $subject = $this->getContext()->getI18N()->__('Payment Confirmation');
            $sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
            $sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

            $recepient_email = trim($this->customer->getEmail());
            $recepient_name = sprintf('%s %s', $this->customer->getFirstName(), $this->customer->getLastName());
            $referrer_id = trim($this->customer->getReferrerId());

            if ($referrer_id):
                $c = new Criteria();
                $c->add(AgentCompanyPeer::ID, $referrer_id);

                $recepient_agent_email = AgentCompanyPeer::doSelectOne($c)->getEmail();
                $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
            endif;

            //send email

            $unidid = $this->customer->getUniqueid();
            if ((int) $unidid > 200000) {
                $message_body = $this->getPartial('customer/order_receipt_us', array(
                            'customer' => $this->customer,
                            'order' => $order,
                            'transaction' => $transaction,
                            'vat' => $vat,
                            'wrap' => false
                        ));
            } else {
                $message_body = $this->getPartial('payments/order_receipt', array(
                            'customer' => $this->customer,
                            'order' => $order,
                            'transaction' => $transaction,
                            'vat' => $vat,
                            'wrap' => false
                        ));
            }

            emailLib::sendCustomerRefillEmail($this->customer, $order, $transaction);
        }

        $order->setExeStatus(1);
        $order->save();
//echo 'NOOO';
// Update cloud 9
        //c9Wrapper::equateBalance($this->customer);
//echo 'Comeing';
        //set vat

        echo 'Yes';
        return sfView::NONE;
    }
 /*******************************************************Customer Registeration Dibs Call ***********************************************/
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
                $uc->addAnd(UniqueIdsPeer::SIM_TYPE_ID,$customer_product->getProduct()->getSimTypeId());
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
                        $uc->addAnd(UniqueIdsPeer::SIM_TYPE_ID,$customer_product->getProduct()->getSimTypeId());
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
                    Telienta::recharge($this->customers, $OpeningBalance,"Tipsa en van " . $invite->getInviteNumber());
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
          
        }
        
    }


 /*******************************************************Customer Registeration US Dibs Call ***********************************************/
    public function executeConfirmpaymentus(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);

        $urlvalrest="Confirm-payment-us-".var_export($_REQUEST,true);
        $emaires = new DibsCall();
        $emaires->setCallurl($urlvalrest);
        $emaires->save();
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
                    //$c->setLimit(1);
                    $c->add(SeVoipNumberPeer::IS_ASSIGNED, 0);
                    if (SeVoipNumberPeer::doCount($c) < 10) {
                      //  emailLib::sendErrorInTelinta("Resenumber about to Finis", "Resenumbers in the landncall are lest then 10 . ");
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
                    Telienta::createReseNumberAccount($voipnumbers, $this->customer, $TelintaMobile,11118);


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
/***********************************Agent Account Refill Dibs Call*******************************************/


   public function executeAccountRefill(sfWebRequest $request){

      //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
     changeLanguageCulture::languageCulture($request,$this);


                $ca = new Criteria();
		$ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                $agent = AgentCompanyPeer::doSelectOne($ca);
		$this->forward404Unless($agent);


                if(isset($_REQUEST['error'] )) {


                    $agent_order_id = $request->getParameter('orderid');

                     $aoc = new Criteria();
                     $aoc->add(AgentOrderPeer::AGENT_ORDER_ID, $agent_order_id);
                     $agent_order = AgentOrderPeer::doSelectOne($aoc);

                     $this->getUser()->setFlash('message', 'Your Credit Card Information was not approved');
                     $this->agent_order_id = $agent_order_id;
                     $this->agent_order = $agent_order;

                }else{


                    $c = new Criteria();
                    $agent_order = new AgentOrder();
                    $agent_order->setAgentCompanyId($agent->getId());
                    $agent_order->setOrderDescription(2);///// By Credit Card for agent
                    $agent_order->setStatus('1');
                    $agent_order->save();

                    $agent_order->setAgentOrderId('a0'.$agent_order->getId());
                    $agent_order->save();

                    $this->agent_order = $agent_order;

            }return sfView::NONE;
  }
  
  
      public function executeSuspendForumtelCustomer(sfWebRequest $request){
          
         $customer_id = $request->getParameter("customer_id");
         
         $output =  ForumTel::suspendForumtel($customer_id);
         echo $output;
         return sfView::NONE;
      }
      public function executeCreateReseAccountTelinta(sfWebRequest $request){
          $customer_id = $request->getParameter("customer_id");
          $voipnumbers = $request->getParameter("voip");
          $this->customer = CustomerPeer::retrieveByPK($customer_id);
          if ($getFirstnumberofMobile == 0) {
                    $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                    $TelintaMobile = '46' . $TelintaMobile;
                } else {
                    $TelintaMobile = '46' . $this->customer->getMobileNumber();
                }
          Telienta::createReseNumberAccount($voipnumbers, $this->customer, $TelintaMobile);
      }
}
