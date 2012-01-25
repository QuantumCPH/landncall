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


  public function executeAppWebSMS(sfWebrequest $request){

  $sender = $request->getParameter('sender')    ;
  $message = $request->getParameter('message');
  $destination_code = $request->getParameter('code');
  $number = $request->getParameter('number');
  $destination = $destination_code."".$number;


                $mobile = "";
                $customer = NULL;
                $country_code="";

             
               
   if (strlen($sender) == 10 ){
                $country_code = substr($sender,0,2);
                $mobile =  substr($sender,2,8);

            }
         else if (strlen($sender) == 13 ){
                $country_code = substr($sender,0,3);
                $usnumber=true;
                $mobile =  substr($sender,3,10);
         }




                $cr = new Criteria();
                $cr->add(CustomerPeer::MOBILE_NUMBER, $mobile);
                $cr->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
		$customer = CustomerPeer::doSelectOne($cr);

                if(!$customer){
                echo 'error, Mobile Number Not Registered';
                return sfView::NONE;
                }


      $cct = new Criteria();
      $cct->add(CountryPeer::CALLING_CODE,$destination_code);
      $country = CountryPeer::doSelectOne($cct);
      if(!$country){
          echo 'error, Country code: '.$destination_code.' not recgnized';
          return sfView::NONE;;
      }

  if($customer){
      if($sender and $message and $country_code and $number){



      //echo $country;

      $messages = array();

      if(strlen($message) < 142 ){
          $messages[1] = $message."-Sent by zer0call-";
      }
      else if (strlen($message) > 142 and strlen($message) < 302){

            $messages[1]=substr($message,1,142)."-Sent by zer0call-";
            $messages[2]=substr($message,143)."-Sent by zer0call-";

      }else if (strlen($message) > 382){
         $messages[1]=substr($message,1,142)."-Sent by zer0call-";
         $messages[2]=substr($message,143,302)."-Sent by zer0call-";
         $messages[3]=substr($message,303,432)."-Sent by zer0call-";

      }

      $cc = CurrencyConversionPeer::retrieveByPK(1);

      foreach($messages as $sms_text){
          $cbf = new Cbf();
          $cbf->setS('H');
          $cbf->setDa($destination);
          $cbf->setMessage($sms_text);
          $cbf->setCountryId($country->getId());
          $cbf->setMobileNumber($customer->getMobileNumber());


          $cbf->save();

          //recharge fonet

          
          $balance = Fonet::getBalance($customer);
          $amt = number_format($cc->getBppDkk()*$country->getCbfRate(),2);

//          echo '<br/>';
//          echo 'SMS Amount: '.$amt;
//          echo '<br/>';
//          echo 'Balance: '.$balance;
//          echo '<br/>';
          
          if($balance < $amt){
              echo "error, Not Enough Balance, Please Recharge";
              return sfView::NONE;
          }else{
            Fonet::recharge($customer, -($amt));

            $this->customer = $customer;
            $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
            if($getFirstnumberofMobile==0){
              $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
              $TelintaMobile =  '46'.$TelintaMobile ;
            }else{
              $TelintaMobile = '46'.$this->customer->getMobileNumber();
            }
            $uniqueId = $this->customer->getUniqueid();
            $OpeningBalance = '40';
            //This is for Recharge the Customer
            $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=customer&action=manual_charge&name='.$uniqueId.'&amount='.$OpeningBalance);
                       
         
          }
          

          $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                  'DA'=>$destination,
                  'SA' =>$country_code.$mobile,
                  'M'=>$sms_text,
                  'ST'=>'5'
            );


                    $queryString = http_build_query($data,'', '&');
  					$queryString=smsCharacter::smsCharacterReplacement($queryString);
                    $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
                    //echo $res;






      }

      if(substr($res,0,2)=="OK"){

                    echo "success";
                   /* $this->getResponse()->setContentType("text/xml");
                    $this->getResponse()->setContent("<?xml version=\"1.0\"?>
                    <zerocall>
                    <status>ok</status>
                    <message>sms sent</message>
                    </zerocall>");*/


                }else {

                    echo "sms could not be sent";
                    /*$this->getResponse()->setContentType("text/xml");
                    $this->getResponse()->setContent("<?xml version=\"1.0\"?>
                    <zerocall>
                    <status>error</status>
                    <message>sms could not be sent</message>
                    </zerocall>");*/

                }

      }else {

          echo "sms could not be sent";
          /*$this->getResponse()->setContentType("text/xml");
                    $this->getResponse()->setContent("<?xml version=\"1.0\"?>
                    <zerocall>
                    <status>error</status>
                    <message>sms could not be sent</message>
                    </zerocall>");*/
      }

        return sfView::NONE;
            }
                    echo "sms could not be sent";
                   /* $this->getResponse()->setContentType("text/xml");
                    $this->getResponse()->setContent("<?xml version=\"1.0\"?>
                    <zerocall>
                    <status>error</status>
                    <message>mobile number is not registered</message>
                    </zerocall>");
                    return sfView::NONE;*/
                    return sfView::NONE;
}


public function executeAppLogin(sfWebrequest $request){


        $mobile_number = $request->getParameter('mobile_number');
        $password = sha1($request->getParameter('password'));

        $mobile = "";
        $country_code="";


         if (strlen($mobile_number) == 10 ){
                $country_code = substr($mobile_number,0,2);
                $mobile =  substr($mobile_number,2,8);

            }
         else if (strlen($mobile_number) == 13 ){
                $country_code = substr($mobile_number,0,3);
                $mobile =  substr($mobile_number,3,10);
         }


         $c = new Criteria();
	 $c->add(CustomerPeer::MOBILE_NUMBER, $mobile);
	 $c->add(CustomerPeer::PASSWORD, $password);
	 $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
	 $customer = CustomerPeer::doSelectOne($c);





        if ($customer){

                $sc = new Criteria();
                $sc->add(SipPeer::CUSTOMER_ID, $customer->getId());
                $sip = SipPeer::doSelectOne($sc);

                if($sip){
                echo "OK,".$sip->getUser().",",$sip->getPwd();
                }


        }
        else {


        echo "failure, invalid phone number";

        }

        return sfView::NONE;
        }
        public function executeAppGetBalance(sfWebRequest $request){

            $mobile_number = $request->getParameter('mobile_number');
//            echo $mobile_number;
//            echo '<br/>';
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
//                echo $mobile_number;
//                echo '<br/>';
                $c = new Criteria();
                $c->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
                $c->add(CustomerPeer::CUSTOMER_STATUS_ID, sfConfig::get('app_status_completed'));
                $customer = CustomerPeer::doSelectOne($c);
               // echo  $customer->
                  //  echo $customer;
                if($customer){
//                echo 'got customer';
//                    echo '<br/>';
//                echo $customer->getId();
//                 echo '<br/>';
                $fonetBalance = Fonet::getBalance($customer);
                echo number_format($fonetBalance,2);
                }else{
                    echo "error, mobile number is not registered";
                }

                





            return sfView::NONE;

        }








