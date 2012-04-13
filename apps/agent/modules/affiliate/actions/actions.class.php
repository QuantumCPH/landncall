<?php
require_once(sfConfig::get('sf_lib_dir').'/Browser.php');
require_once(sfConfig::get('sf_lib_dir').'/emailLib.php');
require_once(sfConfig::get('sf_lib_dir').'/changeLanguageCulture.php');
/**
 * affiliate actions.
  * @package    zapnacrm
 * @subpackage affiliate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.2 2010-08-05 20:37:52 orehman Exp $
 */
class affiliateActions extends sfActions
{

    //private $targetURL = "http://localhost/landncall/web/agent_dev.php/affiliate/";
    //private $targetURL = "http://stagelc.zerocall.com/agent.php/affiliate/";
    //private $targetPScriptURL = "http://landncall.zerocall.com/b2c.php/pScripts/";

    private function getTargetUrl() {
        return sfConfig::get('app_agent_url');
    }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
	  $this->updateNews = NewupdatePeer::doSelect(new Criteria());
    $this->forward('default', 'module');
  }

   public function executeConversionform(sfWebRequest $request)
  {
	//call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
      	changeLanguageCulture::languageCulture($request,$this);
  }



  public function executeReceipts(sfWebRequest $request)
  {
      
                //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
                changeLanguageCulture::languageCulture($request,$this);
                $this->updateNews = NewupdatePeer::doSelect(new Criteria());
                $this->forward404Unless($this->getUser()->isAuthenticated());

		$c = new Criteria();
		$agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession');
		$c->add(AgentCompanyPeer::ID, $agent_company_id);
                $agent = AgentCompanyPeer::doSelectOne($c);

		$this->forward404Unless(AgentCompanyPeer::doSelectOne($c));

                $transactions = array();
                  $registrations = array();
                $i=1;

		//echo $agent_company_id;
		
                       $c = new Criteria();
                       $c->add(CustomerPeer::REFERRER_ID,$agent_company_id);
                       $c->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
                       $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
                       $customers=CustomerPeer::doSelect($c);



                       foreach($customers as $customer){
                        //echo $customer->getId().'<br>';
                           $tc = new Criteria();
                           $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
                           $tc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
                           $tc->add(TransactionPeer::TRANSACTION_STATUS_ID,3);
                           if(TransactionPeer::doSelectOne($tc)){
                            $registrations[$i]=TransactionPeer::doSelectOne($tc);
                           }
                          // echo $customer->getId().'__'.$agent_company_id.'<br>';
                           $i=$i+1;
                           
//                           echo $customer->getMobileNumber();
//                           echo '<br/>';
                       }

                      //echo count($registrations);
		$ar = new Criteria();
		$ar->add(TransactionPeer::AGENT_COMPANY_ID,$agent_company_id);
                $ar->add(TransactionPeer::DESCRIPTION,'Registrering inkl. taletid', Criteria::NOT_EQUAL);
                $ar->addAnd(TransactionPeer::DESCRIPTION,'Avgift för förändring nummer (' . $agent->getName() . ')', Criteria::NOT_EQUAL);
		$ar->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
                $ar->addAnd(TransactionPeer::TRANSACTION_STATUS_ID,3);
                $refills = TransactionPeer::doSelect($ar);


                $cn = new Criteria();
                $cn->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
                $cn->addAnd(TransactionPeer::DESCRIPTION, 'Avgift för förändring nummer (' . $agent->getName() . ')', Criteria::EQUAL);
                $cn->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
                $cn->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $numberchange = TransactionPeer::doSelect($cn);

//                foreach ($refills as $refill){
//                    $transactions[$i]=$refill;
//                    $i=$i+1;
//                }

		$this->registrations = $registrations;
                $this->refills = $refills;
                $this->numberchanges = $numberchange;
                $this->counter = $i - 1;
		
  }

public function executePrintReceipt(sfWebRequest $request)
  {
  	//is authenticated
	$this->forward404Unless($this->getUser()->isAuthenticated());
	$this->updateNews = NewupdatePeer::doSelect(new Criteria());

  	//check to see if transaction id is there
  	
  	$transaction_id = $request->getParameter('tid');
  	
  	$this->forward404Unless($transaction_id);
  	
  	//is this receipt really belongs to authenticated user
  	
  	$transaction = TransactionPeer::retrieveByPK($transaction_id);

	$c = new Criteria();
	$c->add(CustomerPeer::ID, $transaction->getCustomerId());
	$this->customer = CustomerPeer::doSelectOne($c);
	
  	
  	$this->forward404Unless($transaction->getCustomerId()==$this->customer->getId(), 'Not allowed');
  	
  	//set customer order
  	$customer_order = CustomerOrderPeer::retrieveByPK($transaction->getOrderId());
  	
  	if ($customer_order)
  	{
  		$vat = $customer_order->getIsFirstOrder()?
  				($customer_order->getProduct()->getPrice()*$customer_order->getQuantity() -
  				$customer_order->getProduct()->getInitialBalance()) * .20:
  				0;
  	}
  	else
  		die('Error retreiving');


  	  //This Query For Get Agent Informatiion again Issue#xxxx - 01/13/11
//        $c = new Criteria();
//        $c->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
//
//        $recepient_agent_email  = AgentCompanyPeer::doSelectOne($c)->getEmail();
//        $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();

        $this->renderPartial('affiliate/order_receipt', array(
                    'customer'=>$this->customer,
                    'order'=>CustomerOrderPeer::retrieveByPK($transaction->getOrderId()),
                    'transaction'=>$transaction,
                    'agent_name'=>'',
                    'vat'=>$vat,
            ));
  				
  	return sfView::NONE;
  		
  }

  public function executeNewsListing(sfWebRequest $request){
	$this->forward404Unless($this->getUser()->isAuthenticated());

	 	 
         $c=new Criteria();  
         $c->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
         $news=  NewupdatePeer::doSelect($c);
         $this->news = $news;


  }

  public function executeReport(sfWebRequest $request)
  {

      //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
      changeLanguageCulture::languageCulture($request,$this);
         
		$this->forward404Unless($this->getUser()->isAuthenticated());

		$nc = new Criteria();
		$nc->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
		$this->updateNews = NewupdatePeer::doSelect($nc);

		//verify if agent is already logged in
		$ca = new Criteria();
		$ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                $agent = AgentCompanyPeer::doSelectOne($ca);
		$this->forward404Unless($agent);

                $this->agent = $agent;

                

			//get All customer registrations from customer table
			try{			
                     
                       $c = new Criteria();
                       $c->add(CustomerPeer::REFERRER_ID,$agent_company_id);
                       $c->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
                       $c->add(CustomerPeer::REGISTRATION_TYPE_ID,4, Criteria::NOT_EQUAL);
                       $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
                       $customers=CustomerPeer::doSelect($c);

                    

                       $registration_sum=0.00;
                       $registration_commission=0.00;
                       $registrations = array();
                         $comregistrations = array();
                       $i=1;
                       foreach($customers as $customer){
                           $tc = new Criteria();
                           //echo $customer->getId();
                           $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
                           $tc->add(TransactionPeer::TRANSACTION_STATUS_ID,3);
                           $tc->add(TransactionPeer::DESCRIPTION, 'Registrering inkl. taletid');
                           if(TransactionPeer::doSelectOne($tc)){
                             $registrations[$i] = TransactionPeer::doSelectOne($tc);
                           }
                           $i=$i+1;
//
//                           echo $customer->getId();
//                           echo '<br/>';

                       }
//                       echo 'transactions';
//                       echo '<br/>';
                        //print_r($registrations);
                       if(count($registrations)>=1){

                       //echo count($registrations);
                       foreach($registrations as $registration){


//                       echo $registration->getCustomerId();
//                       echo '<br/>';
                       $registration_sum = $registration_sum + $registration->getAmount();

                       if($registration != NULL){
                           $coc = new Criteria();
                           $coc->add(CustomerOrderPeer::ID, $registration->getOrderId());
                           $customer_order = CustomerOrderPeer::doSelectOne($coc);

                           $registration_commission = $registration_commission + ($registration->getCommissionAmount());
                           
                       }

                       }
                        
                       }

                        
                       $this->registrations = $registrations;
                       $this->registration_revenue = $registration_sum;
                       $this->registration_commission = $registration_commission;

                      

                       $cc = new Criteria();
                       $cc->add(TransactionPeer::AGENT_COMPANY_ID,$agent_company_id);
                       $cc->add(TransactionPeer::DESCRIPTION,'LandNCall AB Refill' );
                       $cc->add(TransactionPeer::TRANSACTION_STATUS_ID,3);
                       $cc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
                       $refills=TransactionPeer::doSelect($cc);
       

                           $refill_sum=0.00;
                           $refill_com=0.00;
                           foreach($refills as $refill){
                            $refill_sum = $refill_sum+ $refill->getAmount();
                            $refill_com = $refill_com + $refill->getCommissionAmount();
                            
                           }
                           

                      
                       $this->refills = $refills;
                       $this->refill_revenue = $refill_sum;
                       $this->refill_com = $refill_com;

                      
			
			
                   
                   $efc = new Criteria();
                   $efc->add(TransactionPeer::AGENT_COMPANY_ID,$agent_company_id);
                   $efc->add(TransactionPeer::TRANSACTION_STATUS_ID,3);
                   $efc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
                   $ef=TransactionPeer::doSelect($efc);
              
                   $ef_sum=0.00;
                   $ef_com=0.00;
                   foreach($ef as $efo)
                   {
                       $description=substr($efo->getDescription(),0 ,26);
                       $stringfinds  = 'LandNCall AB Refill via agent';
                       if(strstr($efo->getDescription(),$stringfinds)){
                             //if($description== 'LandNCall AB Refill via agent ')
                              $ef_sum = $ef_sum+ $efo->getAmount();
                               $ef_com = $ef_com + $efo->getCommissionAmount();

                           
                       }
                   }

                   

                   $this->ef = $ef;
                   $this->ef_sum = $ef_sum;
                   $this->ef_com = $ef_com;

/////////// SMS Registrations

                       $cs = new Criteria();
                       $cs->add(CustomerPeer::REFERRER_ID,$agent_company_id);
                       $cs->add(CustomerPeer::CUSTOMER_STATUS_ID,3);
                       $cs->add(CustomerPeer::REGISTRATION_TYPE_ID,4);
                       $cs->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
                       $sms_customers=CustomerPeer::doSelect($cs);


                       $sms_registrations = array();
                       $sms_registration_earnings = 0.0;
                       $sms_commission_earnings = 0.0;
                       $i=1;
                       foreach($sms_customers as $sms_customer){
                           $tc = new Criteria();
                           $tc->add(TransactionPeer::CUSTOMER_ID, $sms_customer->getId());
                           $tc->add(TransactionPeer::TRANSACTION_STATUS_ID,3);
                           $tc->add(TransactionPeer::DESCRIPTION, 'Registrering inkl. taletid');
                           $sms_registrations[$i]= TransactionPeer::doSelectOne($tc);

                           if(count($sms_registrations)>=1){
                           $sms_registration_earnings = $sms_registration_earnings + $sms_registrations[$i]->getAmount();
                           $sms_commission_earnings = $sms_commission_earnings + $sms_registrations[$i]->getCommissionAmount();
                           
                           }
                           $i = $i +1;

                       }


                       $this->sms_registrations = $sms_registrations;
                       $this->sms_registration_earnings = $sms_registration_earnings;
                       $this->sms_commission_earnings = $sms_commission_earnings;


                       $nc = new Criteria();
            $nc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $nc->addAnd(TransactionPeer::DESCRIPTION, 'Avgift för förändring nummer (' . $agent->getName() . ')');
            $nc->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            $nc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $number_changes = TransactionPeer::doSelect($nc);

            $numberChange_earnings = 0.00;
            $numberChange_commission = 0.00;
            foreach ($number_changes as $number_change) {
                $numberChange_earnings = $numberChange_earnings + $number_change->getAmount();
                $numberChange_commission = $numberChange_commission + $number_change->getCommissionAmount();
            }



            $this->number_changes = $number_changes;
            $this->numberChange_earnings = $numberChange_earnings;
            $this->numberChange_commission = $numberChange_commission;
                       
////////// End SMS registrations
                  $this->sf_request = $request;
                        }catch(Exception $e){
                            echo $e->getMessage();
                        }

  }
  