public function executeAppRegistration(sfWebrequest $request){


   //validation patterns
   $den_pattern = "/^[0-9]{10}$/";
   $us_pattern = "/^[0-9]{13}$/";
   $code_pattern = "/^[0-9]{6}$/";
   $agent_pattern = "/^[0-9]{4}$/";
   $product_pattern = "/^[0-9]{2}$/";

   //initialize flags
   $us_num_flag=false;
   $den_num_flag=false;
   $alreadyRegistered = true;
   $invalidMobile = true;
   $invalidEmail = false;
   $is_agent = false;
   $is_product = false;
   $valid_code = true;
   $customer_registered=false;

   //initialize parameter variables
   $full_mobile_number = "";
   $mobile = "";
   $code="";
   $product_code="";
   $agent_code="";
   $name = "";
   $password = "";
   $email = "";

   //initialize object variables
   $agent=NULL;
   $product=NULL;
   $customer= new Customer();
   $order = new CustomerOrder();
   $customer_product = new CustomerProduct();
   $transaction = new Transaction();

   //start function execution
   try{

  //get request parameters
   $name = $request->getParameter('name');
   $full_mobile_number = $request->getParameter('mobile_number');
   $password = $request->getParameter('password');
   $email = $request->getParameter('email');
   $code = $request->getParameter('code');

  //validate code
  if(preg_match($code_pattern,$code)){

//      echo "code pattern matched";
//      echo '<br/>';

      $agent_code = substr($code,0,4);
      $product_code = substr($code,4,5);

//      echo $agent_code;
//      echo '<br/>';
//      echo $product_code;
//      echo '<br/>';

    
      // echo $referrer_cvr;
      if(preg_match($agent_pattern,$agent_code)){
      $c = new Criteria();
      $c->add(AgentCompanyPeer::SMS_CODE,  $agent_code);
      $agent = AgentCompanyPeer::doSelectOne($c);


      }//end if(preg_match($agent_pattern,$agent_code))


     //geting product sms code
     if(preg_match($product_pattern,$product_code)){
     $pc = new Criteria();
     $pc->add(ProductPeer::SMS_CODE, $product_code);
     $product = ProductPeer::doSelectOne($pc);

         if($product and $agent){
             $is_product = true;
             $is_agent = true;
             $valid_code =true;
         }//end if($product and $agent)
     }//end if(preg_match($product_pattern,$product_code))
  }else if ($code!=""){
         $valid_code =false;
  }//if ($code!="")

  if(preg_match($den_pattern,$full_mobile_number)){

      $mobile = substr($full_mobile_number,2,8);

      $invalidMobile = false;
      $mnc = new Criteria();
      $mnc->add(CustomerPeer::MOBILE_NUMBER, $mobile);
      $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
      $cus = CustomerPeer::doSelectOne($mnc);

      //This Function For Get the Enable Country Id =
      $calingcode = substr($full_mobile_number,0,2);
      $countryId = $this->getEnableCountryId($calingcode);
     
      
      if(!$cus){

      $alreadyRegistered = false;
      $customer->setCountryId($countryId);
      $customer->setMobileNumber($mobile);
      }

      // if(preg_match($den_pattern,$full_mobile_number))
      }else if(preg_match($us_pattern,$full_mobile_number)){

      $invalidMobile = false;
      $mobile = substr($full_mobile_number,3,10);
      $mnc = new Criteria();
      $mnc->add(CustomerPeer::MOBILE_NUMBER, $mobile);
      $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
      $cus = CustomerPeer::doSelectOne($mnc);

      //This Function For Get the Enable Country Id =
      $calingcode = substr($full_mobile_number,0,3);
      $countryId = $this->getEnableCountryId($calingcode);
     
     
          if(!$cus){

//          echo $mobile;
//          echo '<br/>';
          $alreadyRegistered = false;
          $customer->setCountryId($countryId);
          $customer->setMobileNumber($mobile);

          }// if(!$cus)

  }// if(preg_match($us_pattern,$full_mobile_number))


//    Listing flags
//   $us_num_flag=false;
//   $den_num_flag=false;
//   $alreadyRegistered = true;
//   $invalidMobile = true;
//   $invalidEmail = true;
//   $is_agent = false;
//   $is_product = false;
//   $valid_code = true;

 if(!$invalidMobile and !$alreadyRegistered and $valid_code and !$invalidEmail){

 //echo 'saving customer';

//        echo "inside if(invalidMobile and !alreadyRegistered and valid_code and !invalidEmail)";

        $customer->setFirstName($name);
        $customer->setLastName(".");
        $customer->setMobileNumber($mobile);
        $customer->setPassword($password);
        $customer->setEmail($email);
            if($is_agent){
            $customer->setReferrerId($agent->getId());
            }
        $customer->setCity("Application");
        $customer->setAddress("not given");
        $customer->setTelecomOperatorId(13);
        $customer->setDeviceId(2191);
        $customer->setCustomerStatusId(sfConfig::get('app_status_completed'));
        $customer->setRegistrationTypeId(5);
        $customer->setPlainText($password);
        $customer->save();
//        echo '<br/>';
//        echo 'customer'.$customer->save();
//        echo '<br/>';

        if($is_product){

            $order->setProductId($product->getId());
            $order->setCustomerId($customer->getId());
            $order->setExtraRefill($order->getProduct()->getInitialBalance());
            $order->setIsFirstOrder(1);
            $order->setOrderStatusId(3);
            $order->save();
//            echo 'order with product'.$order->save();
//            echo '<br/>';
        }else{

             $order->setProductId(8);
            $order->setCustomerId($customer->getId());
            $order->setExtraRefill($order->getProduct()->getInitialBalance());
            $order->setIsFirstOrder(1);
            $order->setOrderStatusId(3);
            $order->save();
//            echo 'order without product'.$order->save();
//            echo '<br/>';
        }// if($is_product)

//        echo "product saved, moving to customer_product";
//        echo $order->getId();
//        echo $order->getCustomerId();
//        echo $order->getProductId();

        $customer_product->setCustomerId($order->getCustomerId());
	$customer_product->setProductId($order->getProductId());
        $customer_product->save();
//	echo 'customer_product'.$customer_product->save();
//        echo '<br/>';


        $transaction->setAgentCompanyId($customer->getReferrerId() );
    	$transaction->setAmount($order->getProduct()->getPrice() - $order->getProduct()->getInitialBalance() + $order->getExtraRefill());
    	$transaction->setDescription($this->getContext()->getI18N()->__('Registrering inkl. taletid'));
    	$transaction->setOrderId($order->getId());
    	$transaction->setCustomerId($customer->getId());
        $transaction->setTransactionStatusId(3);
        $transaction->save();
//        echo 'transaction'.$transaction->save();
//        echo '<br/>';

        $customer_registered=true;

 if($is_product){

        if($product->getSmsCode()!='01'){


$sms_text="Velkommen til Zerocall, dit nummer er hermed aktiveret til at kunne benytte Zerocall Out, og du kan nu ringe til de billigste priser til hele verden.
Din login informationer er:
Brugernavn:".$mobile."  Adgangskode: ".$mobile."
Har du behov for hj?lp er ud velkommen til at kontakte vores kunde service p?: support@landncall.com

Mange hilsner
LandNCall AB ? Support?
";

             //Fonet::registerFonet($customer);
                $c = new Criteria();
		$c->add(FonetCustomerPeer::ACTIVATED_ON, NULL);
		$fonet_customer = FonetCustomerPeer::doSelectOne($c);
                $customer->setFonetCustomerId($fonet_customer->getFonetCustomerId());
                $fonet_customer->setActivatedOn("2011-02-10 09:40:53");
                $fonet_customer->save();
                $customer->save();



         }// end if($product->getSmsCode()!='01')
         else if($product->getSmsCode()=='01'){
             echo 'product found'.$product->getSmsCode();
             $sms_text="Velkommen til Zerocall, dit nummer er hermed aktiveret til at kunne benytte Zerocall Free, og du kan nu ringe til 60 destinationer. Har du behov for hj?lp er ud velkommen til at kontakte vores kunde service p?: support@landncall.com";
             $sms_text.="Mange hilsner";
             $sms_text.= "LandNCall AB ? Support";





             $query_vars = array(
			'Action'=>'CustUpdate',
			'ParentCustomID'=>1393238,
                        'AniNo'=>$mobile,
	  		'DdiNo'=>25969696,
                        'RateTableName'=>'ZapnaB2Cfree',
                        'Trace'=>1,
			'CustomID'=>$customer->getFonetCustomerId()
	  	);

		$query_string = http_build_query($query_vars);
		$resp = file_get_contents('http://fax.fonet.dk/cgi-bin/ZeroCallV2Control.pl?'.$query_string);
   $customer->save();

   //////////////////////////////////////////////////////////////////////////////////////
                $this->customer =$customer;
                $gentid=$customer->getReferrerId();
                $productid=$product->getId();
                $transactionid=$transaction->getId();
                if(isset($gentid) && $gentid!=""){
               $massage=commissionLib::registrationCommission($gentid,$productid,$transactionid);
               if(isset($massage)&& $massage=="balance_error" ){

                   $sms_text = "your balance is low";
         echo "error, your balance is low";

die;

               }
                }


           //////////////////////////////////////////////////////////////////////////     /
                Fonet::registerFonet($customer);
                Fonet::recharge($customer, $order->getExtraRefill());
                   $customer->setCustomerStatusId(3);
                $customer->save();


                $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
                if($getFirstnumberofMobile==0){
                  $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                  $TelintaMobile =  '46'.$TelintaMobile ;
                }else{
                  $TelintaMobile = '46'.$this->customer->getMobileNumber();
                }
                $uniqueId = $this->customer->getUniqueid();
              $emailId = $this->customer->getEmail();
              $OpeningBalance = $order->getExtraRefill();
              $customerPassword = $this->customer->getPlainText();

              //Section For Telinta Add Cusomter
              $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_LandNcall&action=add&name='.$uniqueId.'&currency=SEK&opening_balance=0&credit_limit=0&enable_dialingrules=Yes&int_dial_pre=00&email='.$emailId.'&type=customer');

              // For Telinta Add Account
              $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$uniqueId.'&customer='.$uniqueId.'&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=0&billing_model=1&password=asdf1asd');
              $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$TelintaMobile.'&customer='.$uniqueId.'&opening_balance=0&credit_limit=&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$TelintaMobile.'&customer='.$uniqueId.'&opening_balance=0&credit_limit=&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');

              //This is for Recharge the Customer
              //$telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');

                //echo 'fonet id: ';
                //echo $customer->getFonetId();
//             echo "zerocall free";
//             echo $resp;
//             echo 'zerocall free customer registered';

         }//end if($product->getSmsCode()=='01')


         }else{
             //echo 'product not found';
             $sms_text="Velkommen til Zerocall, dit nummer er hermed aktiveret til at kunne benytte Zerocall Out, og du kan nu ringe til de billigste priser til hele verden.
Din login informationer er:
Brugernavn:".$mobile."  Adgangskode: ".$mobile."
Har du behov for hj?lp er ud velkommen til at kontakte vores kunde service p?: support@landncall.com

Mange hilsner
Zerocall ? Support?
";
////////////////////////////////////////////////////////////////////////////////////
               $this->customer =$customer;
                $gentid=$customer->getReferrerId();
                $productid=$product->getId();
                $transactionid=$transaction->getId();
                if(isset($gentid) && $gentid!=""){
               $massage=commissionLib::registrationCommission($gentid,$productid,$transactionid);
               if(isset($massage)&& $massage=="balance_error" ){

                   $sms_text = "your balance is low";
         echo "error, your balance is low";

die;
         
               }
                }

                /////////////////////////////////////////////////////////////////////////////////////
             Fonet::registerFonet($customer);
             Fonet::recharge($customer, $order->getExtraRefill());
//           echo "zerocall paid without promo";
//           echo "<br/>";
            $customer->save();
//                echo 'fonet id: ';
//                echo $customer->getFonetId();

            $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
            if($getFirstnumberofMobile==0){
              $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
              $TelintaMobile =  '46'.$TelintaMobile ;
            }else{
              $TelintaMobile = '46'.$this->customer->getMobileNumber();
            }
            $uniqueId = $this->customer->getUniqueid();
              $emailId = $this->customer->getEmail();
              $OpeningBalance = $order->getExtraRefill();
              $customerPassword = $this->customer->getPlainText();

              //Section For Telinta Add Cusomter
              $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_LandNcall&action=add&name='.$uniqueId.'&currency=SEK&opening_balance=0&credit_limit=0&enable_dialingrules=Yes&int_dial_pre=00&email='.$emailId.'&type=customer');

              // For Telinta Add Account
              $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$uniqueId.'&customer='.$uniqueId.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=0&billing_model=1&password='.$customerPassword);
              $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);

              //This is for Recharge the Customer
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');
              
    }

      // Assign SIP user / pwd
             //echo "SIP is being Assigned 1";
              $sc = new Criteria();
              $sc->add(SipPeer::ASSIGNED, false);
              $sip = SipPeer::doSelectOne($sc);

              //echo "SIP is being Assigned 2";

              $sip->setCustomerId($customer->getId());
              $sip->setAssigned(true);
              $sip->save();

              $cc = new Criteria();
              $cc->add(CountryPeer::ID, $customer->getCountryId());
              $country = CountryPeer::doSelectOne($cc);

              $mobile = $country->getCallingCode().$customer->getMobileNumber();

//              if(strlen($mobile)==11){
////                 echo 'mobile # = 11' ;
//                 $mobile = '00'.$mobile;
//             }

              $IMdata = array(
                      'type' => 'add',
                      'secret'=>'rnRQSRD0',
                      'username'=>$mobile,
                      'password'=>$customer->getPlainText(),
                      'name' =>$customer->getFirstName(),
                      'email'=>$customer->getEmail()
                );

              
                emailLib::sendCustomerRegistrationViaAgentAPPEmail($this->customer,$order);
               $queryString = http_build_query($IMdata,'', '&');
               $res2 = file_get_contents('http://im.zerocall.com:9090/plugins/userService/userservice?'.$queryString);

//               echo 'http://im.zerocall.com:9090/plugins/userService/userservice?'.$queryString;
//               echo '<br/>';

              //echo "SIP is being Assigned 3";
              //echo $res2;
              echo "OK,".$sip->getUser().",".$sip->getPwd();
 //end of if(!$invalidMobile and !$alreadyRegistered and $valid_code and !$invalidEmail)
 }else{


//     //initialize flags
//   $us_num_flag=false;
//   $den_num_flag=false;
//   $alreadyRegistered = true;
//   $invalidMobile = true;
//   $invalidEmail = false;
//   $is_agent = false;
//   $is_product = false;
//   $valid_code = true;
//   $customer_registered=false;

     $sms_text = "Customer not registered, please retry. ";
     if($invalidMobile){
         $sms_text = "error, invalid Mobile Number";
         echo 'error, invalid Mobile Number';
     }else if ($invalidEmail){
         $sms_text = "error, invalid Email";
         echo 'customer not found';
     }else if($alreadyRegistered){
         $sms_text = "error, Number Already Registered";
         echo "error, Number Already Registered";
     }else if(!$valid_code){
         $sms_text = "error, invalid promo code";
         echo "error, invalid promo code";
     }


 }






//         $cc = new Criteria();
//         $cc->add(CountryPeer::ID, $customer->getCountryId());
//         $country_code = CountryPeer::doSelectOne($cc)->getCallingCode();
//
//        $data = array(
//                  'S' => 'H',
//                  'UN'=>'zapna1',
//                  'P'=>'Zapna2010',
//                  'DA'=>$country_code.$mobile,
//                  'SA' =>'zer0call',
//                  'M'=>$sms_text,
//                  'ST'=>'5'
//            );

//        $queryString = http_build_query($data,'', '&');
//        echo $sms_text;
//        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
//        echo $res;



     //log registration
//      $app_log .= $sms_text;
//      $app_log .= "\n";
//      file_put_contents($app_log_data_file, $app_log, FILE_APPEND);
  }catch(Exception $e){
//      $app_log .= $e->getMessage();
//      $app_log .= "\n";
//      file_put_contents($app_log_data_file, $app_log, FILE_APPEND);
  }//end try catch

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
                      $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');
                      //This is for Recharge the Account
                      //this condition for if follow me is Active
                        $getvoipInfo = new Criteria();
                        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customer->getMobileNumber());
                        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                        if(isset($getvoipInfos)){
                            $voipnumbers = $getvoipInfos->getNumber() ;
                            $voip_customer = $getvoipInfos->getCustomerId() ;
                            //$telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$voipnumbers.'&amount='.$OpeningBalance.'&type=account');
                        }else{
                           // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=account');
                        }
                      
                     // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name=a'.$TelintaMobile.'&amount='.$OpeningBalance.'&type=account');
                     // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name=cb'.$TelintaMobile.'&amount='.$OpeningBalance.'&type=account');

                      $MinuesOpeningBalance = $OpeningBalance*3;
                      //type=<account_customer>&action=manual_charge&name=<name>&amount=<amount>
                      //This is for Recharge the Customer
                     // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=customer&action=manual_charge&name='.$uniqueId.'&amount='.$MinuesOpeningBalance);


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
public function executeConfirmPayment(sfWebRequest $request)
  {

print_r($_REQUEST);

die;
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
        echo $order_id.'<br />';
  	$subscription_id = $request->getParameter('subscriptionid');        
  	$this->logMessage('sub id: '.$subscription_id);
        $order_amount = $request->getParameter('amount')/100;

  	$this->forward404Unless($order_id || $order_amount);

	//get order object
  	$order = CustomerOrderPeer::retrieveByPK($order_id);

  	//check to see if that customer has already purchased this product
  	$c = new Criteria();
  	$c->add(CustomerProductPeer::CUSTOMER_ID, $order->getCustomerId());
  	$c->addAnd(CustomerProductPeer::PRODUCT_ID, $order->getProductId());
  	$c->addJoin(CustomerProductPeer::CUSTOMER_ID, CustomerPeer::ID);
  	$c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID, sfConfig::get('app_status_new'), Criteria::NOT_EQUAL);

        echo 'retrieve order id: '.$order->getId().'<br />';

  	if (CustomerProductPeer::doCount($c)!=0)
  	{
  		echo 'Customer is already registered.';
  		//exit the script successfully
  		return sfView::NONE;
  	}

  	//set subscription id
  	$order->getCustomer()->setSubscriptionId($subscription_id);

  	//set auto_refill amount
  	if (isset($ticket_id) && $ticket_id!="")
  	{
  		
                echo 'is autorefill activated';
                //auto_refill_amount
  		$auto_refill_amount_choices = array_keys(ProductPeer::getRefillHashChoices());

  		$auto_refill_amount = in_array($request->getParameter('USER_ATTR_2'), $auto_refill_amount_choices)?$request->getParameter('USER_ATTR_2'):$auto_refill_amount_choices[0];
  		$order->getCustomer()->setAutoRefillAmount($auto_refill_amount);

  		//auto_refill_lower_limit
  		$auto_refill_lower_limit_choices = array_keys(ProductPeer::getAutoRefillLowerLimitHashChoices());
  		$auto_refill_min_balance = in_array($request->getParameter('USER_ATTR_3'), $auto_refill_lower_limit_choices)?$request->getParameter('USER_ATTR_3'):$auto_refill_lower_limit_choices[0];
  		$order->getCustomer()->setAutoRefillMinBalance($auto_refill_min_balance);
                $order->getCustomer()->setTicketval($ticket_id);
  	}

  	//if order is already completed > 404
  	$this->forward404Unless($order->getOrderStatusId()!=sfConfig::get('app_status_completed'));
  	$this->forward404Unless($order);

        echo 'processing order <br />';

  	$c = new Criteria;
  	$c->add(TransactionPeer::ORDER_ID, $order_id);
  	$transaction = TransactionPeer::doSelectOne($c);

        echo 'retrieved transaction<br />';

  	if($transaction->getAmount() > $order_amount || $transaction->getAmount() < $order_amount){
  		//error
  		$order->setOrderStatusId(sfConfig::get('app_status_error')); //error in amount
  		$transaction->setTransactionStatusId(sfConfig::get('app_status_error')); //error in amount
  		$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_error')); //error in amount
                echo 'setting error <br /> ';

  	}
  	else {
  		//TODO: remove it
  		$transaction->setAmount($order_amount);

	  	$order->setOrderStatusId(sfConfig::get('app_status_completed')); //completed
	  	$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed')); //completed
	  	$transaction->setTransactionStatusId(sfConfig::get('app_status_completed')); //completed
                 echo 'transaction=ok <br /> ';
	  	$is_transaction_ok = true;
  	}


  	$product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();

  	$product_price_vat = .20 * $product_price;

  	$order->setQuantity(1);

  	//set active agent_package in case customer
  	if ($order->getCustomer()->getAgentCompany())
  	{
  		$order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
  	}


  	
  	

	 echo 'saving order '.$order->save().'<br /> ';
         echo 'saving transaction '.$transaction->save().' <br /> ';
  	if ($is_transaction_ok)
  	{

                echo 'Assigning Customer ID <br/>';
	  	//set customer's proudcts in use
	  	$customer_product = new CustomerProduct();

	  	$customer_product->setCustomer($order->getCustomer());
	  	$customer_product->setProduct($order->getProduct());

	  	$customer_product->save();

	  	//register to fonet
	  	$this->customer = $order->getCustomer();

	  //	Fonet::registerFonet($this->customer);
	  	//recharge the extra_refill/initial balance of the prouduct
		//Fonet::recharge($this->customer, $order->getExtraRefill());

                echo 'Fonet Id assigned ID <br/>';


                $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
                if($getFirstnumberofMobile==0){
                  $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                  $TelintaMobile =  '46'.$TelintaMobile ;
                }else{
                  $TelintaMobile = '46'.$this->customer->getMobileNumber();
                }
                 $uniqueId = $this->customer->getUniqueid();
              $emailId = $this->customer->getEmail();
              $OpeningBalance = $order->getExtraRefill();
              $customerPassword = $this->customer->getPlainText();

              //Section For Telinta Add Cusomter
              $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_LandNcall&action=add&name='.$uniqueId.'&currency=SEK&opening_balance=0&credit_limit=0&enable_dialingrules=Yes&int_dial_pre=00&email='.$emailId.'&type=customer');

              // For Telinta Add Account
              $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$uniqueId.'&customer='.$uniqueId.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=0&billing_model=1&password='.$customerPassword);
              $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);

              //This is for Recharge the Customer
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');
             

                   //echo "SIP is being Assigned 1";
              $sc = new Criteria();
              $sc->add(SipPeer::ASSIGNED, false);
              $sip = SipPeer::doSelectOne($sc);

              //echo "SIP is being Assigned 2";

              $sip->setCustomerId($this->customer->getId());
              $sip->setAssigned(true);
              $sip->save();

              $cc = new Criteria();
              $cc->add(EnableCountryPeer::ID, $this->customer->getCountryId());
              $country = EnableCountryPeer::doSelectOne($cc);

              $mobile = $country->getCallingCode().$this->customer->getMobileNumber();

              if(strlen($mobile)==11){
//                 echo 'mobile # = 11' ;
                 $mobile = '00'.$mobile;
             }

              $IMdata = array(
                      'type' => 'add',
                      'secret'=>'rnRQSRD0',
                      'username'=>$mobile,
                      'password'=>$this->customer->getPlainText(),
                      'name' =>$this->customer->getFirstName(),
                      'email'=>$this->customer->getEmail()
                );
               $queryString = http_build_query($IMdata,'', '&');
               $res2 = file_get_contents('http://im.zerocall.com:9090/plugins/userService/userservice?'.$queryString);


         // Assign C9 number
         if ($order->getProduct()->getId()=='3'){
                
          $c = new Criteria();
  		  $c->add(C9NumbersPeer::IS_ASSIGNED, false);
          $c9number = C9NumbersPeer::doSelectOne($c);

          $this->customer->setC9CustomerNumber($c9number->getC9Number() );

          $c9number->setIsAssigned(true);
          $c9number->save();
          //$customer = $form->save();
          $this->customer->save();

         c9Wrapper::equateBalance($this->customer);
}



			

                //if the customer is invited, Give the invited customer a bonus of 10dkk
                $invite_c = new Criteria();
                $invite_c->add(InvitePeer::INVITE_NUMBER, $this->customer->getMobileNumber());
                $invite_c->add(InvitePeer::INVITE_STATUS, 2);
                $invite =  InvitePeer::doSelectOne($invite_c);

                if($invite!=NULL){


    $invite2 = "assigning bonuss \r\n";
			 echo " assigning bonuss <br />";
                         $invite_data_file=sfConfig::get('sf_data_dir').'/invite.txt';
			file_put_contents($invite_data_file, $invite2, FILE_APPEND);




    $invite->setInviteStatus(3);

                       $sc = new Criteria();
                $sc->add(CustomerCommisionPeer::ID, 1);
                $commisionary = CustomerCommisionPeer::doSelectOne($sc);
                           $comsion=$commisionary->getCommision();


                           
                $products = new Criteria();
                $products->add(ProductPeer::ID, 11);
                $products = ProductPeer::doSelectOne($products);
                $extrarefill=$products->getInitialBalance();
                //if the customer is invited, Give the invited customer a bonus of 10dkk
            
                $inviteOrder = new CustomerOrder();
                $inviteOrder->setProductId(11);
                $inviteOrder->setQuentity(1);
                $inviteOrder->setOrderStatusId(3);
                $inviteOrder->setCustomerId($invite->getCustomerId());
                $inviteOrder->setExtraRefill($extrarefill);
                $inviteOrder->save();
                $OrderId    =   $inviteOrder->getId();
                // make a new transaction to show in payment history
                $transaction_i = new Transaction();
                $transaction_i->setAmount($comsion);
                $transaction_i->setDescription("Invitation Bonus for Mobile Number: ".$invite->getInviteNumber());
                $transaction_i->setCustomerId($invite->getCustomerId());
                $transaction_i->setOrderId($OrderId);
                $transaction_i->setTransactionStatusId(3);

                //send fonet query to update the balance of invitee by 10dkk
           //     Fonet::recharge(CustomerPeer::retrieveByPK($invite->getCustomerId()), $comsion);

                //save transaction & Invite
                $transaction_i->save();
                $invite->save();
				$invite2 .= "transaction & invite saved  \r\n";
				file_put_contents($invite_data_file, $invite2, FILE_APPEND);
    $invitevar=$invite->getCustomerId();



                 if(isset($invitevar)){
                  emailLib::sendCustomerConfirmRegistrationEmail($invite->getCustomerId());
                            }
}
                //send email

	  	$message_body = $this->getPartial('payments/order_receipt', array(
	  						'customer'=>$this->customer,
	  						'order'=>$order,
	  						'transaction'=>$transaction,
	  						'vat'=>$product_price_vat,
	  						'wrap'=>true
	  					));

                $subject = $this->getContext()->getI18N()->__('Payment Confirmation');
		$sender_email = sfConfig::get('app_email_sender_email', 'support@landncall.com');
		$sender_name = sfConfig::get('app_email_sender_name', 'LandNCall AB support');

		$recepient_email = trim($this->customer->getEmail());
		$recepient_name = sprintf('%s %s', $this->customer->getFirstName(), $this->customer->getLastName());


                //This Seciton For Make The Log History When Complete registration complete - Agent
                //echo sfConfig::get('sf_data_dir');
             
              
                
		//Send Email --- when Confirm Payment --- 01/15/11

               $agentid=$customer->getReferrerId();
                $productid=$product->getId();
                $transactionid=$transaction->getId();
                if(isset($agentid) && $agentid!=""){
                commissionLib::registrationCommissionCustomer($agentid,$productid,$transactionid);
                }
                   emailLib::sendCustomerConfirmPaymentEmail($this->customer,$message_body);
                 emailLib::sendCustomerRegistrationViaWebEmail($this->customer,$order);
              
             
	    $this->order = $order;

  	}//end if
  	else
  	{
  		$this->logMessage('Error in transaction.');

  	} //end else

  	return sfView::NONE;
  }