public function executeRefill(sfWebRequest $request)
{

                //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 03/09/11 - Ahtsham
                changeLanguageCulture::languageCulture($request,$this);
                
		$this->browser = new Browser();
		$this->form = new AccountRefillAgent();

                $this->error_msg="";
                $this->error_mobile_number="";
                $validated = false;

                //get Agent
                $ca = new Criteria();
                $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                $agent = AgentCompanyPeer::doSelectOne($ca);

                //get Agent commission package
                $cpc = new Criteria();
                $cpc->add(AgentCommissionPackagePeer::ID,$agent->getAgentCommissionPackageId());
                $commission_package  = AgentCommissionPackagePeer::doSelectOne($cpc);

                if($request->getParameter('balance_error')){
                    $this->balance_error = $request->getParameter('balance_error');
                }else{
                    $this->balance_error = 0;
                }
                
		if ($request->isMethod('post'))
		{
                        $mobile_number = $request->getParameter('mobile_number');
                        $extra_refill = $request->getParameter('extra_refill');

//                        echo $mobile_number;
//                        echo '<br/>';
//                        echo $extra_refill;
//                        echo '<br/>';

                        
                        $is_recharged = true;

//                        if($agent->getIsPrepaid()==true){
//                            if ($extra_refill > $agent->getBalance()){
//                                $is_recharged = false;
//                                $balance_error = true;
//                            }
//                        }
          
                        $transaction = new Transaction();
                        $order = new CustomerOrder();	
                        $customer = NULL;
			$cc = new Criteria();
			$cc->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
                        $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
                        //$cc->add(CustomerPeer::FONET_CUSTOMER_ID, NULL, Criteria::ISNOTNULL);  // This Line disable becoz no need of fonet system in landncall -
			$customer = CustomerPeer::doSelectOne($cc);

                        //echo $customer->getId();
                       
                            if($customer and $mobile_number!=""){
                            $validated = true;
                            }
                            else{
                                    $validated = false;
                                    $is_recharged=false;                                    
                                    $this->error_mobile_number = 'invalid mobile number';
                                    return ;
                            }
                      
                        
//			echo 'validating form';
			if ($validated)
                        //if(true)
			{
                                
				

//                                echo "form valid";
//                                echo '<br />';
                                
                                //create order
				
				
				//get customer first product purchase
				$c = new Criteria();
				$c->add(CustomerProductPeer::CUSTOMER_ID, $customer->getId());
				$customer_product = CustomerProductPeer::doSelectOne($c)->getProduct();

//                                echo 'customer product :';
//                                echo $customer_product->getId();
//                                echo '<br />';

                                $order->setCustomerId($customer->getId());
				$order->setProductId($customer_product->getId());
				$order->setQuantity(1);
				$order->setExtraRefill($extra_refill);								
                                $order->setIsFirstOrder(false);
                                $order->setOrderStatusId(sfConfig::get('app_status_new'));
                                //$order->setAgentCommissionPackageId($agent->getAgentCommissionPackageId());

                                $order->save();

//				echo 'customer order :';
//                                echo $order->getId();
//                                echo '<br />';
                                //create transaction
				
				$transaction->setOrderId($order->getId());
				$transaction->setCustomerId($customer->getId());
				$transaction->setAmount($extra_refill);
				
				//get agent name
				$transaction->setDescription($this->getContext()->getI18N()->__('LandNCall AB Refill via agent ').'('.$agent->getName().')');
				$transaction->setAgentCompanyId($agent->getId());

//                                echo "assigning commission";
                                //assign commission to transaction;

   /////////////////////////////////////////////////////////////////////////////////////////////////
            //    $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
              $order->setAgentCommissionPackageId($agent->getAgentCommissionPackageId());
                ///////////////////////////commision calculation by agent product ///////////////////////////////////////

              $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession');
           
                $cp = new Criteria;
                    $cp->add(AgentProductPeer::AGENT_ID,$agent_company_id );
                     $cp->add(AgentProductPeer::PRODUCT_ID,$order->getProductId());
                    $agentproductcount = AgentProductPeer::doCount($cp);

                 
                    if($agentproductcount>0){
                      $p = new Criteria;
                      $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                      $p->add(AgentProductPeer::PRODUCT_ID,$order->getProductId());

                    $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                   $agentcomession=$agentproductcomesion->getExtraPaymentsShareEnable();
                    }

                      ////////   commission setting  through  agent commision//////////////////////

                    if($agentcomession){

                    if($agentproductcomesion->getIsExtraPaymentsShareValuePc()){
                      
                        $transaction->setCommissionAmount(($transaction->getAmount()/100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    }else{                      
                        $transaction->setCommissionAmount($agentproductcomesion->getExtraPaymentsShareValue());
                    }

                    }else{

                       
                                if($commission_package->getIsExtraPaymentsShareValuePc()){
                                            $transaction->setCommissionAmount(($transaction->getAmount()/100) * $commission_package->getExtraPaymentsShareValue());
                                }else{
                                            $transaction->setCommissionAmount($commission_package->getExtraPaymentsShareValue());
                                }
                    }

                                //calculated amount for agent commission
                                if($agent->getIsPrepaid()==true){
                                 if($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount()) ){
                                     $is_recharged = false;
                                     $balance_error=1;
                                }
                                
                                }
                                
                                

                                
//                                echo 'is_recharged '.$is_recharged;
//                                echo '<br/>';
				if ($is_recharged)
				{
//                                    echo 'isrecharged = true';
                                    $transaction->save();

                                    if($agent->getIsPrepaid()==true){
                                        $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));
                                       $agent->save();
                                        $remainingbalance=$agent->getBalance();
                                        $amount=$transaction->getAmount() - $transaction->getCommissionAmount();
                                        $amount=-$amount;
                                        $aph = new AgentPaymentHistory();
                                        $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                                        $aph->setCustomerId($transaction->getCustomerId());
                                        $aph->setExpeneseType(2);
                                        $aph->setAmount($amount);
                                        $aph->setRemainingBalance($remainingbalance);
                                        $aph->save();

                                    }

                                        if($customer->getFonetCustomerId()){
        //                                        echo 'got fonet id';
        //                                        echo '<br/>';
        //                                        As per omair Instruction now need to rechange the account on Fonet on landncall  we not not Fonet system on landncall
                                                //Fonet::recharge($customer, $transaction->getAmount());
                                        }

                                        //When the customer account is being refilled from the agent portal, Telinta should be update also
                                        //Refill The Customer 
                                        $uniqueId       =   $customer->getUniqueid();
                                        $OpeningBalance =    $transaction->getAmount();

                                        if($uniqueId!=''){
                                            Telienta::recharge($customer, $OpeningBalance);      
                                        }
                        
					//set status
					$order->setOrderStatusId(sfConfig::get('app_status_completed'));
					$transaction->setTransactionStatusId(sfConfig::get('app_status_completed'));



					
                                        $order->save();
					$transaction->save();
					$this->customer = $order->getCustomer();
                                        $this->getUser()->setCulture('sv');
                                        emailLib::sendRefillEmail($this->customer,$order);
                                        $this->getUser()->setCulture('en');
                                        $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('%1% account is successfully refilled with %2% SEK.', array("%1%"=>$customer->getMobileNumber(), "%2%"=>$transaction->getAmount() )));
//                                      echo 'rehcarged, redirecting';
					$this->redirect('affiliate/receipts');
					
				}
				else
				{
//                                        echo 'NOT rehcarged, redirecting';
                                        $this->balance_error = 1;
					$this->getUser()->setFlash('error', 'You do not have enough balance, please recharge');
                                        
				} //end else
				
			}else{
//                                        echo 'Form Invalid, redirecting';
                                        $this->balance_error = 1;
					//$this->getUser()->setFlash('message', 'Invalid mobile number');
                                        //$this->getUser()->setFlash('error_message', 'Customer Not Found.');
                                        $is_recharged=false;                                    
                                        $this->error_mobile_number = 'invalid mobile number';

                        }
                        }
	
}

  public  function executeRegistrationstep1(sfWebRequest $request)
  {

$mobile="";

if(isset($_REQUEST['mobileno']) && $_REQUEST['mobileno']!=""){
  
    $mobile=$_REQUEST['mobileno'];

                $c = new Criteria();
                $c->addJoin(CustomerProductPeer::CUSTOMER_ID, CustomerPeer::ID ,  Criteria::LEFT_JOIN);
                $c->add(CustomerProductPeer::PRODUCT_ID, 7);
  		$c->add(CustomerPeer::MOBILE_NUMBER, $mobile);
                $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
  		$customer = CustomerPeer::doSelectOne($c);

                if($customer){
                    $this->form = new CustomerForm(CustomerPeer::retrieveByPK($customer->getId()));
                }else{
                   $this->getUser()->setFlash('message', 'Customer is not a Zerocall Free customer');
                   $this->redirect('affiliate/conversionform') ;
                }
}


     $c = new Criteria();
  		$c->add(AgentCompanyPeer::ID, $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
  		$referrer_id = AgentCompanyPeer::doSelectOne($c);//->getId();
       // $this->form = new CustomerForm(CustomerPeer::retrieveByPK($customer->getId()));
		if($request->isMethod('post')){
                    if($mobile==""){
                       // $this->form = new CustomerForm(CustomerPeer::retrieveByPK($request->getParameter['Id']));
  		 //   $this->form = new CustomerForm();
                      //  echo $this->getParameter("id");
//                       echo $request->getParameter($form->getId());
//                      echo $request->getParameter('id');
//           //  print_r($customer);
//            die;
//                        $this->customer = CustomerPeer::retrieveByPK($request->getParameter("id"));
//               echo $this->customer->getId();
//
		//$this->forward404Unless($this->customer);
		//$this->redirectUnless($this->customer, "@homepage");
		//$this->form = new CustomerForm(CustomerPeer::retrieveByPK($this->customer->getId()));
                     //    $this->form = new CustomerForm();
                      
                 $this->form = new CustomerForm(CustomerPeer::retrieveByPK($_REQUEST['customer']['id']));
                        $this->form->bind($request->getParameter("newCustomerForm"), $request->getFiles("newCustomerForm"));
                        $this->form->setDefault('referrer_id', $referrer_id);
                      //   $this->form->setDefault('registration_type_id', 2);
                      unset($this->form['terms_conditions']);
                      unset($this->form['password']);
                        unset($this->form['password_confirm']);
                            
                     
                        
  			$this->processFormone($request, $this->form);
  		}

  		//set referrer id

                $this->form->getWidget('mobile_number')->setAttribute('readonly','readonly');
  		$this->getUser()->getAttribute('agent_company_id', '', 'usersession');
                $this->browser = new Browser();

           



  		

             //  $this->form = new CustomerForm();

             

  		//$this->setLayout();
                sfView::NONE;
  }
  

  }




















  public  function executeRegisterCustomer(sfWebRequest $request)
  {
    	
  		
      		
                //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 03/09/11 - Ahtsham
                changeLanguageCulture::languageCulture($request,$this);

  		//set referrer id
  		
       
  		$this->getUser()->getAttribute('agent_company_id', '', 'usersession');
                $this->browser = new Browser();

                $c = new Criteria();         
  		$c->add(AgentCompanyPeer::ID, $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
  		$referrer_id = AgentCompanyPeer::doSelectOne($c);//->getId();



  		if($request->isMethod('post')){
                 
						$this->form = new CustomerForm();
						$this->form->bind($request->getParameter("newCustomerForm"), $request->getFiles("newCustomerForm"));
						$this->form->setDefault('referrer_id', $referrer_id);
						unset($this->form['terms_conditions']);
						unset($this->form['imsi']);
						unset($this->form['uniqueid']);
//                        //unset($this->form['password']);
//                        unset($this->form['terms_conditions']);
                      // print_r($this->form);
                      //  die;
                        
  			$this->processForm($request, $this->form);
  		}else{
                   
                    $this->form = new CustomerForm();
                    
                          }
  		
  		//$this->setLayout();
                sfView::NONE;
  }

    protected function processFormone(sfWebRequest $request, sfForm $form)
  {
  	//print_r($request->getParameter($form->getName()));
    	$customer = $request->getParameter($form->getName());
  	   $product = $customer['product'];
     
        //$customer['referrer_id']= $this->getUser()->getAttribute('agent_company_id', '', 'usersession');
//echo $customer['id'];
//die;
 
  //  $this->form = new CustomerForm(CustomerPeer::retrieveByPK($customer['id']));
        unset($this->form['terms_conditions']);
	unset($this->form['imsi']);
	unset($this->form['uniqueid']);
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

 //print_r($form);
  // $this->redirect('@customer_registration_step3?customer_id='.$customer['id'].'&product_id='.$product);
   

    if($form->isValid())
    {
    // $customer=$customer['id'];
    //     $customer->setPlainText($request->getParameter($form->getPassword()));
     $customer = $form->save();
    
      $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
       $customer->setRegistrationTypeId('2');
       
      $customer->save();

	  if($customer){

	  }
     
		  echo "redirecting";
    $this->redirect('@customer_registration_step3?customer_id='.$customer->getId().'&product_id='.$product);
      //$this->redirect(sfConfig::get('app_epay_relay_script_url').$this->getController()->genUrl('@signup_step2?customer_id='.$customer->getId().'&product_id='.$product, true));
    }
 //   if ($form->hasErrors()){
//        echo 'form has errors<br/>';
//        echo '<br/>password: '.$form['password']->renderError();
//        echo '<br/>country: '.$form['country_id']->renderError();
//        echo '<br/>first name: '.$form['first_name']->renderError();
//        echo '<br/>last_name: '.$form['last_name']->renderError();
//        echo '<br/>country_id: '.$form['country_id']->renderError();
//        echo '<br/>city: '.$form['city']->renderError();
//        echo '<br/>po box number: '.$form['po_box_number']->renderError();
//        echo '<br/>mobile number: '.$form['mobile_number']->renderError();
//        echo '<br/>device id: '.$form['device_id']->renderError();
//        echo '<br/>email: '.$form['email']->renderError();
//        echo '<br/>password: '.$form['password']->renderError();
//        echo '<br/>is news letter subscribed: '.$form['is_newsletter_subscriber']->renderError();
//        echo '<br/>created_at: '.$form['created_at']->renderError();
//        echo '<br/>updated_at: '.$form['updated_at']->renderError();
//        echo '<br/>customer_status_id: '.$form['customer_status_id']->renderError();
//        echo '<br/>address: '.$form['address']->renderError();
//        echo '<br/>fonet_customer_id: '.$form['fonet_customer_id']->renderError();
//        echo '<br/>referrer_id: '.$form['referrer_id']->renderError();
//        echo '<br/>telecom_operator_id: '.$form['telecom_operator_id']->renderError();
//        echo '<br/>date_of_birth: '.$form['date_of_birth']->renderError();
//       // echo '<br/>other'.$form['other: ']->renderError();
//        echo '<br/>subscription_type: '.$form['subscription_type']->renderError();
//        echo '<br/>auto_refill_amount: '.$form['auto_refill_amount']->renderError();
//        echo '<br/>subscription_id: '.$form['subscription_id']->renderError();
//        echo '<br/>last_auto_refill: '.$form['last_auto_refill']->renderError();
//        echo '<br/>auto_refill_min_balance: '.$form['auto_refill_min_balance']->renderError();
//        echo '<br/>c9_customer_number: '.$form['c9_customer_number']->renderError();
//
//        //echo ''.$form['']->renderError();
//
//    }


  }



  protected function processForm(sfWebRequest $request, sfForm $form)
  {
  	//print_r($request->getParameter($form->getName()));
   	$customer = $request->getParameter($form->getName());
  	$product = $customer['product'];
        //$customer['referrer_id']= $this->getUser()->getAttribute('agent_company_id', '', 'usersession');
  $plainPws =  $customer["password"];
   
    
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
	
    if($form->isValid())
    {
   
    //     $customer->setPlainText($request->getParameter($form->getPassword()));
      $customer = $form->save();
      $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
       $customer->setRegistrationTypeId('2');
        $customer->setPlainText($plainPws);
      
      $customer->save();
	  if($customer){
	 
	  }
      $this->getUser()->setAttribute('customer_id', $customer->getId(), 'usersignup');
      $this->getUser()->setAttribute('product_id', $product, 'usersignup');
		  echo "redirecting";
      $this->redirect('@customer_registration_step2?customer_id='.$customer->getId().'&product_id='.$product);
      //$this->redirect(sfConfig::get('app_epay_relay_script_url').$this->getController()->genUrl('@signup_step2?customer_id='.$customer->getId().'&product_id='.$product, true));
    }
     
  }

   public function executeSetProductDetailsc(sfWebRequest $request)
  {
  		$this->forward404Unless($this->getUser()->isAuthenticated());
		$this->updateNews = NewupdatePeer::doSelect(new Criteria());

		$this->browser = new Browser();

  		$this->form = new PaymentForm();

		 unset(
			$this->form['cardno'],
			$this->form['expmonth'],
			$this->form['expyear'],
			$this->form['cvc'],
			$this->form['cardtype']
		 );


    	$product_id = $request->getParameter('product_id');
    	$customer_id = $request->getParameter('customer_id');

    	if($product_id == '' || $customer_id == ''){
    		$this->forward404('Product id not found in session');
    	}

    	$order = new CustomerOrder();
    	$transaction = new Transaction();

    	$order->setProductId($product_id);
    	$order->setCustomerId($customer_id);
    	$order->setExtraRefill($order->getProduct()->getInitialBalance());

    	//$extra_refil_choices = ProductPeer::getRefillChoices();

    	//$order->setExtraRefill($extra_refil_choices[0]);//minumum refill amount
    	$order->setIsFirstOrder(1);

    	$order->save();

		$customer = CustomerPeer::retrieveByPk($customer_id);
                $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'usersession')) ;
                $customer->save();

                $transaction->setAgentCompanyId($customer->getReferrerId());

          $transaction->setAmount(($order->getProduct()->getPrice() - $order->getProduct()->getInitialBalance() + $order->getExtraRefill()) / 100);
                $transaction->setDescription($this->getContext()->getI18N()->__('Registrering inkl. taletid'));
                $transaction->setOrderId($order->getId());
                $transaction->setCustomerId($customer_id);



    	//$transaction->setTransactionStatusId() // default value 1

    	$transaction->save();
    	$this->order = $order;
    	$this->forward404Unless($this->order);

    	$this->order_id = $order->getId();
    	$this->amount   = $transaction->getAmount();

         if($request->getParameter('balance_error')=='1'){
            $this->getUser()->setFlash('message', 'You Do not have enough Balance, Please Recharge');
            $this->getUser()->setFlash('error_message', 'You Do not have enough Balance, Please Recharge');
            $this->balance_error = $request->getParameter('balance_error');
         }else{

            $this->balance_error = "";
         }

  }



  public function executeSetProductDetails(sfWebRequest $request)
  {
  		$this->forward404Unless($this->getUser()->isAuthenticated());
		$this->updateNews = NewupdatePeer::doSelect(new Criteria());
		$this->browser = new Browser();   		
  		$this->form = new PaymentForm();
                $this->target = $this->targetURL;
		 unset( 
			$this->form['cardno'],
			$this->form['expmonth'],
			$this->form['expyear'],
			$this->form['cvc'],
			$this->form['cardtype']
		 );
		 
		 
    	$product_id = $request->getParameter('product_id');
    	$customer_id = $request->getParameter('customer_id');
    	
    	if($product_id == '' || $customer_id == ''){
    		$this->forward404('Product id not found in session');
    	}
    	
    	$order = new CustomerOrder();
    	$transaction = new Transaction();
    	
    	$order->setProductId($product_id);
    	$order->setCustomerId($customer_id);
    	$order->setExtraRefill($order->getProduct()->getInitialBalance());
    	
    	//$extra_refil_choices = ProductPeer::getRefillChoices();
    	
    	//$order->setExtraRefill($extra_refil_choices[0]);//minumum refill amount
    	$order->setIsFirstOrder(1);
    	
    	$order->save();
		
		$customer = CustomerPeer::retrieveByPk($customer_id);
                $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'usersession')) ;
                $customer->save();

                $transaction->setAgentCompanyId($customer->getReferrerId());

          $transaction->setAmount(($order->getProduct()->getPrice() - $order->getProduct()->getInitialBalance() + $order->getExtraRefill()) / 100);
                $transaction->setDescription($this->getContext()->getI18N()->__('Registrering inkl. taletid'));
                $transaction->setOrderId($order->getId());
                $transaction->setCustomerId($customer_id);


		
    	//$transaction->setTransactionStatusId() // default value 1
    	
    	$transaction->save();    	
    	$this->order = $order;
    	$this->forward404Unless($this->order);
    	
    	$this->order_id = $order->getId();
    	$this->amount   = $transaction->getAmount();

         if($request->getParameter('balance_error')=='1'){
            $this->getUser()->setFlash('decline', 'You Do not have enough Balance, Please Recharge');
            $this->getUser()->setFlash('error_message', 'You Do not have enough Balance, Please Recharge');
            $this->balance_error = $request->getParameter('balance_error');
         }else{

            $this->balance_error = ""; 
         }

  }



    public function executeCompleteCustomerRegistrationconversion(sfWebRequest $request)
  {


  	$this->forward404Unless($this->getUser()->isAuthenticated());
	$this->updateNews = NewupdatePeer::doSelect(new Criteria());
	$this->browser = new Browser();


        //debug form value
	$order_id = $request->getParameter('orderid');
        //$request->getParameter('amount');
  	$order_amount = ((double)$request->getParameter('amount'));

        $this->forward404Unless($order_id || $order_amount);


  	$order = CustomerOrderPeer::retrieveByPK($order_id);

  	//if order is already completed > 404
  	$this->forward404Unless($order->getOrderStatusId()!=sfConfig::get('app_status_completed'));
  	$this->forward404Unless($order);

        //get agent
        $ca = new Criteria();
	$ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
         $agent->getId();

        //getting agent commission
        $cc = new Criteria();
        $cc->add(AgentCommissionPackagePeer::ID, $agent->getAgentCommissionPackageId());
        $commission_package = AgentCommissionPackagePeer::doSelectOne($cc);


        //get transaction
  	$c = new Criteria;
  	$c->add(TransactionPeer::ORDER_ID, $order_id);
  	$transaction = TransactionPeer::doSelectOne($c);

  	$order->setOrderStatusId(sfConfig::get('app_status_completed', 3)); //completed
  	$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 3)); //completed
  	$transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3)); //completed

  	if($transaction->getAmount() > $order_amount){

  		$order->setOrderStatusId(sfConfig::get('app_status_error', 5)); //error in amount
  		$transaction->setTransactionStatusId(sfConfig::get('app_status_error', 5)); //error in amount
  		$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_error', 5)); //error in amount

  	} else if ($transaction->getAmount() < $order_amount){
  		$transaction->setAmount($order_amount/100);
  	}

  	$is_transaction_completed = $transaction->getTransactionStatusId()==sfConfig::get('app_status_completed', 3);

  	// if transaction ok
  	if ($is_transaction_completed)
  	{

	  	$product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();
	  	$product_price_vat = .20 * $product_price;


                $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
                ///////////////////////////commision calculation by agent product ///////////////////////////////////////
                    $cp = new Criteria;
                    $cp->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                     $cp->add(AgentProductPeer::PRODUCT_ID,$order->getProductId());
                    $agentproductcount = AgentProductPeer::doCount($cp);

                    if($agentproductcount>0){
                      $p = new Criteria;
                      $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                      $p->add(AgentProductPeer::PRODUCT_ID,$order->getProductId());

                    $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                   $agentcomession=$agentproductcomesion->getRegShareEnable();
                    }
                    
                      ////////   commission setting  through  agent commision//////////////////////

                    if($agentcomession){


                if($order->getIsFirstOrder()){
                    if($agentproductcomesion->getIsRegShareValuePc()){
                        $transaction->setCommissionAmount(($transaction->getAmount()/100) * $agentproductcomesion->getRegShareValue());
                    }else{

                        $transaction->setCommissionAmount( $agentproductcomesion->getRegShareValue());
                    }
                }else{
                    if($agentproductcomesion->getIsExtraPaymentsShareValuePc()){
                            $transaction->setAgentCommission(($transaction->getAmount()/100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    }else{
                            $transaction->setAgentCommission($agentproductcomesion->getExtraPaymentsShareValue());
                    }

                }




                    }else{

                if($order->getIsFirstOrder()){
                    if($commission_package->getIsRegShareValuePc()){
                        $transaction->setCommissionAmount(($transaction->getAmount()/100) * $commission_package->getRegShareValue());
                    }else{
                        
                        $transaction->setCommissionAmount( $commission_package->getRegShareValue());
                    }
                }else{
                    if($commission_package->getIsExtraPaymentsShareValuePc()){
                            $transaction->setAgentCommission(($transaction->getAmount()/100) * $commission_package->getExtraPaymentsShareValue());
                    }else{
                            $transaction->setAgentCommission($commission_package->getExtraPaymentsShareValue());
                    }

                }
                    }


	$transaction->save();



                
/////////////////////////end of commission setting ////////////////////////////////////////////

                if ($agent->getIsPrepaid()==true)
	  	{



                    if($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount()) ){
                        $this->redirect('affiliate/setProductDetails?product_id='.$order->getProductId().'&customer_id='.$transaction->getCustomerId().'&balance_error=1');
                    }else{
                    $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));                    
                    $agent->save();

                     $remainingbalance=$agent->getBalance();



   ////////////////////////    agent payment history////////////
                    $amount=$transaction->getAmount() - $transaction->getCommissionAmount();
                     $amount=-$amount;
                     $aph = new AgentPaymentHistory();
                     $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                     $aph->setCustomerId($transaction->getCustomerId());
                     $aph->setExpeneseType(1);
                     $aph->setAmount($amount);
                     $aph->setRemainingBalance($remainingbalance);
                     $aph->save();




                    ////////////////////////////////////////////
                    }
                }


  	}

  	$order->save();
  


  	if ($is_transaction_completed)
  	{


 $order->getCustomerId();

          $c = new Criteria;
  	 $c->add(CustomerProductPeer::CUSTOMER_ID, $order->getCustomerId());
  	 $customerproduct = CustomerProductPeer::doSelectOne($c);
      $customerproduct->getId();
  
	  	//set customer's proudcts in use
	  	//$customer_product = new CustomerProduct();
    
             $customer_product= CustomerProductPeer::retrieveByPK($customerproduct->getId());

	  	 $customer_product->setCustomerId($order->getCustomerId());
	  	 $customer_product->setProductId($order->getProductId());

	  	 $customer_product->save();

	  	//register to fonet
	  	$this->customer = $order->getCustomer();
                      //echo "SIP is being Assigned 1";
              $sc = new Criteria();
              $sc->add(SipPeer::ASSIGNED, false);
              $sip = SipPeer::doSelectOne($sc);

              //echo "SIP is being Assigned 2";

              $sip->setCustomerId($this->customer->getId());
              $sip->setAssigned(true);
              $sip->save();

              $cc = new Criteria();
              $cc->add(CountryPeer::ID, $this->customer->getCountryId());
              $country = CountryPeer::doSelectOne($cc);

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



                Fonet::unregister($this->customer);
	  	Fonet::registerFonet($this->customer);
	  	Fonet::recharge($this->customer, $order->getExtraRefill());

                //generate SMS
                $data = array(
                      'type' => 'add',
                      'secret'=>'ep1638F2',
                      'username'=>$this->customer->getMobileNumber(),
                      'password'=>$this->customer->getPassword(),
                      'name' =>$this->customer->getFirstName().' '.$this->customer->getLastName(),
                      'email'=>$this->customer->getEmail()
                );

                $queryString = http_build_query($data,'', '&');
                $res = file_get_contents('http://70.38.12.20:9090/plugins/userService/userservice?'.$queryString);

                //generate Email
                emailLib::sendCustomerRegistrationViaAgentEmail($this->customer,$order);
                $this->getUser()->setFlash('message', 'Customer '.$this->customer->getMobileNumber().'  conversion registered successfully');

              
                $this->redirect('affiliate/receipts');
  	}


  }




  public function executeCompleteCustomerRegistration(sfWebRequest $request)
  {

      
       
  	$this->forward404Unless($this->getUser()->isAuthenticated());
	$this->updateNews = NewupdatePeer::doSelect(new Criteria());
	$this->browser = new Browser();


        //debug form value
	$order_id = $request->getParameter('orderid');  	
        //$request->getParameter('amount');
  	$order_amount = ((double)$request->getParameter('amount'));  	

        $this->forward404Unless($order_id || $order_amount);
  	

  	$order = CustomerOrderPeer::retrieveByPK($order_id);
  	
  	//if order is already completed > 404
  	$this->forward404Unless($order->getOrderStatusId()!=sfConfig::get('app_status_completed'));  	
  	$this->forward404Unless($order);

        //get agent
        $ca = new Criteria();
	$ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        echo $agent->getId();
       
        //getting agent commission
        $cc = new Criteria();
        $cc->add(AgentCommissionPackagePeer::ID, $agent->getAgentCommissionPackageId());
        $commission_package = AgentCommissionPackagePeer::doSelectOne($cc);
        

        //get transaction
  	$c = new Criteria;
  	$c->add(TransactionPeer::ORDER_ID, $order_id);  	
  	$transaction = TransactionPeer::doSelectOne($c);

  	$order->setOrderStatusId(sfConfig::get('app_status_completed', 3)); //completed
  	$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 3)); //completed
  	$transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3)); //completed
  	
  	if($transaction->getAmount() > $order_amount){

  		$order->setOrderStatusId(sfConfig::get('app_status_error', 5)); //error in amount
  		$transaction->setTransactionStatusId(sfConfig::get('app_status_error', 5)); //error in amount
  		$order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_error', 5)); //error in amount
  		
  	} else if ($transaction->getAmount() < $order_amount){
  		$transaction->setAmount($order_amount/100);
  	}
  	
  	$is_transaction_completed = $transaction->getTransactionStatusId()==sfConfig::get('app_status_completed', 3);
  	$agentcomession=Null;
  	// if transaction ok
  	if ($is_transaction_completed)
  	{
  		
	  	$product_price = $order->getProduct()->getPrice() - $order->getExtraRefill();	  	
	  	$product_price_vat = .20 * $product_price;	  		  	





                $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
                ///////////////////////////commision calculation by agent product ///////////////////////////////////////
                    $cp = new Criteria;
                    $cp->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                     $cp->add(AgentProductPeer::PRODUCT_ID,$order->getProductId());
                    $agentproductcount = AgentProductPeer::doCount($cp);

                    if($agentproductcount>0){
                      $p = new Criteria;
                      $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                      $p->add(AgentProductPeer::PRODUCT_ID,$order->getProductId());

                    $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                   $agentcomession=$agentproductcomesion->getRegShareEnable();
                    }
                    
                      ////////   commission setting  through  agent commision//////////////////////

                    if($agentcomession){


                if($order->getIsFirstOrder()){
                    if($agentproductcomesion->getIsRegShareValuePc()){
                        $transaction->setCommissionAmount(($transaction->getAmount()/100) * $agentproductcomesion->getRegShareValue());
                    }else{

                        $transaction->setCommissionAmount( $agentproductcomesion->getRegShareValue());
                    }
                }else{
                    if($agentproductcomesion->getIsExtraPaymentsShareValuePc()){
                            $transaction->setAgentCommission(($transaction->getAmount()/100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    }else{
                            $transaction->setAgentCommission($agentproductcomesion->getExtraPaymentsShareValue());
                    }

                }




                    }else{

                if($order->getIsFirstOrder()){
                    if($commission_package->getIsRegShareValuePc()){
                        $transaction->setCommissionAmount(($transaction->getAmount()/100) * $commission_package->getRegShareValue());
                    }else{
                        
                        $transaction->setCommissionAmount( $commission_package->getRegShareValue());
                    }
                }else{
                    if($commission_package->getIsExtraPaymentsShareValuePc()){
                            $transaction->setAgentCommission(($transaction->getAmount()/100) * $commission_package->getExtraPaymentsShareValue());
                    }else{
                            $transaction->setAgentCommission($commission_package->getExtraPaymentsShareValue());
                    }

                }
                    }


	$transaction->save();



                
/////////////////////////end of commission setting ////////////////////////////////////////////
                   echo 'entering if';
                   echo '<br/>';
                if ($agent->getIsPrepaid()==true)
	  	{

                   echo 'agent is prepaid';
                   echo '<br/>';
                   echo $agent->getBalance();
                   echo '<br/>';
                   echo $transaction->getCommissionAmount();
                   echo '<br/>';
                   echo $agent->getBalance() < $transaction->getCommissionAmount();

                    if($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount()) ) {
                        $this->redirect('affiliate/setProductDetails?product_id='.$order->getProductId().'&customer_id='.$transaction->getCustomerId().'&balance_error=1');
                    }else{
                    $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));
                    $agent->save();
                    ////////////////////////////////////
                      $remainingbalance=$agent->getBalance();
                    $amount=$transaction->getAmount() - $transaction->getCommissionAmount();
                       $amount=-$amount;
                     $aph = new AgentPaymentHistory();
                     $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                     $aph->setCustomerId($transaction->getCustomerId());
                     $aph->setExpeneseType(1);
                     $aph->setAmount($amount);
                     $aph->setRemainingBalance($remainingbalance);
                     $aph->save();




                    ////////////////////////////////////////////







                    }
                }

               
  	}






  	$order->save();
  
        
  	
  	if ($is_transaction_completed)
  	{
	  	//set customer's proudcts in use
	  	$customer_product = new CustomerProduct();
	  	
	  	$customer_product->setCustomer($order->getCustomer());
	  	$customer_product->setProduct($order->getProduct());
	  	
	  	$customer_product->save();
	  	
	  	//register to fonet
	  	$this->customer = $order->getCustomer();
//	  	Fonet::registerFonet($this->customer);
//	  	Fonet::recharge($this->customer, $order->getExtraRefill());
                $uniqueid = $request->getParameter('uniqueid');
                $uc = new Criteria();
                $uc->add(UniqueIdsPeer::REGISTRATION_TYPE_ID, 2);
                $uc->addAnd(UniqueIdsPeer::STATUS, 0);
                $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueid);
                $availableUniqueCount = UniqueIdsPeer::doCount($uc);
                $availableUniqueId = UniqueIdsPeer::doSelectOne($uc);


                if($availableUniqueCount  == 0){
                    // Unique Ids are not avaialable.  send email to the support.
                    emailLib::sendUniqueIdsIssueAgent($uniqueid,$this->customer);
                    
                    
                }else{
                    $availableUniqueId->setStatus(1);
                    $availableUniqueId->setAssignedAt(date('Y-m-d H:i:s'));
                    $availableUniqueId->save();
                }
                $this->customer->setUniqueid(str_replace(' ', '', $uniqueid));
                $this->customer->save();



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

                $callbacklog = new CallbackLog();
                $callbacklog->setMobileNumber($TelintaMobile);
                $callbacklog->setuniqueId($this->customer->getUniqueid());
                $callbacklog->setCheckStatus(3);
                $callbacklog->save();

                 //Section For Telinta Add Cusomter

                Telienta::ResgiterCustomer($this->customer, $order->getExtraRefill());
                Telienta::createAAccount($TelintaMobile, $this->customer);
                Telienta::createCBAccount($TelintaMobile, $this->customer);

               


                //generate SMS
                $data = array(
                      'type' => 'add',
                      'secret'=>'ep1638F2',
                      'username'=>$this->customer->getMobileNumber(),
                      'password'=>$this->customer->getPassword(),
                      'name' =>$this->customer->getFirstName().' '.$this->customer->getLastName(),
                      'email'=>$this->customer->getEmail()
                );
                
                $queryString = http_build_query($data,'', '&');
                $res = file_get_contents('http://70.38.12.20:9090/plugins/userService/userservice?'.$queryString);

                //generate Email
                $this->getUser()->setCulture('sv');
                emailLib::sendCustomerRegistrationViaAgentEmail($this->customer,$order);
                $this->getUser()->setCulture('en');
                $this->getUser()->setFlash('message', 'Customer '.$this->customer->getMobileNumber().' is registered successfully');
                $this->redirect('affiliate/receipts');
  	}


  }
  
  public function executeFaq(sfWebRequest $request)
  {
		
		//call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
      	changeLanguageCulture::languageCulture($request,$this);
	  
       //----Query Get FAQs

      //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $country_id = $agent->getCountryId();

        //-----------------------------------
        //        $countrylng = new Criteria();
        //        $countrylng->add(EnableCountryPeer::ID, $country_id);
        //        $countrylng = EnableCountryPeer::doSelectOne($countrylng);
        //        $countryRefill = $countrylng->getRefill();


        $Faqs = new Criteria();
        //$Faqs->add(FaqsPeer::COUNTRY_ID, $country_id);
        $Faqs->add(FaqsPeer::STATUS_ID, 1);
        $Faqs = FaqsPeer::doSelect($Faqs);

        $this->Faqs = $Faqs;
    //-----------
	  $this->updateNews = NewupdatePeer::doSelect(new Criteria());
	  $this->browser = new Browser();
  	
  }
  
  public function executeUserguide(sfWebRequest $request)
  {
		
		//call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
      	changeLanguageCulture::languageCulture($request,$this);
		
       //----Query Get UserGuide

      //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $country_id = $agent->getCountryId();

        //-----------------------------------
        //        $countrylng = new Criteria();
        //        $countrylng->add(EnableCountryPeer::ID, $country_id);
        //        $countrylng = EnableCountryPeer::doSelectOne($countrylng);
        //        $countryRefill = $countrylng->getRefill();


        $Userguide = new Criteria();
      // $Userguide->add(UserguidePeer::COUNTRY_ID, $country_id);
        $Userguide->add(UserguidePeer::STATUS_ID, 1);
        $Userguide = UserguidePeer::doSelect($Userguide);

        $this->Userguide = $Userguide;
    //-----------
        
	  $this->updateNews = NewupdatePeer::doSelect(new Criteria());
  	$this->browser = new Browser();
  }
  
  public function executeSupportingHandset(sfWebRequest $request)
  {
         //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
         changeLanguageCulture::languageCulture($request,$this);
        
	  $this->updateNews = NewupdatePeer::doSelect(new Criteria());
	  $this->browser = new Browser();
  	
  }

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
                    $agent_order->setStatus('1');
                    $agent_order->save();

                    $agent_order->setAgentOrderId('a0'.$agent_order->getId());
                    $agent_order->save();

                    $this->agent_order = $agent_order;
                
            }
  }

  public function executeThankyou(sfWebRequest $request){

      $order_id = $request->getParameter('orderid') ;
      $amount = $request->getParameter('amount') ;

      if( $order_id and $amount){
          $c = new Criteria();
          $c->add(AgentOrderPeer::AGENT_ORDER_ID, $order_id);
          $c->add(AgentOrderPeer::STATUS, 1);
          $agent_order = AgentOrderPeer::doSelectOne($c);

          $agent_order->setAmount($amount/100);
          $agent_order->setStatus(3);
          $agent_order->save();

          $agent = AgentCompanyPeer::retrieveByPK($agent_order->getAgentCompanyId());
          $agent->setBalance($agent->getBalance() + ($amount/100));
          $agent->save();
          $this->agent=$agent;

                     $amount=$amount/100;
                     $remainingbalance=$agent->getBalance();
                     $aph = new AgentPaymentHistory();
                     $aph->setAgentId($agent_order->getAgentCompanyId());
                     $aph->setExpeneseType(3);
                     $aph->setAmount($amount);
                     $aph->setRemainingBalance($remainingbalance);
                     $aph->save();

          $this->getUser()->setFlash('message', 'Your Credit Card recharge of '.$amount.'SEK is approved');
          emailLib::sendAgentRefilEmail($this->agent,$agent_order);
          $this->redirect('affiliate/agentOrder');
  
                }
  }

public function executeAgentOrder(sfRequest $request){

    //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
     changeLanguageCulture::languageCulture($request,$this);
     
                $ca = new Criteria();
		$ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                $agent = AgentCompanyPeer::doSelectOne($ca);
		$this->forward404Unless($agent);

                $this->agent=$agent;
                $c = new Criteria();
                $c->add(AgentOrderPeer::AGENT_COMPANY_ID, $agent->getId());
                $c->add(AgentOrderPeer::STATUS, 3);
                $this->agentOrders=AgentOrderPeer::doSelect($c);


  }

  public function executePrintAgentReceipt(sfWebrequest $request){
                $ca = new Criteria();
		$ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                $agent = AgentCompanyPeer::doSelectOne($ca);
		$this->forward404Unless($agent);

                $aoid = $request->getParameter('aoid');
                $agent_order = AgentOrderPeer::retrieveByPk($aoid);
                $this->agent=$agent;
                $this->aoid = $aoid;
                $this->agent_order = $agent_order;

                $this->setLayout(false);

  }
   public function executePaymentHistory(sfWebrequest $request){
        changeLanguageCulture::languageCulture($request,$this);
                $ca = new Criteria();
		$ca->add(AgentPaymentHistoryPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                $agent = AgentPaymentHistoryPeer::doSelect($ca);
		$this->forward404Unless($agent);
        
                $this->agents=$agent;
              

               

  }
  
    public function executeGetmobilemodel(sfWebRequest $request){

      if ($request->isXmlHttpRequest()) {
           // echo $request->getParameter('device_id').'pakistan';
            $device_id = (int)$request->getParameter('device_id');
            if($device_id) {
                    // Get The Mobile Model
                    $Mobilemodel=new Criteria();
                    $Mobilemodel->add(DevicePeer::MANUFACTURER_ID,$device_id);
                    $mModel =  DevicePeer::doSelect($Mobilemodel);
                    //echo $mModel->getName();
                  $output = '<option value=""></option>';
                    foreach($mModel as $mModels){
                        echo $mModels->getName();
                        $output .= '<option value="'.$mModels->getId ().'">'.$mModels->getName().'</option>';
                    }
                    return $this->renderText($output);
            }
        }

  }

   public function executeValidateUniqueId(sfWebRequest $request){

        $uniqueId = $request->getParameter('uniqueid');
        $uc = new Criteria();
        $uc->add(UniqueIdsPeer::REGISTRATION_TYPE_ID, 2);
        $uc->addAnd(UniqueIdsPeer::STATUS, 0);
        $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueId);
        $availableUniqueCount = UniqueIdsPeer::doCount($uc);
        if($availableUniqueCount == 1){
            echo "true";
        }else{
            echo "false";
        }

        return sfView::NONE;
   }

   public function executeChangenumberservice(sfWebRequest $request) {

       changeLanguageCulture::languageCulture($request,$this);
       $this->browser = new Browser();
    
   }
   public function executeChangenumber(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);

        $mobile = "";
        $existingNumber = $request->getParameter('existingNumber');
        $this->newNumber = $request->getParameter('newNumber');
        $this->countrycode = $request->getParameter('countrycode');
        if (isset($_REQUEST['existingNumber']) && $_REQUEST['existingNumber'] != "") {
            $mobile = $_REQUEST['existingNumber'];
            $product = $_REQUEST['product'];
            $cc = new Criteria();
            $cc->add(CustomerPeer::MOBILE_NUMBER, $mobile);
            $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);

            $c = new Criteria();
            $c->add(ProductPeer::ID, $product);
            $product = ProductPeer::doSelectOne($c);

            if (CustomerPeer::doCount($cc) == 0) {
                $this->getUser()->setFlash('message', 'Customer Does not exist');
                $this->redirect('affiliate/refill');
            }

            $customer = CustomerPeer::doSelectOne($cc);
            if ($customer) {
                $this->customer = $customer;
                $this->product = $product;
            } else {
                $this->getUser()->setFlash('message', 'Customer Does not exist');
                $this->redirect('affiliate/refill');
            }
        }
    }

    public function executeNumberProcess(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 03/09/11 - Ahtsham
        changeLanguageCulture::languageCulture($request, $this);

        $this->browser = new Browser();
        $this->form = new AccountRefillAgent();

        $this->error_msg = "";
        $this->error_mobile_number = "";
        $validated = false;

        //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);

        //get Agent commission package
        $cpc = new Criteria();
        $cpc->add(AgentCommissionPackagePeer::ID, $agent->getAgentCommissionPackageId());
        $commission_package = AgentCommissionPackagePeer::doSelectOne($cpc);

        if ($request->getParameter('balance_error')) {
            $this->balance_error = $request->getParameter('balance_error');
        } else {
            $this->balance_error = 0;
        }

        if ($request->isMethod('post')) {
            $mobile_number = $request->getParameter('mobile_number');
            $productid = $request->getParameter('productid');
            $extra_refill = $request->getParameter('extra_refill');
            $newnumber = $request->getParameter('newnumber');
            $countrycode = $request->getParameter('countrycode');

            $is_recharged = true;
            $transaction = new Transaction();
            $order = new CustomerOrder();
            $customer = NULL;
            $cc = new Criteria();
            $cc->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
            $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
           
            $customer = CustomerPeer::doSelectOne($cc);
         
            if ($customer and $mobile_number != "") {
                $validated = true;
            } else {
                $validated = false;
                $is_recharged = false;
                $this->error_mobile_number = 'invalid mobile number';
                return;
            }

            if ($validated) {
               
///////////////////////////////change number process///////////////////////////////////////////////////////////////////
////////////////////////chena number process end//////////////////////////////////////////////////////////




                $order->setCustomerId($customer->getId());
                $order->setProductId($productid);
                $order->setQuantity(1);
                $order->setExtraRefill($extra_refill);
                $order->setOrderStatusId(sfConfig::get('app_status_new'));
               
                $order->save();

                //create transaction
                $transaction->setOrderId($order->getId());
                $transaction->setCustomerId($customer->getId());
                $transaction->setAmount($extra_refill);
                //get agent nam
                $transaction->setDescription('Avgift för förändring nummer (' . $agent->getName() . ')');
                $transaction->setAgentCompanyId($agent->getId());
                //assign commission to transaction;
                
                /////////////////////////////////////////////////////////////////////////////////////////////////
                $order->setAgentCommissionPackageId($agent->getAgentCommissionPackageId());
                    ///////////////////////////commision calculation by agent product ///////////////////////////////////////
                $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession');
                $cp = new Criteria;
                $cp->add(AgentProductPeer::AGENT_ID, $agent_company_id);
                $cp->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());
                $agentproductcount = AgentProductPeer::doCount($cp);
                if ($agentproductcount > 0) {
                    $p = new Criteria;
                    $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                    $p->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());

                    $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                    $agentcomession = $agentproductcomesion->getExtraPaymentsShareEnable();
                }

                ////////   commission setting  through  agent commision//////////////////////

                if (isset($agentcomession) && $agentcomession != "") {

                    if ($agentproductcomesion->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setCommissionAmount($agentproductcomesion->getExtraPaymentsShareValue());
                    }
                } else {
                    if ($commission_package->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $commission_package->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setCommissionAmount($commission_package->getExtraPaymentsShareValue());
                    }
                }
                //calculated amount for agent commission
                if ($agent->getIsPrepaid() == true) {
                    if ($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount())) {
                        $is_recharged = false;
                        $balance_error = 1;
                    }
                }
                          // var_dump($customer);exit;
                if ($is_recharged) {

                    $transaction->save();
                    if ($customer) {
                        $newMobileNo=$countrycode.substr($newnumber,1);
                        $customerids = $customer->getId();
                        $uniqueId=$customer->getUniqueid();
                        $customer->setMobileNumber($newnumber);
                        $customer->save();

                        $changenumberdetail = new ChangeNumberDetail();
                        $changenumberdetail->setOldNumber($mobile_number);
                        $changenumberdetail->setNewNumber($newnumber);
                        $changenumberdetail->setCustomerId($customerids);
                        $changenumberdetail->setStatus(3);
                        $changenumberdetail->save();

                        $un = new Criteria();
                        $un->add(CallbackLogPeer::UNIQUEID, $uniqueId);
                        $un -> addDescendingOrderByColumn(CallbackLogPeer::CREATED);
                        $activeNumber = CallbackLogPeer::doSelectOne($un);

                           // As each customer have a single account search the previous account and terminate it.
                            $cp = new Criteria;
                            $cp->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'a'. $activeNumber->getMobileNumber());
                            $cp->addAnd(TelintaAccountsPeer::STATUS, 3);

                            if(TelintaAccountsPeer::doCount($cp)>0){
                                $telintaAccount = TelintaAccountsPeer::doSelectOne($cp);
                                Telienta::terminateAccount($telintaAccount);
                            }

                            Telienta::createAAccount($newMobileNo, $customer);

                            $cb = new Criteria;
                            $cb->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'cb'. $activeNumber->getMobileNumber());
                            $cb->addAnd(TelintaAccountsPeer::STATUS, 3);

                            if(TelintaAccountsPeer::doCount($cb)>0){
                                $telintaAccountsCB = TelintaAccountsPeer::doSelectOne($cb);
                                Telienta::terminateAccount($telintaAccountsCB);
                            }
                            Telienta::createCBAccount($newMobileNo, $customer);

                            $getvoipInfo = new Criteria();
                            $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customerids);
                            $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo);//->getId();
                            if(isset($getvoipInfos)){
                                $voipnumbers = $getvoipInfos->getNumber() ;
                                $voipnumbers =  substr($voipnumbers,2);
                            }else{
                            }

                            $tc = new Criteria();
                            $tc->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
                            $tc->add(TelintaAccountsPeer::STATUS,3);
                            if(TelintaAccountsPeer::doCount($tc)>0){
                                $telintaAccountR = TelintaAccountsPeer::doSelectOne($tc);
                                Telienta::terminateAccount($telintaAccountR);
                            }

                            Telienta::createReseNumberAccount($voipnumbers, $customer, $newMobileNo);
                        }

                            $callbacklog = new CallbackLog();
                            $callbacklog->setMobileNumber($newMobileNo);
                            $callbacklog->setuniqueId($uniqueId);
                            $callbacklog->setcallingCode($countrycode);
                            $callbacklog->save();

                        
                         $number = $countrycode . $mobile_number;
                         $sms = SmsTextPeer::retrieveByPK(12);
                         $smsText = $sms->getMessageText();
                         $smsText = str_replace(array("(oldnumber)", "(newnumber)"),array($mobile_number, $newnumber),$smsText);
                    
                         CARBORDFISH_SMS::Send($number, $sms_text,"LandNCall");
                         //Send SMS ----
                         $number = $newMobileNo;
                         CARBORDFISH_SMS::Send($number, $sms_text,"LandNCall");
                       
                    }