public function executeTest(sfWebrequest $request){
$amt=100;
echo  $Tes=CurrencyConverter::convertSekToUsd($amt);




 
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

  public function executeSMS(sfWebRequest $request)
  {


  	$sms = SMS::receive($request);

  	if ($sms)
  	{
  		//take action
  		$valid_keywords = array('ZEROCALLS', 'ZEROCALLR', 'ZEROCALLN');

  		if (in_array($sms->getKeyword(), $valid_keywords))
  		{
  			//get voucher info
  			$c = new Criteria();

  			$c->add(VoucherPeer::PIN_CODE, $sms->getMessage());
  			$c->add(VoucherPeer::USED_ON, null, CRITERIA::ISNULL);

  			$is_voucher_ok = false;
  			$voucher = VoucherPeer::doSelectOne($c);

  			switch (strtolower($sms->getKeyword()))
		  	{
		  		case 'zerocalls': //register + refill
		  			//purchaes a product in 0 rs, and 200 refill

		  			//create customer

		  			//create order for a product

		  			//don't create trnsaction for product order

		  			//create refill order for product
		  			//create transaction for refill order

		  			if ($voucher)
		  			{
		  				$is_voucher_ok = $voucher->getType()=='s';

		  				$is_voucher_ok = $is_voucher_ok &&
		  					 ($voucher->getAmount()==200);
		  			}

		  			if ($is_voucher_ok)
		  			{
		  				//check if customer already exists
		  				if ($this->is_mobile_number_exists($sms->getMobileNumber()))
		  				{
		  					$message = $this->getContext()->getI18N()->__('
		  						You mobile number is already registered with LandNCall AB.
		  					');

		  					echo $message;
		  					SMS::send($message, $sms->getMobileNumber());
		  					break;
		  				}

                                              //This Function For Get the Enable Country Id =
                                              $calingcode = '45';
                                              $countryId = $this->getEnableCountryId($calingcode);
                                              
			  			//create a customer
			  			$customer = new Customer();
                                                
			  			$customer->setMobileNumber($sms->getMobileNumber());
			  			$customer->setCountryId($countryId); //denmark;
			  			$customer->setAddress('Street address');
			  			$customer->setCity('City');
			  			$customer->setDeviceId(1);
			  			$customer->setEmail($sms->getMobileNumber().'@zerocall.com');
			  			$customer->setFirstName('First name');
			  			$customer->setLastName('Last name');

			  			$password  = substr(md5($customer->getMobileNumber() .  'jhom$brabar_x'),0,8);
			  			$customer->setPassword($password);

			  			//crete an order of startpackage
			  			$customer_order = new CustomerOrder();
			  			$customer_order->setCustomer($customer);
			  			$customer_order->setProductId(1);
			  			$customer_order->setExtraRefill($voucher->getAmount());
			  			$customer_order->setQuantity(0);
			  			$customer_order->setIsFirstOrder(true);

			  			//set customer_product

			  			$customer_product = new CustomerProduct();

			  			$customer_product->setCustomer($customer);
			  			$customer_product->setProduct($customer_order->getProduct());

			  			//crete a transaction of product price
			  			$transaction = new Transaction();
			  			$transaction->setAmount($voucher->getAmount());
			  			$transaction->setDescription($this->getContext()->getI18N()->__('Product  purchase & refill, via voucher'));
			  			$transaction->setOrderId($customer_order->getId());
			  			$transaction->setCustomer($customer);


			  			$customer->setCustomerStatusId(sfConfig::get('app_status_completed', 3));
			  			$customer_order->setOrderStatusId(sfConfig::get('app_status_completed', 3));
			  			$transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3));


			  			$customer->save();
			  			$customer_order->save();
			  			$customer_product->save();
			  			$transaction->save();


			  			//save voucher so it can't be reused
			  			$voucher->setUsedOn(date('Y-m-d'));

			  			$voucher->save();

			  			//register with fonet
			  			Fonet::registerFonet($customer);
			  			Fonet::recharge($customer, $transaction->getAmount());

                                                $this->customer = $customer;
                                                $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
                                                if($getFirstnumberofMobile==0){
                                                  $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                                                  $TelintaMobile =  '46'.$TelintaMobile ;
                                                }else{
                                                  $TelintaMobile = '46'.$this->customer->getMobileNumber();
                                                }
                                                $uniqueId = $this->customer->getUniqueid();
                                              $emailId = $this->customer->getEmail();
                                              $OpeningBalance = $transaction->getAmount();
                                              $customerPassword = $this->customer->getPlainText();

                                              //Section For Telinta Add Cusomter
                                              $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_LandNcall&action=add&name='.$uniqueId.'&currency=SEK&opening_balance=0&credit_limit=0&enable_dialingrules=Yes&int_dial_pre=00&email='.$emailId.'&type=customer');

                                              // For Telinta Add Account
                                              $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$uniqueId.'&customer='.$uniqueId.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=0&billing_model=1&password='.$customerPassword);
                                              $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);
                                              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);

                                              //This is for Recharge the Customer
                                              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');



			  			$message = $this->getContext()->getI18N()->__('
			  			You have been registered to ZerOcall.' /* \n
			  			You can use following login information to access your account.\n
			  			Email: '. $customer->getEmail(). '\n' .
			  			'Password: ' . $password */
			  			);

			  			echo $message;
			  			SMS::send($message, $customer->getMobileNumber());


		  			}
		  			else
		  			{
		  				$invalid_pin_sms = SMS::send($this->getContext()->getI18N()->__('Invalid pin code.'), $sms->getMobileNumber());
		  				echo $invalid_pin_sms;
		  				$this->logMessage('invaild pin sms sent to ' . $sms->getMobileNumber());
		  			}

		  			break;
		  		case 'zerocallr': //refill
		  			//check if mobile number exists?

		  			//create an order for sms refill

		  			//create a transaction
		  			if ($voucher)
		  			{
		  				$is_voucher_ok = $voucher->getType()=='r';

		  				$valid_refills = array(100, 200, 500);

		  				$is_voucher_ok = $is_voucher_ok && in_array($voucher->getAmount(), $valid_refills);
		  			}

		  			if ($is_voucher_ok)
		  			{
		  				//check if customer already exists
		  				if (!$this->is_mobile_number_exists($sms->getMobileNumber()))
		  				{
		  					$message = $this->getContext()->getI18N()->__('
		  						Your mobile number is not registered with LandNCall AB.
		  					');

		  					echo $message;
		  					SMS::send($message, $sms->getMobileNumber());
		  					break;
		  				}
			  			//get the customer

		  				$c = new Criteria();
		  				$c->add(CustomerPeer::MOBILE_NUMBER, $sms->getMobileNumber());


			  			$customer = CustomerPeer::doSelectOne($c);

			  			//create new customer order
			  			$customer_order = new CustomerOrder();
			  			$customer_order->setCustomer($customer);

			  			//get customer product

			  			$c = new Criteria();
			  			$c->add(CustomerProductPeer::CUSTOMER_ID, $customer->getId());

			  			$customer_product = CustomerProductPeer::doSelectOne($c);

			  			//set customer product
			  			$customer_order->setProduct($customer_product->getProduct());

			  			$customer_order->setExtraRefill($voucher->getAmount());
			  			$customer_order->setQuantity(0);
			  			$customer_order->setIsFirstOrder(false);


			  			//crete a transaction of product price
			  			$transaction = new Transaction();
			  			$transaction->setAmount($voucher->getAmount());
			  			$transaction->setDescription($this->getContext()->getI18N()->__('LandNCall AB  Refill, via voucher'));
			  			$transaction->setOrderId($customer_order->getId());
			  			$transaction->setCustomer($customer);


			  			$customer_order->setOrderStatusId(sfConfig::get('app_status_completed', 3));
			  			$transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3));

			  			$customer_order->save();
			  			$transaction->save();

			  			Fonet::recharge($customer, $transaction->getAmount());


			  			//save voucher so it can't be reused
			  			$voucher->setUsedOn(date('Y-m-d H:i:s'));

			  			$voucher->save();

			  			$message = $this->getContext()->getI18N()->__('
			  			You account has been topped up.' /* \n
			  			You can use following login information to access your account.\n
			  			Email: '. $customer->getEmail(). '\n' .
			  			'Password: ' . $password */
			  			);

			  			echo $message;
			  			SMS::send($message, $sms->getMobileNumber());


		  			}
		  			else
		  			{
		  				$invalid_pin_sms = SMS::send($this->getContext()->getI18N()->__('Invalid pin code.'), $sms->getMobileNumber());
		  				echo $invalid_pin_sms;
		  				$this->logMessage('invaild pin sms sent to ' . $sms->getMobileNumber());
		  			}

		  			break;
		  		case 'zerocalln':
		  			//purchases a 100 product, no refill

		  			//check if pin code
		  			// pin code matches
		  			// not used before
		  			//	type is n, amount eq to gt than product price



		  			if ($voucher)
		  			{
		  				$is_voucher_ok = $voucher->getType()=='n';

		  				$is_voucher_ok = $is_voucher_ok &&
		  					 ($voucher->getAmount()>=ProductPeer::retrieveByPK(1)->getPrice());
		  			}

		  			if ($is_voucher_ok)
		  			{
		  				//check if customer already exists
		  				if ($this->is_mobile_number_exists($sms->getMobileNumber()))
		  				{
		  					$message = $this->getContext()->getI18N()->__('
		  						You mobile number is already registered with LandNCall AB.
		  					');

		  					echo $message;
		  					SMS::send($message, $sms->getMobileNumber());
		  					break;
		  				}

                                                //This Function For Get the Enable Country Id =
                                                $calingcode = '45';
                                                $countryId = $this->getEnableCountryId($calingcode);

			  			//create a customer
			  			$customer = new Customer();

			  			$customer->setMobileNumber($sms->getMobileNumber());
			  			$customer->setCountryId($countryId); //denmark;
			  			$customer->setAddress('Street address');
			  			$customer->setCity('City');
			  			$customer->setDeviceId(1);
			  			$customer->setEmail($sms->getMobileNumber().'@zerocall.com');
			  			$customer->setFirstName('First name');
			  			$customer->setLastName('Last name');

			  			$password  = substr(md5($customer->getMobileNumber() .  'jhom$brabar_x'),0,8);
			  			$customer->setPassword($password);

			  			//crete an order of startpackage
			  			$customer_order = new CustomerOrder();
			  			$customer_order->setCustomer($customer);
			  			$customer_order->setProductId(1);
			  			$customer_order->setExtraRefill(0);
			  			$customer_order->setQuantity(1);
			  			$customer_order->setIsFirstOrder(true);

			  			//set customer_product

			  			$customer_product = new CustomerProduct();

			  			$customer_product->setCustomer($customer);
			  			$customer_product->setProduct($customer_order->getProduct());

			  			//crete a transaction of product price
			  			$transaction = new Transaction();
			  			$transaction->setAmount($customer_order->getProduct()->getPrice()*$customer_order->getQuantity());
			  			$transaction->setDescription($this->getContext()->getI18N()->__('Product  purchase, via voucher'));
			  			$transaction->setOrderId($customer_order->getId());
			  			$transaction->setCustomer($customer);


			  			$customer->setCustomerStatusId(sfConfig::get('app_status_completed', 3));
			  			$customer_order->setOrderStatusId(sfConfig::get('app_status_completed', 3));
			  			$transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3));


			  			$customer->save();
			  			$customer_order->save();
			  			$customer_product->save();
			  			$transaction->save();


			  			//save voucher so it can't be reused
			  			$voucher->setUsedOn(date('Y-m-d'));

			  			$voucher->save();

			  			//register with fonet
			  			Fonet::registerFonet($customer);

			  			$message = $this->getContext()->getI18N()->__('
			  			You have been registered to LandNCall AB.' /* \n
			  			You can use following login information to access your account.\n
			  			Email: '. $customer->getEmail(). '\n' .
			  			'Password: ' . $password */
			  			);

			  			echo $message;
			  			SMS::send($message, $sms->getMobileNumber());


		  			}
		  			else
		  			{
		  				$invalid_pin_sms = SMS::send($this->getContext()->getI18N()->__('Invalid pin code.'), $sms->getMobileNumber());
		  				echo $invalid_pin_sms;
		  				$this->logMessage('invaild pin sms sent to ' . $sms->getMobileNumber());
		  			}

		  			break;
		  	}
  		}

  	}

  	$this->renderText('completed');

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