//exit;
                    if ($agent->getIsPrepaid() == true) {
                        $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));
                        $agent->save();
                        $remainingbalance = $agent->getBalance();
                        $amount = $transaction->getAmount() - $transaction->getCommissionAmount();
                        $amount = -$amount;
                        $aph = new AgentPaymentHistory();
                        $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'usersession'));
                        $aph->setCustomerId($transaction->getCustomerId());
                        $aph->setExpeneseType(6);
                        $aph->setAmount($amount);
                        $aph->setRemainingBalance($remainingbalance);
                        $aph->save();
                    }
                    //set status
                    $order->setOrderStatusId(sfConfig::get('app_status_completed'));
                    $transaction->setTransactionStatusId(sfConfig::get('app_status_completed'));
                    $order->save();
                    $transaction->save();
                    $this->customer = $order->getCustomer();
                    emailLib::sendRefillEmail($this->customer, $order);
                    $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('%1% Mobile Number is changed successfully  with %2% dkk.', array("%1%" => $customer->getMobileNumber(), "%2%" => $transaction->getAmount())));

                    $this->redirect('affiliate/receipts');
                } else {

                    $this->balance_error = 1;
                    $this->getUser()->setFlash('error', 'You do not have enough balance, please recharge');
                } //end else
            } else {

                $this->balance_error = 1;
                $is_recharged = false;
                $this->error_mobile_number = 'invalid mobile number';
            }
        }
}