public function executeSmsRegisteration(sfWebrequest $request){

  $sms_log_data_file = sfConfig::get('sf_data_dir').'/sms_log.txt';
  $sms_log = "sms registration";
  $sms_log .= "number: ".$request->getParameter('mobile');
  $sms_log .= " keyword: ".$request->getParameter('keyword');
  $sms_log .= " message:".$request->getParameter('message');
  $sms_log .= "\n";
  file_put_contents($sms_log_data_file, $sms_log, FILE_APPEND);

  try{
  $alreadyRegistered = true;
  $invalidMobile = true;
  $mobile = "";
  $number = $request->getParameter('mobile');
  $sms_text="";
  
  if(strlen($number)>=10 and strlen($number) <=10){

      $invalidMobile = false;
      $mnc = new Criteria();
      $mnc->add(CustomerPeer::MOBILE_NUMBER, substr($number,2,8));
      $cus = CustomerPeer::doSelectOne($mnc);

      if(!$cus){

      $mobile = substr($number,2,8);
      echo $number;
      echo '<br/>';
      echo $mobile;
      echo '<br/>';
      $alreadyRegistered = false;
      
      }else{
          $mobile = substr($number,2,8);
          $sms_text = "Mobile is Already Registered";
      }
  }else{
       $sms_text = "Invlaid Mobile Number Format";
  }

  $message = $request->getParameter('message');
  $keyword = $request->getParameter('keyword');

  $agent_code = substr($message,0,4);
  $product_code = substr($message,4,5);

  echo $agent_code;
  echo '<br/>';
  echo $product_code;
  echo '<br/>';

 // echo $referrer_cvr;
  $c = new Criteria();
  $c->add(AgentCompanyPeer::SMS_CODE,  $agent_code);
  $agent = AgentCompanyPeer::doSelectOne($c);



 //geting product sms code
    $pc = new Criteria();
    $pc->add(ProductPeer::SMS_CODE, $product_code);
    $product = ProductPeer::doSelectOne($pc);

    ////////////////////////////////////////////
//       $cp = new Criteria;
//        $cp->add(AgentProductPeer::AGENT_ID, $agent->getId());
//         $agentproductcount = AgentProductPeer::doCount($cp);
//
//        if(isset($agentproductcount) && $agentproductcount>0){
//
//               $cpp = new Criteria;
//        $cpp->add(AgentProductPeer::AGENT_ID, $agent->getId());
//        $cpp->add(AgentProductPeer::PRODUCT_ID,$product->getId());
//        $agentproductcounta = AgentProductPeer::doCount($cpp);
//
//  if(isset($agentproductcounta) && $agentproductcounta==0){
//
//      $sms_text = "this product is not in your  package";
//         echo "error, this product is not in your  package";
//
//  $data = array(
//                  'S' => 'H',
//                  'UN'=>'zapna1',
//                  'P'=>'Zapna2010',
//                  'DA'=>'45'.$mobile,
//                  'SA' =>'Zerocall',
//                  'M'=>$sms_text,
//                  'ST'=>'5'
//            );
//
//
//        $queryString = http_build_query($data,'', '&');
//	$queryString=smsCharacter::smsCharacterReplacement($queryString);
//        echo $sms_text;
//        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
//        echo $res;
//
//        die;
//  }
//
//
//        }



 if($product and $agent and !$invalidMobile and !$alreadyRegistered){


     //This Function For Get the Enable Country Id =
    $calingcode = '45';
    $countryId = $this->getEnableCountryId($calingcode);

        $customer = new Customer();

        $customer->setFirstName($mobile);
        $customer->setLastName($mobile);
        $customer->setMobileNumber($mobile);
        $customer->setPassword($mobile);
        $customer->setEmail($agent->getEmail());
        $customer->setReferrerId($agent->getId());
        $customer->setCountryId($countryId);
        $customer->setCity("");
        $customer->setAddress("");        
        $customer->setTelecomOperatorId(13);
        $customer->setDeviceId(2191);
        $customer->setCustomerStatusId(3);

        $customer->setPlainText($mobile);
      $customer->setRegistrationTypeId(4);
      echo 'customer'.$customer->save();
        echo '<br/>';

        


        $order = new CustomerOrder();    	
    	$order->setProductId($product->getId());
    	$order->setCustomerId($customer->getId());
    	$order->setExtraRefill($order->getProduct()->getInitialBalance());
    	$order->setIsFirstOrder(1);
        $order->setOrderStatusId(3);
    	echo 'order'.$order->save();
        echo '<br/>';
       $this->customer =$customer;
        $transaction = new Transaction();
        $transaction->setAgentCompanyId($customer->getReferrerId() );
    	$transaction->setAmount($order->getProduct()->getPrice() - $order->getProduct()->getInitialBalance() + $order->getExtraRefill());
    	$transaction->setDescription($this->getContext()->getI18N()->__('Registrering inkl. taletid'));
    	$transaction->setOrderId($order->getId());
    	$transaction->setCustomerId($customer->getId());
        $transaction->setTransactionStatusId(3);
        echo 'transaction'.$transaction->save();
        echo '<br/>';

        $customer_product = new CustomerProduct();
        $customer_product->setCustomer($order->getCustomer());
	$customer_product->setProduct($order->getProduct());
	echo 'customer_product'.$customer_product->save();
        echo '<br/>';

  
        

         if($product->getSmsCode()!='01'){
/*$sms_text="Velkommen til Zerocall, dit nummer er hermed aktiveret til at kunne benytte Zerocall Out, og du kan nu ringe til de billigste priser til hele verden.
Din login informationer er:
Brugernavn:".$mobile."  Adgangskode: ".$mobile."
Har du behov for hjaelp er du velkommen til at kontakte vores kunde service paa: support@landncall.com

Mange hilsner
Zerocall Support";*/

$sms_text="Velkommen til Zerocall out.
Sæt Zerocall simkortet i telefonen og du er i gang med at ringe billigt til udlandet.
Huskat registrere dig på www.zerocall.com inden for 24 timer. Dit brugernavn er ".$mobile." og password".$mobile."  
Spørgsmål? Email: support@landncall.com eller 25998891.
Venlig hilsen Zerocall";


                   //echo "SIP is being Assigned 1";
              $sc = new Criteria();
              $sc->add(SipPeer::ASSIGNED, false);
              $sip = SipPeer::doSelectOne($sc);

              //echo "SIP is being Assigned 2";

              $sip->setCustomerId($customer->getId());
              $sip->setAssigned(true);
              $sip->save();

              $cc = new Criteria();
              $cc->add(EnableCountryPeer::ID, $customer->getCountryId());
              $country = EnableCountryPeer::doSelectOne($cc);

              $mobile = $country->getCallingCode().$customer->getMobileNumber();

              if(strlen($mobile)==11){
//                 echo 'mobile # = 11' ;
                 $mobile = '00'.$mobile;
             }

              $IMdata = array(
                      'type' => 'add',
                      'secret'=>'rnRQSRD0',
                      'username'=>$mobile,
                      'password'=>$customer->getPlainText(),
                      'name' =>$customer->getFirstName(),
                      'email'=>$customer->getEmail()
                );
               $queryString = http_build_query($IMdata,'', '&');
               $res2 = file_get_contents('http://im.zerocall.com:9090/plugins/userService/userservice?'.$queryString);

         $agentid=$agent->getId();
         $productid=$product->getId();
         $transactionid=$transaction->getId();
         $massage=commissionLib::registrationCommission($agentid,$productid,$transactionid);
           if(isset($massage)&& $massage=="balance_error" ){

                   $sms_text = "your balance is low";
         echo "error, your balance is low";

  $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                  'DA'=>'45'.$mobile,
                  'SA' =>'Zerocall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

  

        $queryString = http_build_query($data,'', '&');
	$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
        echo $res;

        die;
               }
           
             Fonet::registerFonet($customer);
             Fonet::recharge($customer, $order->getExtraRefill());

             
             $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0,1);     // bcdef
             if($getFirstnumberofMobile==0){
                 $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                 $TelintaMobile =  '46'.$TelintaMobile ;
              }else{
                  $TelintaMobile = '46'.$this->customer->getMobileNumber();
              }
               $uniqueId = $this->customer->getUniqueid();
              $emailId = $this->customer->getEmail();
              $OpeningBalance = $order->getExtraRefill();
              $customerPassword = $this->customer->getPlainText();

              //Section For Telinta Add Cusomter
              $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?reseller=R_LandNcall&action=add&name='.$uniqueId.'&currency=SEK&opening_balance=0&credit_limit=0&enable_dialingrules=Yes&int_dial_pre=00&email='.$emailId.'&type=customer');

              // For Telinta Add Account
              $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$uniqueId.'&customer='.$uniqueId.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=0&billing_model=1&password='.$customerPassword);
              $telintaAddAccountA = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=a'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_CT&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$TelintaMobile.'&customer='.$TelintaMobile.'&opening_balance=-'.$OpeningBalance.'&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password='.$customerPassword);

              //This is for Recharge the Customer
              $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');
                           
             echo "zerocall paid";
         }
         else{
             $sms_text="Velkommen til ZerocallFree.
Dit nummer er aktiveret og du kan nu benyttet Zerocall Free til at ringe gratis til mere end 60 destinationer.
Ring til 25 99 88 91 og ringe videre til udlandet herefter (afslut med #). Brug for hjaelp?
Skriv til support@landncall.com.
VH - Zerocall";
             $sms_text.="Mange hilsner";
             $sms_text.= "Zerocall Support";


         $agentid=$agent->getId();
         $productid=$product->getId();
         $transactionid=$transaction->getId();
         $massage=commissionLib::registrationCommission($agentid,$productid,$transactionid);
           if(isset($massage)&& $massage=="balance_error" ){

                   $sms_text = "your balance is low";
         echo "error, your balance is low";

  $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                  'DA'=>'45'.$mobile,
                  'SA' =>'Zerocall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

        $queryString = http_build_query($data,'', '&');
	$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
        echo $res;

        die;
               }

                  Fonet::registerFonetZeroCallOut($customer);
//             $query_vars = array(
//			'Action'=>'CustUpdate',
//			'ParentCustomID'=>1393238,
//	  		'AniNo'=>'45'.$mobile,
//	  		'DdiNo'=>25969696,
//                        'RateTableName'=>'ZapnaB2Cfree',
//                        'Trace'=>1,
//			'CustomID'=>$customer->getFonetCustomerId()
//	  	);
//
//		$query_string = http_build_query($query_vars);
//		$resp = file_get_contents('http://fax.fonet.dk/cgi-bin/ZeroCallV2Control.pl?'.$query_string);

//             Fonet::registerFonet($customer);
//             Fonet::recharge($customer, $order->getExtraRefill());
             echo "zerocall free";
            // echo $resp;
         }

         emailLib::sendCustomerRegistrationViaAgentSMSEmail($this->customer,$order);
        echo 'customer registered';
 }else{

     $sms_text = "customer not registered, please retry. ";
     if(!$product){
         $sms_text = 'Dear Customer, The Product code that you provided is not valid. Please try again';
         echo 'Dear Customer, The Product code that you provided is not valid. Please try again';
     }else if (!$agent){
         $sms_text = "Dear Customer, The Code that you provided is not valid. Please try again";
         echo 'customer not found';
     }else if($invalidMobile){
         $sms_text = "Dear Customer, The telephone number that you have provided is not valid. Please try again";
         echo 'Invalid mobile number';
     }else if($alreadyRegistered){
         $sms_text = "Kaere Kunden
Du er allerede registeret til ZerocallFree.
Har du brug for hjaelp saa skriv til support@landncall.com.
VH - Zerocall";
         echo 'Number Already Registered';
     }
     
     
     
 }
        $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                  'DA'=>'45'.$mobile,
                  'SA' =>'Zerocall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );
    
        $queryString = http_build_query($data,'', '&');
	$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
        echo $res;

      $sms_log .= $sms_text;
      $sms_log .= "\n";
      $sms_log .= $res;
      $sms_log .= "\n";
      file_put_contents($sms_log_data_file, $sms_log, FILE_APPEND);
  }catch(Exception $e){
      $sms_log .= $e->getMessage();
      $sms_log .= "\n";
      file_put_contents($sms_log_data_file, $sms_log, FILE_APPEND);
  }


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


public function executeSmsRegisterationwcb(sfWebrequest $request){
    $sms_text="";
   $number = $request->getParameter('from');
    $mtnumber = $request->getParameter('from');
    $frmnumberTelinta = $request->getParameter('from');
	 $text = $request->getParameter('text');
      $caltype=substr($text,0,2);

     $numberlength=strlen($number);

      $endnumberlength=$numberlength-2;

          if ($caltype == "00") {



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
                    $secondnumber = substr($message, 2);

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
               

                $sms_text = "Hej,
                        Ditt telefonnummer är inte registrerat hos LandNCall. Vänligen registrera telefonen eller kontakta support på support@landncall.com
                        MVH
                        LandNCall";

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
                echo $sms_text;
                $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?' . $queryString);
                //
            }
             return sfView::NONE;
        }
    if($caltype=="hc"){


        $cus=0;
	$mobile = "";
        $mnumber= $number;
	$number =substr($number,2,$endnumberlength);
	$message =substr($text,3,6);
	$uniqueId  = $text;
        $uniqueId  =substr($uniqueId,3,6);

  if(isset($number) && $number!=""){



             
                $number="0".$number;
		$mnc = new Criteria();
		$mnc->add(CustomerPeer::MOBILE_NUMBER, $number);
                $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
		$cus = CustomerPeer::doSelectOne($mnc);
                 
		$mnc = new Criteria();
		$mnc->add(CustomerPeer::MOBILE_NUMBER, $number);
                $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
		$cusCount = CustomerPeer::doCount($mnc);


		$callbackq = new Criteria();
		$callbackq->add(CallbackLogPeer::UNIQUEID, $uniqueId);
		//$callbackq = CallbackLogPeer::doSelectOne($callbackq);
                $callbackq = CallbackLogPeer::doCount($callbackq);
  }
//if(isset($callbackq) && $callbackq>0)
 if($cusCount>=1 && isset($callbackq) && $callbackq>0){
  	  $customerid=$cus->getId();
	  $mbno =$cus->getMobileNumber();
	 // $callCode = substr($number,0,2);
	 if(isset($customerid)  && $customerid!=""){
	 		//echo $uniqueId;
		   //$cus->setUniqueid($uniqueId);
		  // $cus->save();

		   //------save the callback data
		   	$callbacklog = new CallbackLog();
			$callbacklog->setMobileNumber($mnumber);
			$callbacklog->setuniqueId($uniqueId);
			//$callbacklog->setcallingCode(45);
			// $calllog->setMac($mac);
			$callbacklog->save();
   			echo 'Success';
                        $mtnumber;

                       $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name='.$uniqueId.'&type=customer');
                        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
                        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);
                        
                        $getvoipInfo = new Criteria();
                        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customerids);
                        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                        if(isset($getvoipInfos)){
                            $voipnumbers = $getvoipInfos->getNumber() ;
                            $voipnumbers =  substr($voipnumbers,2);
                        }else{
                        }
                         //$emailId = $this->customer->getEmail();
                       // echo 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$frmnumberTelinta.'&customer='.$uniqueId.'&opening_balance=-'.$telintaGetBalance.'&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd';
                      // $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$frmnumberTelinta.'&customer='.$uniqueId.'&opening_balance=-'.$telintaGetBalance.'&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number='.$TelintaMobile.'&billing_model=1&password=asdf1asd');
                      // $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=update&name='.$voipnumbers.'&active=Y&follow_me_number='.$frmnumberTelinta.'&type=account');
                       $deleteAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name='.$voipnumbers.'&type=account');

                       
                        $find = '';
                        $string = $deleteAccount;
                        $find = 'ERROR';
                        if(strpos($string, $find )){
                            $message_body = "Error ON Delete Account within environment <br> VOIP Number :$voipnumbers <br / >";
                            //Send Email to User/Agent/Support --- when Customer Refilll --- 01/15/11
                            emailLib::sendErrorTelinta($this->customer,$message_body);
                        }else{
                        }

                      $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$voipnumbers.'&customer='.$uniqueId.'&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number='.$frmnumberTelinta.'&billing_model=1&password=asdf1asd');
                      $find = '';
                      
                      $string = $telintaAddAccount;
                      $find = 'ERROR';
                        if(strpos($string, $find )){
                             $message_body = "Error ON Active a HC within environment <br> VOIP Number :$voipnumbers <br / >Follow Me Number: $frmnumberTelinta";
                             //Send Email to User/Agent/Support --- when Customer Refilll --- 01/15/11
                            emailLib::sendErrorTelinta($this->customer,$message_body);
                        }else{
                        }

                        
                    
        $sms_text="Hej,
                    Ditt Smartsim är nu aktiverat och du kan börja spara pengar på din utlandstelefoni.
                    Med vänlig hälsning
                    LandNCall";
        
        
                            
       $sm = new Criteria();
                    $sm->add(SmsTextPeer::ID, 1);
                    $smstext = SmsTextPeer::doSelectOne($sm);
                    $sms_text = $smstext->getMessageText();
                     

//$mtnumber=923006826451;
        $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                'DA'=>$mtnumber,
                 'SA' =>'LandNcall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

     echo   $queryString = http_build_query($data,'', '&');
     
		$queryString=smsCharacter::smsCharacterReplacement($queryString);
      echo $sms_text;
      echo   $queryString;
    $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
       echo $res;

	 }
 }



      if($cusCount<1){
 echo 'Success else contdiont';
   echo $mtnumber;
   
                         
     
                     
        $sms_text="Hej,
                    Ditt telefonnummer är inte registrerat hos LandNCall. Vänligen registrera telefonen eller kontakta support på support@landncall.com
                    MVH
                    LandNCall";
  $sm = new Criteria();
                    $sm->add(SmsTextPeer::ID, 2);
                    $smstext = SmsTextPeer::doSelectOne($sm);
                    $sms_text = $smstext->getMessageText();
        $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                'DA'=>$mtnumber,
                 'SA' =>'LandNcall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

       $queryString = http_build_query($data,'', '&');
		$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
       $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
       echo $res;

      }

      if($callbackq<1){
       // echo '_Error';
      }


      return sfView::NONE;


    }

    if($caltype=="IC"){
	$mobile = "";
      
        $number=$number;
        $mnumber= $number;

	$number =substr($number,2,$endnumberlength);
	$message =substr($text,3,6);
	$uniqueId  = $text;
      echo   $uniqueId  =substr($uniqueId,3,6);
	$callbackq = new Criteria();
	$callbackq->add(CallbackLogPeer::UNIQUEID, $uniqueId);
	$callbackq = CallbackLogPeer::doCount($callbackq);

 	if(isset($callbackq) && $callbackq>0){

         
		 //$customerid=$cus->getId();
		  //$mbno =$cus->getMobileNumber();
		//  $callCode = substr($Mobilenumber,0,2);
	 		//echo $uniqueId;
		   //$cus->setUniqueid($uniqueId);
		   //$cus->save();
		   //------save the callback data
		   //echo $mobnum = $Mobilenumber;
		   	$callbacklog = new CallbackLog();
			$callbacklog->setMobileNumber($mnumber);
			$callbacklog->setuniqueId($uniqueId);
			$callbacklog->setcallingCode(45);
			$callbacklog->save();
   			echo 'Success';

                        $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$frmnumberTelinta.'&customer='.$uniqueId.'&opening_balance=0&credit_limit=&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd');
                        $find = '';
                        $string = $telintaAddAccountCB;
                        $find = 'ERROR';
                        if(strpos($string, $find )){
                            $message_body = "On IC Registration Page Call Back Account Add Error within environment <br> Name :cb$frmnumberTelinta <br / >Unique Id: $uniqueId";
                             //Send Email to User/Agent/Support --- when Customer Refilll --- 01/15/11
                            emailLib::sendErrorTelinta($this->customer,$message_body);
                        }else{
                        }

//                        $mnc = new Criteria();
//                        $mnc->add(CustomerPeer::MOBILE_NUMBER, $number);
//                        $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
//                        $cus = CustomerPeer::doSelectOne($mnc);
//                    echo     $uniqueId = $cus->getUniqueid();
                        //This is for Retrieve balance From Telinta
                        $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name='.$uniqueId.'&type=customer');
                        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
                        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);

                        $mnc = new Criteria();
                        $mnc->add(CustomerPeer::UNIQUEID, $uniqueId);
                        $mnc->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
                        $cus = CustomerPeer::doSelectOne($mnc);
                        $customerids = $cus->getId();
                
                        $getvoipInfo = new Criteria();
                        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customerids);
                        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                        if(isset($getvoipInfos)){
                            $voipnumbers = $getvoipInfos->getNumber() ;
                           echo  $voipnumbers =  substr($voipnumbers,2);

                           $deleteAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name='.$voipnumbers.'&type=account');

                            $find = '';
                            $string = $deleteAccount;
                            $find = 'ERROR';
                            if(strpos($string, $find )){
                                $message_body = "Error ON Delete Account within environment <br> VOIP Number :$voipnumbers <br / >";
                                //Send Email to User/Agent/Support --- when Customer Refilll --- 01/15/11
                                emailLib::sendErrorTelinta($this->customer,$message_body);
                            }else{
                            }
                            $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$voipnumbers.'&customer='.$uniqueId.'&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number='.$frmnumberTelinta.'&billing_model=1&password=asdf1asd');
                            $find = '';
                            $string = $telintaAddAccount;
                            $find = 'ERROR';
                            if(strpos($string, $find )){
                                 $message_body = "Error ON Active a IC within environment <br> VOIP Number :$voipnumbers <br / >Follow Me Number: $frmnumberTelinta";
                                 //Send Email to User/Agent/Support --- when Customer Refilll --- 01/15/11
                                emailLib::sendErrorTelinta($this->customer,$message_body);
                            }else{
                            }
                        }else{
                        }
                         //$emailId = $this->customer->getEmail();
                       // echo 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=cb'.$frmnumberTelinta.'&customer='.$uniqueId.'&opening_balance=-'.$telintaGetBalance.'&product=YYYLandncall_callback&outgoing_default_r_r=2034&billing_model=1&password=asdf1asd';
                      // $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name='.$frmnumberTelinta.'&customer='.$uniqueId.'&opening_balance=-'.$telintaGetBalance.'&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number='.$TelintaMobile.'&billing_model=1&password=asdf1asd');
                      //echo  $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=update&name='.$voipnumbers.'&active=Y&follow_me_number='.$frmnumberTelinta.'&type=account');

                       
                  
    
                    $sms_text="Hej,
Ditt Smartsim är nu aktiverat och du kan börja spara pengar på din utlandstelefoni.
Med vänlig hälsning
LandNCall";
                    $sm = new Criteria();
                    $sm->add(SmsTextPeer::ID, 3);
                    $smstext = SmsTextPeer::doSelectOne($sm);
                    $sms_text = $smstext->getMessageText();
        $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                'DA'=>$mtnumber,
                 'SA' =>'LandNcall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

       $queryString = http_build_query($data,'', '&');
		$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
       $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
       echo $res;

 	}


      if($callbackq){
       //echo 'Error';
      }


      return sfView::NONE;



    }


if(($caltype!="IC") && ($caltype!="hc") && ($caltype!="00")){


  $sms_log_data_file = sfConfig::get('sf_data_dir').'/imsi_log.txt';
  $sms_log = "sms registration";
  $sms_log .= "number: ".$request->getParameter('from');
  $sms_log .= " message:".$request->getParameter('text');
  $sms_log .= "\n";
  file_put_contents($sms_log_data_file, $sms_log, FILE_APPEND);



  $mobile = "";
   $number = $request->getParameter('from');
   $message = $request->getParameter('text');

 
  if(isset($number) && $number!=""){
      $mnc = new Criteria();
	 
      $mnc->add(CallbackLogPeer::MOBILE_NUMBER, $number);
      $cus = CallbackLogPeer::doSelectOne($mnc);
 
  }
 if(isset($cus) && $cus!=""){
  	 $customerid=$cus->getId();
	 if(isset($customerid)  && $customerid!="" ){     
	           $cus->setImsi(substr($message,0,15));
		   $cus->save();

                   $getvoipInfo = new Criteria();
                    $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customerid);
                    $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                    if(isset($getvoipInfos)){
                       $voipnumbers = $getvoipInfos->getNumber() ;
                       $voipnumbers =  substr($voipnumbers,2);
                    }else{
                    }
                   // echo  $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=update&name='.$voipnumbers.'&active=Y&follow_me_number='.$frmnumberTelinta.'&type=account');
		   echo 'Ok';
	 }
 }

      if(!$cus){

        $sms_text="Hej,
                    Ditt telefonnummer är inte registrerat hos LandNCall. Vänligen registrera telefonen eller kontakta support på support@landncall.com
                    MVH
                    LandNCall";

        $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                  'DA'=>$number,
                  'SA' =>'LandNcall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

        $queryString = http_build_query($data,'', '&');
		$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);
        //
      }else{

          $sms_text="Bästa kund, Din IMSI registrerat successusfully";

        $data = array(
                  'S' => 'H',
                  'UN'=>'zapna1',
                  'P'=>'Zapna2010',
                  'DA'=>$number,
                  'SA' =>'LandNcall',
                  'M'=>$sms_text,
                  'ST'=>'5'
            );

        $queryString = http_build_query($data,'', '&');
		$queryString=smsCharacter::smsCharacterReplacement($queryString);
        echo $sms_text;
        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString);

      }

      return sfView::NONE;
  }
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

                //echo "UniqueID:";
                $uniqueId = $customer->getUniqueid();
                if ((int) $uniqueId > 200000) {
                    $Tes = ForumTel::getBalanceForumtel($customer->getId());

                    $customer_balance = $Tes;
                } else {
                    //echo "This is for Retrieve balance From Telinta"."<br/>";
                    $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=' . $uniqueId . '&type=customer');
                    sleep(0.25);
                    if(!$telintaGetBalance){
                        //emailLib::sendErrorInTelinta("Error in Balance Fetching", "We have faced an issue in autorefill on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=" . $uniqueId . "&type=customer. <br/> Please Investigate.");
                        continue;
                    }
                    parse_str($telintaGetBalance);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in Balance Status", "We have faced an issue in autorefill on telinta. after fetching data from the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=" . $uniqueId . "&type=customer. we are unable to find the status in the string <br/> Please Investigate.");
                        continue;
                    }
                    $customer_balance = $Balance*(-1);
                }
                echo "<br/>";
                // $customer_balance = Fonet::getBalance($customer);
                //if customer balance is less than 10
                if ($customer_balance != null && (float)$customer_balance <= (float)$customer->getAutoRefillMinBalance()) {


                    echo $customer_balance;
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
                    $form->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
                    $form->set_referrer("http://landncall.zerocall.com");
                    $post_data = array(
                        'merchant' => '90049676',
                        'amount' => $total,
                        'currency' => '752',
                        'orderid' => $order_id,
                        'textreply' => true,
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
//echo $html_data;
//echo "<br/>";
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
            
           $urlval=0;
            $urlval="autorefil-".$request->getParameter('transact');
    
         $email2 = new DibsCall();
         $email2->setCallurl($urlval);

            $email2->save();
           $urlval=$request->getParameter('transact');
            if(isset($urlval) && $urlval>0){
         $order_id = $request->getParameter("orderid");

	  	$this->forward404Unless($order_id || $order_amount);

		$order = CustomerOrderPeer::retrieveByPK($order_id);

	  	$order_amount = ((double)$request->getParameter('amount'))/100 ;

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
                        if((int)$uniqueId>200000){
                            $cuserid = $this->customer->getId();
                          $amt=$OpeningBalance;
                  $amt=CurrencyConverter::convertSekToUsd($amt);
                $Test=ForumTel::rechargeForumtel($cuserid,$amt);
                        }else{


                        $MinuesOpeningBalance = $OpeningBalance*3;
                        $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=customer');

                        }
                        //This is for Recharge the Account
                          //this condition for if follow me is Active
                            $getvoipInfo = new Criteria();
                            $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $this->customer->getMobileNumber());
                            $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                            if(isset($getvoipInfos)){
                                $voipnumbers = $getvoipInfos->getNumber() ;
                                $voip_customer = $getvoipInfos->getCustomerId() ;
                              //  $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$voipnumbers.'&amount='.$OpeningBalance.'&type=account');
                            }else{
                              //  $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$uniqueId.'&amount='.$OpeningBalance.'&type=account');
                            }                            
                       // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name=a'.$TelintaMobile.'&amount='.$OpeningBalance.'&type=account');
                       // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name=cb'.$TelintaMobile.'&amount='.$OpeningBalance.'&type=account');

                        $MinuesOpeningBalance = $OpeningBalance*3;
                        //type=<account_customer>&action=manual_charge&name=<name>&amount=<amount>
                        //This is for Recharge the Customer
                       // $telintaAddAccountCB = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=customer&action=manual_charge&name='.$uniqueId.'&amount='.$MinuesOpeningBalance);



//echo 'NOOO';
// Update cloud 9
        //c9Wrapper::equateBalance($this->customer);

//echo 'Comeing';
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
  	$message_body = $this->getPartial('pScripts/order_receipt', array(
  						'customer'=>$this->customer,
  						'order'=>$order,
  						'transaction'=>$transaction,
  						'vat'=>$vat,
  						'wrap'=>false
  					));



            emailLib::sendCustomerRefillEmail($this->customer,$order,$transaction); 
          
          
            }    
  }    
        public function executeUsageAlert(sfWebRequest $request)
  {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request,$this);
        //-----------------------
        $langSym = $this->getUser()->getCulture();

         $enableCountry = new Criteria();
        $enableCountry->add(EnableCountryPeer::LANGUAGE_SYMBOL, $langSym);
        $country_id = EnableCountryPeer::doSelectOne($enableCountry);//->getId();
        if($country_id){
            $CallCode= $country_id->getCallingCode();
            $countryId = $country_id->getId();
        }else{
            $CallCode = '46';
            $countryId = "2";
        }
        $this->customer_balance = -1;
       // echo $CallCode ;

        //This Code For send the SMS Alert....
        $usagealerts = new Criteria();
        $usagealerts->add(UsageAlertPeer::SMS_ACTIVE, 1);
        //$usagealerts->addAnd(UsageAlertPeer::ALERT_AMOUNT, 0, Criteria::NOT_EQUAL);
        $usagealerts->addAnd(UsageAlertPeer::COUNTRY, $countryId);
        $usagealert = UsageAlertPeer::doSelect($usagealerts);
        foreach($usagealert as $alerts){

            $c=new Criteria();
            $c->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
            $c->addAnd(CustomerPeer::COUNTRY_ID,$countryId);
            $customers=CustomerPeer::doSelect($c);
            $customer_balance = "";
            foreach($customers as $cus){
                //echo $alerts->getId().'<br>';
                $msgsentstatus = new Criteria();
                $msgsentstatus->add(UsageAlertSentPeer::USAGE_ALERT_ID,$alerts->getId());
                $msgsentstatus->addAnd(UsageAlertSentPeer::CUSTOMERID, $cus->getId());
                $msgsentstatus->addAnd(UsageAlertSentPeer::ALERT_AMOUNT, $alerts->getAlertAmount());
                $msgsentstatus->addAnd(UsageAlertSentPeer::MESSAGETYPE, "sms");
                $msgsentrecord = UsageAlertSentPeer::doSelectOne($msgsentstatus);
//                if($msgsentrecord){
//                   // echo "Message Aleady Sent......".$msgsentrecord->getAlertAmount().'__'.$msgsentrecord->getCustomerid().'__'.$msgsentrecord->getUsageAlertId().'<br>';
//                    echo "Message Aleady Sent......<br>";
//                }else


                    $senderName = new Criteria();
                    $senderName->add(UsageAlertSenderPeer::ID,$alerts->getSenderName());
                    $usageAlertSenderName = UsageAlertSenderPeer::doSelectOne($senderName);
                    $AlertSenderName = $usageAlertSenderName->getName();
                    //echo $cus->getId().'<br>';
                    $this->customer = CustomerPeer::retrieveByPK($cus->getId());
                  


                       $uniqueId =$this->customer->getUniqueid();
 if(isset($uniqueId) && $uniqueId!=""){
                       $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name='.$uniqueId.'&type=customer');
        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);
         $customer_balance = $telintaGetBalance;



                    }else{

                        continue;
                        
                    }

                    if($customer_balance<$alerts->getAlertAmount()){
                         echo "New Message Sent......<br>";
                         //echo $cus->getMobileNumber();
                         echo 'CustomerBlance:'.$customer_balance.'<br>';
                         //echo $alerts->getSmsAlertMessage();
                         //echo '<br>';
                            //--------------------------This Is sms Send Area---------------------------------------------------
                         $mobnumber=$cus->getMobileNumber();
                         $mobnumber=substr($mobnumber,1);
                            echo $customerMobileNumber = $CallCode.$mobnumber;
                            $delievry="";
                            //*/3 *    * * *   root     /usr/bin/curl http://stage.zerocall.com/b2c/pScripts/usageAlert
                            $number = $customerMobileNumber;
                           // $number = "923214745120";
                            $sms_text = $alerts->getSmsAlertMessage();
                            $data = array(
                              'S' => 'H',
                              'UN'=>'zapna1',
                              'P'=>'Zapna2010',
                              'DA'=>$number,
                              'SA' => $AlertSenderName,
                              'M'=>$sms_text,
                              'ST'=>'5'
                            );
                            $queryString = http_build_query($data,'', '&');
                            //   die;
                            sleep(0.5);

                            $queryString=smsCharacter::smsCharacterReplacement($queryString);

                            if($this->response_text = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString)){
                                echo $this->response_text;
                            }
                            //--------------------------------------------------------------------------------------

//                            //Set the Sms text
//                            $msgSent = new UsageAlertSent();
//                            $msgSent->setUsageAlertId($alerts->getId());
//                            $msgSent->setCustomerid($cus->getId());
//                            $msgSent->setMessagetype("sms");
//                            $msgSent->setAlertAmount($alerts->getAlertAmount());
//                            $msgSent->save();

                    }
                   // echo $alerts->getAlertAmount();
                    if($alerts->getAlertAmount()==0){
                        if($customer_balance==0){
                            //--------------------------This Is sms Send Area---------------------------------------------------
                            echo $customerMobileNumber = $CallCode.$cus->getMobileNumber();
                            $delievry="";
                            //*/3 *    * * *   root     /usr/bin/curl http://stage.zerocall.com/b2c/pScripts/usageAlert
                            $number = $customerMobileNumber;
                            //$number = "923214745120";
                            $sms_text = $alerts->getSmsAlertMessage();
                            $data = array(
                              'S' => 'H',
                              'UN'=>'zapna1',
                              'P'=>'Zapna2010',
                              'DA'=>$number,
                              'SA' => $AlertSenderName,
                              'M'=>$sms_text,
                              'ST'=>'5'
                            );
                            $queryString = http_build_query($data,'', '&');
                            //   die;
                            sleep(0.5);

                            $queryString=smsCharacter::smsCharacterReplacement($queryString);

                            if($this->response_text = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?'.$queryString)){
                                echo $this->response_text;
                            }
                            //--------------------------------------------------------------------------------------

//                            //Set the Sms text
//                            $msgSent = new UsageAlertSent();
//                            $msgSent->setUsageAlertId($alerts->getId());
//                            $msgSent->setCustomerid($cus->getId());
//                            $msgSent->setMessagetype("sms");
//                            $msgSent->setAlertAmount($alerts->getAlertAmount());
//                            $msgSent->save();
                        }
                    }
              //  }
            }
        }

         //This Code For send the Email Alert....
        $usagealerts = new Criteria();
        $usagealerts->add(UsageAlertPeer::EMAIL_ACTIVE, 1);
        //$usagealerts->addAnd(UsageAlertPeer::ALERT_AMOUNT, 0, Criteria::NOT_EQUAL);
        $usagealerts->addAnd(UsageAlertPeer::COUNTRY, $countryId);
        $usagealert = UsageAlertPeer::doSelect($usagealerts);
        foreach($usagealert as $alerts){

            $c=new Criteria();
            $c->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
            $c->addAnd(CustomerPeer::COUNTRY_ID,$countryId);
            $customers=CustomerPeer::doSelect($c);
            $customer_balance = "";
            foreach($customers as $cus){
                //echo $alerts->getId().'<br>';
                $msgsentstatus = new Criteria();
                $msgsentstatus->add(UsageAlertSentPeer::USAGE_ALERT_ID,$alerts->getId());
                $msgsentstatus->addAnd(UsageAlertSentPeer::CUSTOMERID, $cus->getId());
                $msgsentstatus->addAnd(UsageAlertSentPeer::ALERT_AMOUNT, $alerts->getAlertAmount());
                $msgsentstatus->addAnd(UsageAlertSentPeer::MESSAGETYPE, "email");
                $msgsentrecord = UsageAlertSentPeer::doSelectOne($msgsentstatus);
//                if($msgsentrecord){
//                   // echo "Message Aleady Sent......".$msgsentrecord->getAlertAmount().'__'.$msgsentrecord->getCustomerid().'__'.$msgsentrecord->getUsageAlertId().'<br>';
//                    echo "Email Aleady Sent......<br>";
//                }else
                    {

                    $senderName = new Criteria();
                    $senderName->add(UsageAlertSenderPeer::ID,$alerts->getSenderName());
                    $usageAlertSenderName = UsageAlertSenderPeer::doSelectOne($senderName);
                    $AlertSenderName = $usageAlertSenderName->getName();

                    //echo $cus->getId().'<br>';
                    $this->customer = CustomerPeer::retrieveByPK($cus->getId());
                         $uniqueId =$this->customer->getUniqueid();
                if(isset($uniqueId) && $uniqueId!=""){

                     //  $customer_balance = (double)Fonet::getBalance($this->customer);


                  

                       $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name='.$uniqueId.'&type=customer');
        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);
         $customer_balance = $telintaGetBalance;






                    }else{
                        continue;

                    }

                    if($customer_balance<$alerts->getAlertAmount()){
                         echo "New Email Sent......<br>";
                         //echo $cus->getMobileNumber();
                         echo 'CustomerBlance:'.$customer_balance.'<br>';
                         //echo $alerts->getSmsAlertMessage();
                         //echo '<br>';
                            //--------------------------This Is Email Send Area------------------------------------
                                $customerMobileNumber = $CallCode.$cus->getMobileNumber();

                                $subject         = 'Usage Alert' ;
                                $message_body     = $alerts->getEmailAlertMessage()." <br />\r\n ".$AlertSenderName;

                                $emailAlertCus=new Criteria();
                                $emailAlertCus->add(CustomerPeer::ID,$cus->getId());
                                $emailAlertCustomer = CustomerPeer::doSelectOne($emailAlertCus);

                                //Send Email to Customer For Balance --- 06/06/11
                                emailLib::sendCustomerBalanceEmail($emailAlertCustomer,$message_body);

                            //--------------------------------------------------------------------------------------

//                            //Set the Sms text
//                            $msgSent = new UsageAlertSent();
//                            $msgSent->setUsageAlertId($alerts->getId());
//                            $msgSent->setCustomerid($cus->getId());
//                            $msgSent->setMessagetype("email");
//                            $msgSent->setAlertAmount($alerts->getAlertAmount());
//                            $msgSent->save();

                    }
                   // echo $alerts->getAlertAmount();
                    if($alerts->getAlertAmount()==0){
                        if($customer_balance==0){
                            //--------------------------This Is sms Send Area--------------------------------------
                                $customerMobileNumber = $CallCode.$cus->getMobileNumber();
                                $subject         = 'Usage Alert' ;
                                $message_body     = $alerts->getEmailAlertMessage()." <br /> \r\n ".$AlertSenderName;

                                $emailAlertCus=new Criteria();
                                $emailAlertCus->add(CustomerPeer::ID,$cus->getId());
                                $emailAlertCustomer=CustomerPeer::doSelectOne($emailAlertCus);

                                //Send Email to Customer For Balance --- 06/06/11
                                emailLib::sendCustomerBalanceEmail($emailAlertCustomer,$message_body);
                            //--------------------------------------------------------------------------------------

//                            //Set the Sms text
//                            $msgSent = new UsageAlertSent();
//                            $msgSent->setUsageAlertId($alerts->getId());
//                            $msgSent->setCustomerid($cus->getId());
//                            $msgSent->setMessagetype("email");
//                            $msgSent->setAlertAmount($alerts->getAlertAmount());
//                            $msgSent->save();
                        }
                    }
                }
            }
        }

        die();

  }


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



}
