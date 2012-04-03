<?php
require_once(sfConfig::get('sf_lib_dir').'/company_employe_activation.class.php');
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');
/**
 * employee actions.
 *
 * @package    zapnacrm
 * @subpackage employee
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 5125 2007-09-16 00:53:55Z dwhittle $
 */
class employeeActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $c = new Criteria();
                $companyid=$request->getParameter('company_id');
                $this->companyval=$companyid;
   if (isset($companyid) && $companyid != '') {
            $c->addAnd(EmployeePeer::COMPANY_ID,  $companyid);

        }
        $this->employees = EmployeePeer::doSelect($c);
    }

    public function executeEdit(sfWebRequest $request) {

        $e = new Criteria();
        $e->add(EmployeePeer::ID, $request->getParameter('id'));
        $this->employee = EmployeePeer::doSelectOne($e);


        $c = new Criteria();
        $this->companys = CompanyPeer::doSelect($c);

        $pr = new Criteria();
         $pr->add(ProductPeer::ID, 14);
        //$pr->add(ProductPeer::IS_IN_ZAPNA, 1);
        $this->products = ProductPeer::doSelect($pr);
    }

    protected function addFiltersCriteria($c) {

        if (isset($this->filters['vat_no']) && $this->filters['vat_no'] !== '') {
            $c->add(CompanyPeer::VAT_NO, strtr($this->filters['vat_no'], '*', '%'), Criteria::LIKE);
            $c->addJoin(CompanyPeer::ID, EmployeePeer::COMPANY_ID);

            $this->filters['company_id'] = '';
        } else {
            parent::addFiltersCriteria($c);
        }

        //$c->add(CompanyPeer::VAT_NO, strtr($this->filters['vat_no'], '*', '%'), Criteria::LIKE);
        //$c->addJoin(CompanyPeer::ID, EmployeePeer::COMPANY_ID);
        //$tmp = $this->filters['vat_no'];
    }

    public function executeView($request) {
        $this->employee = EmployeePeer::retrieveByPK($request->getParameter('id'));
        $mobilenumber= $this->employee->getCountryMobileNumber();
        $ct = new Criteria();
        $ct->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'a'.$mobilenumber);
        $ct->addAnd(TelintaAccountsPeer::STATUS, 3);
        $telintaAccount = TelintaAccountsPeer::doSelectOne($ct);
        $account_info = CompanyEmployeActivation::getAccountInfo($telintaAccount->getIAccount());
        $balance = $account_info->account_info->balance;

        $cb = new Criteria();
        $cb->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'cb'.$mobilenumber);
        $cb->addAnd(TelintaAccountsPeer::STATUS, 3);
        $telintaAccountcb = TelintaAccountsPeer::doSelectOne($cb);
        $account_infocb = CompanyEmployeActivation::getAccountInfo($telintaAccountcb->getIAccount());
        $balancecb = $account_infocb->account_info->balance;

        $getvoipInfo = new Criteria();
        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $mobilenumber);
        $getvoipInfo->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
        if (isset($getvoipInfos)) {
            $voipnumbers = $getvoipInfos->getNumber();
            $voipnumbers = substr($voipnumbers, 2);


            $res = new Criteria();
            $res->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
            $res->addAnd(TelintaAccountsPeer::STATUS, 3);
            $telintaAccountres = TelintaAccountsPeer::doSelectOne($res);
            $account_infores = CompanyEmployeActivation::getAccountInfo($telintaAccountres->getIAccount());
            $balanceres = $account_infores->account_info->balance;
        }
        $this->balance=$balance + $balancecb +$balanceres;
    }

    public function executeAppCode($request) {
        $this->employee = EmployeePeer::retrieveByPK($request->getParameter('id'));


        $c = new Criteria();
        $c->add(EmployeePeer::APP_CODE, NULL);
        $employees = EmployeePeer::doSelect($c);

        foreach ($employees as $employee) {

            $emplyid = $employee->getId();
            $emplycompanyid = $employee->getCompanyId();

            $appcode = $emplyid . "" . $emplycompanyid;

            $applen = strlen($appcode);
            if (isset($applen) && $applen == 2) {

                $appcode = "00" . $appcode;
            }
            if (isset($applen) && $applen == 3) {

                $appcode = "0" . $appcode;
            }

            //   echo " <br/>".$appcode;
            //  $employee->setId($emplyid);
            $employee->setAppCode($appcode);


            $employee->save();
        }

        return $this->redirect('employee/index');
    }

    public function executeAdd($request) {

       $this->companyval=$request->getParameter('company_id');
   
        $c = new Criteria();
        $this->companys = CompanyPeer::doSelect($c);

        $pr = new Criteria();
       $pr->add(ProductPeer::ID, 14);
        $this->products = ProductPeer::doSelect($pr);
    }

    public function executeSaveEmployee($request) {

        
  //$contrymobilenumber = $request->getParameter('country_code') . $request->getParameter('mobile_number');
  //$employeMobileNumber=$contrymobilenumber;


          if (substr($request->getParameter('mobile_number'),0, 1) == 0) {
               $mobileNo = substr($request->getParameter('mobile_number'), 1);
           }else{
               $mobileNo= $request->getParameter('mobile_number');
           }

      $c = new Criteria();
      $c->addAnd(CompanyPeer::ID, $request->getParameter('company_id'));
      $this->companys = CompanyPeer::doSelectOne($c);
      $companyCVR=$this->companys->getVatNo();
      $countryID=$this->companys->getCountryId();
      $companyCVRNumber=$companyCVR;
      $employee = new Employee();
      $c1 = new Criteria();
      $c1->addAnd(CountryPeer::ID, $countryID);
      $this->country = CountryPeer::doSelectOne($c1);
     $contrymobilenumber = $this->country->getCallingCode() . $mobileNo;
     $employeMobileNumber=$contrymobilenumber;

        if(!CompanyEmployeActivation::telintaRegisterEmployeeCB($employeMobileNumber, $this->companys)){
            $this->getUser()->setFlash('messageError', 'Employee Call Through account is not registered on Telinta please check email');
            $this->redirect('employee/add');
            die;
        }
        if(!CompanyEmployeActivation::telintaRegisterEmployeeCT($employeMobileNumber, $this->companys)){
            $this->getUser()->setFlash('messageError', 'Employee Call Back account is not registered on Telinta please check email');
            $this->redirect('employee/add');
            die;
        }

     
     $rtype=$request->getParameter('registration_type');
      if($rtype==1){
      ////////////////////////////////////////////////

        $this->getbalance = CompanyEmployeActivation::getBalance($this->companys);
        if($this->getbalance>40){
        $c = new Criteria();
                $c->setLimit(1);
                $c->add(SeVoipNumberPeer::IS_ASSIGNED, 0);

                if (SeVoipNumberPeer::doCount($c) < 10) {
                     emailLib::sendErrorInTelinta("Resenumber about to Finis", "Resenumbers in the landncall are lest then 10 . ");
                 }

                if (!$voip_customer = SeVoipNumberPeer::doSelectOne($c)){
                         emailLib::sendErrorInTelinta("Resenumber Finished", "Resenumbers in the landncall are finished. This error is faced by Employee id: " .$request->getParameter('id'));
                     $msg= "Resenummer is not activate";
                    //return false;
                }else{
                $voip_customer->setUpdatedAt(date('Y-m-d H:i:s'));
                $voip_customer->setCustomerId($contrymobilenumber);
                $voip_customer->setIsAssigned(1);
                $voip_customer->save();


                //--------------------------Telinta------------------/
                $getvoipInfo = new Criteria();
                $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $contrymobilenumber);
                $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                if (isset($getvoipInfos)) {
                    $voipnumbers = $getvoipInfos->getNumber();
                    $voipnumbers = substr($voipnumbers, 2);
                    $voip_customer = $getvoipInfos->getCustomerId();
                   

                    //$TelintaMobile = '46'.$this->customer->getMobileNumber();
                  

                    //This Condtion for if IC Active
                  
                    //------------------------------

                    
                     $getFirstnumberofMobile = substr($contrymobilenumber, 0, 1);     // bcdef
                    if ($getFirstnumberofMobile == 0) {
                        $TelintaMobile = substr($contrymobilenumber, 1);
                    }else{
                      $TelintaMobile= $contrymobilenumber;
                    }

                    

                    $telintaResenummerAccount=CompanyEmployeActivation::createReseNumberAccount($voipnumbers, $this->companys, $TelintaMobile);
                    if($telintaResenummerAccount){
                        $OpeningBalance=40;
                        $employee->setRegistrationType($request->getParameter('registration_type'));
                        //$resenummerCharge=file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=manual_charge&name=' . $voipnumbers . '&amount=40&customer='.$companyCVR);
                        CompanyEmployeActivation::charge($this->companys, $OpeningBalance);
                        $transaction = new CompanyTransaction();
                        $transaction->setAmount(-40);
                        $transaction->setCompanyId($request->getParameter('company_id'));
                        $transaction->setExtraRefill(-40);
                        $transaction->setTransactionStatusId(3);
                        $transaction->setPaymenttype(3);//Resenummer Charge
                        $transaction->setDescription('Resenummer Charge');
                        $transaction->save();
                  }
                    else{
                    
                        $getvoipInfos->setUpdatedAt(Null);
                        $getvoipInfos->setCustomerId(Null);
                        $getvoipInfos->setIsAssigned(0);
                        $getvoipInfos->save();
                        $employee->setRegistrationType(0);
                        $msg= "Resenummer is not activate";
                    }

                }}
      }else{

          $msg= "To activate Resenummer Refill account";
      }
      }
        
        $employee->setCompanyId($request->getParameter('company_id'));
        $employee->setFirstName($request->getParameter('first_name'));
        $employee->setLastName($request->getParameter('last_name'));
        $employee->setCountryCode($this->country->getCallingCode());
        $employee->setCountryMobileNumber($contrymobilenumber);
        $employee->setMobileNumber($request->getParameter('mobile_number'));
        $employee->setEmail($request->getParameter('email'));
        
        $employee->setProductId($request->getParameter('productid'));
       // $employee->setProductPrice($request->getParameter('price'));
        $employee->save();
        $this->getUser()->setFlash('messageAdd', 'Employee has been Add Sucessfully '.(isset($msg)?"and ".$msg:''));
        $this->redirect('employee/index?message=add');
    }

    public function executeUpdateEmployee(sfWebRequest $request) {


  //$contrymobilenumber = $request->getParameter('country_code') . $request->getParameter('mobile_number');
  //$employeMobileNumber=$contrymobilenumber;


   $c = new Criteria();

                $c->add(CompanyPeer::ID,$request->getParameter('company_id'));

                $compny=CompanyPeer::doSelectOne($c);

$companyCVR=$compny->getVatNo();
  $rtype=$request->getParameter('registration_type');

      $employee = EmployeePeer::retrieveByPk($request->getParameter('id'));
       $contrymobilenumber=$employee->getCountryMobileNumber();

      $c = new Criteria();
      $c->addAnd(CompanyPeer::ID, $employee->getCompanyId());
      $this->companys = CompanyPeer::doSelectOne($c);
      $companyCVR=$this->companys->getVatNo();
      $companyCVRNumber=$companyCVR;
 
            if($rtype==1){
      ////////////////////////////////////////////////
        $this->getbalance = CompanyEmployeActivation::getBalance($this->companys);
        if($this->getbalance>40){
        $c = new Criteria();
                $c->setLimit(1);
                $c->add(SeVoipNumberPeer::IS_ASSIGNED, 0);

                if (SeVoipNumberPeer::doCount($c) < 10) {
                     emailLib::sendErrorInTelinta("Resenumber about to Finis", "Resenumbers in the landncall are lest then 10 . ");
                    }
                if (!$voip_customer = SeVoipNumberPeer::doSelectOne($c)){
                   emailLib::sendErrorInTelinta("Resenumber Finished", "Resenumbers in the landncall are finished. This error is faced by Employee id: " .$request->getParameter('id'));
                   $msg= "Resenummer is not activate";
                }else{
                $voip_customer->setUpdatedAt(date('Y-m-d H:i:s'));
                $voip_customer->setCustomerId($contrymobilenumber);
                $voip_customer->setIsAssigned(1);
                $voip_customer->save();
                 $voip_customer->getNumber();
               
            
                //--------------------------Telinta------------------/
                $getvoipInfo = new Criteria();
                $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $contrymobilenumber);
                $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();

                  $getvoipInfos->getNumber();
              
                if (isset($getvoipInfos)) {
                    $voipnumbers = $getvoipInfos->getNumber();
                    $voipnumbers = substr($voipnumbers, 2);
                    $voip_customer = $getvoipInfos->getCustomerId();


                    //$TelintaMobile = '46'.$this->customer->getMobileNumber();


                    //This Condtion for if IC Active

                    //------------------------------


                     $getFirstnumberofMobile = substr($contrymobilenumber, 0, 1);     // bcdef
                    if ($getFirstnumberofMobile == 0) {
                        $TelintaMobile = substr($contrymobilenumber, 1);
                    }else{
                      $TelintaMobile= $contrymobilenumber;
                    }

                    $telintaResenummerAccount=CompanyEmployeActivation::createReseNumberAccount($voipnumbers, $this->companys, $TelintaMobile);
                    if($telintaResenummerAccount){

                        $OpeningBalance=40;
                        $employee->setRegistrationType($rtype);
                        //$resenummerCharge=file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=manual_charge&name=' . $voipnumbers . '&amount=40&customer='.$companyCVR);

                        CompanyEmployeActivation::charge($this->companys, $OpeningBalance);
                        $transaction = new CompanyTransaction();
                        $transaction->setAmount(-40);
                        $transaction->setCompanyId($employee->getCompanyId());
                        $transaction->setExtraRefill(-40);
                        $transaction->setTransactionStatusId(3);
                        $transaction->setPaymenttype(3);//Resenummer Charge
                        $transaction->setDescription('Resenummer Charge');
                        $transaction->save();

                        

                    }
                    else{
                        $getvoipInfos->setUpdatedAt(Null);
                        $getvoipInfos->setCustomerId(Null);
                        $getvoipInfos->setIsAssigned(0);
                        $getvoipInfos->save();
                        $employee->setRegistrationType(0);
                        $msg= "Resenummer is not activate";
                    }

                }
                }
                 }else{

                    $msg= "To activate Resenummer Refill account";
      }
      }




      // if($rtype==3){
        // $rtype=1;
       //}
       // $contrymobilenumber = $request->getParameter('country_code') . $request->getParameter('mobile_number');
        
      //  $employee->setCompanyId($request->getParameter('company_id'));
        $employee->setFirstName($request->getParameter('first_name'));
        $employee->setLastName($request->getParameter('last_name'));
       // $employee->setCountryCode($request->getParameter('country_code'));
       // $employee->setCountryMobileNumber($contrymobilenumber);
        $employee->setMobileNumber($request->getParameter('mobile_number'));
        $employee->setEmail($request->getParameter('email'));
   /*     $employee->setAppCode($request->getParameter('app_code'));
        $employee->setIsAppRegistered($request->getParameter('is_app_registered'));
        $employee->setPassword($request->getParameter('password'));*/
        //$employee->setRegistrationType($rtype);
        $employee->setProductId($request->getParameter('productid'));
      //  $employee->setProductPrice($request->getParameter('price'));
        $employee->setDeleted($request->getParameter('deleted'));
        $employee->save();
         $this->getUser()->setFlash('messageEdit', 'Employee has been edit Sucessfully '.(isset($msg)?"and ".$msg:''));
        //$this->message = "employee added successfully";
        $this->redirect('employee/index?message=edit');
       // return sfView::NONE;
    }

    public function executeDel(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $employeeid = $request->getParameter('id');
        $c = new Criteria();
        $c->add(EmployeePeer::ID, $employeeid);
        $employees = EmployeePeer::doSelectOne($c);
        $registration = $employees->getRegistrationType();
        //$mobileNumber=$employees->getCountryMobileNumber();
        $companyid=$request->getParameter('company_id');
        $contrymobilenumber=$employees->getCountryMobileNumber();
        $ct = new Criteria();
        $ct->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'a' . $contrymobilenumber);
        $ct->addAnd(TelintaAccountsPeer::STATUS, 3);
        $telintaAccount = TelintaAccountsPeer::doSelectOne($ct);
        if (!CompanyEmployeActivation::terminateAccount($telintaAccount)) {
            $this->getUser()->setFlash('messageEdit', 'Employee has not been deleted Sucessfully Error in Callthrough Account');
            if (isset($companyid) && $companyid != "") {
                $this->redirect('employee/index?company_id=' . $companyid . '&filter=filter');
            } else {
                $this->redirect('employee/index?message=edit');
            }
            return false;
        }
        $cb = new Criteria();
        $cb->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'cb' . $contrymobilenumber);
        $cb->addAnd(TelintaAccountsPeer::STATUS, 3);
        $telintaAccountcb = TelintaAccountsPeer::doSelectOne($cb);
        if (!CompanyEmployeActivation::terminateAccount($telintaAccountcb)) {
            $this->getUser()->setFlash('messageEdit', 'Employee has not been deleted Sucessfully Error in Call Back Account');
            if (isset($companyid) && $companyid != "") {
                $this->redirect('employee/index?company_id=' . $companyid . '&filter=filter');
            } else {
                $this->redirect('employee/index?message=edit');
            }
            return false;
        }
        /*$telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=a'.$contrymobilenumber.'&type=account');
         
            parse_str($telintaRegisterCus);
            if(isset($success) && $success!="OK"){
                emailLib::sendErrorInTelinta("Error in employee  delete account", 'We have faced an issue in employee deletion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=a'.$contrymobilenumber.'&type=account');
                $this->getUser()->setFlash('message', 'Employee has not been deleted Sucessfully! Error in Callthrough Account');
                if(isset($companyid) && $companyid!=""){$this->redirect('employee/index?company_id='.$companyid.'&filter=filter');}
                else{$this->redirect('employee/index');}
                return false;
            }
        $telintaRegisterCus1 = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=cb'.$contrymobilenumber.'&type=account');
        parse_str($telintaRegisterCus1);
            if(isset($success) && $success!="OK"){
                emailLib::sendErrorInTelinta("Error in employee  delete account", 'We have faced an issue in employee deletion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=cb'.$contrymobilenumber.'&type=account');
                $this->getUser()->setFlash('message', 'Employee has not been deleted Sucessfully! Error in Callback Account');
                if(isset($companyid) && $companyid!=""){$this->redirect('employee/index?company_id='.$companyid.'&filter=filter');}
                else{$this->redirect('employee/index');}
                return false;
            }*/
        $this->forward404Unless($employee = EmployeePeer::retrieveByPk($request->getParameter('id')), sprintf('Object employee does not exist (%s).', $request->getParameter('id')));


                $getvoipInfo = new Criteria();
                $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $contrymobilenumber);
                $getvoipInfo->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
                $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                if (isset($getvoipInfos)) {
                    $voipnumbers = $getvoipInfos->getNumber();
                    $voipnumbers = substr($voipnumbers, 2);

                   /* $telintaDeactivate = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=update&name=' . $voipnumbers . '&active=N&follow_me_number=' . $contrymobilenumber . '&type=account');
                    parse_str($telintaDeactivate);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in employee  delete account", 'We have faced an issue in employee deletion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=update&name=' . $voipnumbers . '&active=N&follow_me_number=' . $contrymobilenumber . '&type=account');
                        $this->getUser()->setFlash('message', 'Employee has not been deleted Sucessfully! Error in Deactivate Resenummer');
                        if(isset($companyid) && $companyid!=""){$this->redirect('employee/index?company_id='.$companyid.'&filter=filter');}
                        else{$this->redirect('employee/index');}
                        return false;
                    }
                    $telintaDeleteResenummer = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name='.$voipnumbers.'&type=account');
                    parse_str($telintaDeleteResenummer);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in employee  delete account", 'We have faced an issue in employee deletion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name='.$voipnumbers.'&type=account');
                        $this->getUser()->setFlash('message', 'Employee has not been deleted Sucessfully! Error in delete resenummer');
                        if(isset($companyid) && $companyid!=""){$this->redirect('employee/index?company_id='.$companyid.'&filter=filter');}
                        else{$this->redirect('employee/index');}
                        return false;
                    }*/

                    $res = new Criteria();
                    $res->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
                    $res->addAnd(TelintaAccountsPeer::STATUS, 3);
                    $telintaAccountres = TelintaAccountsPeer::doSelectOne($res);
                    if (!CompanyEmployeActivation::terminateAccount($telintaAccountres)) {
                        $this->getUser()->setFlash('messageEdit', 'Employee has not been deleted Sucessfully Error in Resenummer Account');
                        if (isset($companyid) && $companyid != "") {
                            $this->redirect('employee/index?company_id=' . $companyid . '&filter=filter');
                        } else {
                            $this->redirect('employee/index?message=edit');
                        }
                        return false;
                    }
                    $getvoipInfos->setUpdatedAt(Null);
                    $getvoipInfos->setCustomerId(Null);
                    $getvoipInfos->setIsAssigned(0);
                    $getvoipInfos->save();

                 }

                
        $employee->delete();
        $this->getUser()->setFlash('message', 'Employee has been deleted Sucessfully');
        if(isset($companyid) && $companyid!=""){$this->redirect('employee/index?company_id='.$companyid.'&filter=filter');}
        else{$this->redirect('employee/index');}
    }

    public function executeUsage($request) {
         $this->employee = EmployeePeer::retrieveByPK($request->getParameter('employee_id'));

        $c = new Criteria();
        $c->addAnd(CompanyPeer::ID, $this->employee->getCompanyId());
        $this->companys = CompanyPeer::doSelectOne($c);

        $tomorrow1 = mktime(0,0,0,date("m"),date("d")-15,date("Y"));
        $fromdate=date("Y-m-d", $tomorrow1);
        $tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        $todate=date("Y-m-d", $tomorrow);

        $mobilenumber = $this->employee->getCountryMobileNumber();
        $ct = new Criteria();
        $ct->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'a'.$mobilenumber);
        $ct->addAnd(TelintaAccountsPeer::STATUS, 3);
        $telintaAccount = TelintaAccountsPeer::doSelectOne($ct);
        $this->callHistory = CompanyEmployeActivation::getAccountCallHistory($telintaAccount->getIAccount(), $fromdate, $todate);
        
        $cb = new Criteria();
        $cb->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'cb'.$mobilenumber);
        $cb->addAnd(TelintaAccountsPeer::STATUS, 3);
        $telintaAccountcb = TelintaAccountsPeer::doSelectOne($cb);
        $this->callHistorycb = CompanyEmployeActivation::getAccountCallHistory($telintaAccountcb->getIAccount(), $fromdate, $todate);

        $getvoipInfo = new Criteria();
        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $contrymobilenumber);
        $getvoipInfo->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
        if (isset($getvoipInfos)) {
            $voipnumbers = $getvoipInfos->getNumber();
            $voipnumbers = substr($voipnumbers, 2);

            $res = new Criteria();
            $res->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
            $res->addAnd(TelintaAccountsPeer::STATUS, 3);
            $telintaAccountres = TelintaAccountsPeer::doSelectOne($res);
            $this->callHistoryres = CompanyEmployeActivation::getAccountCallHistory($telintaAccountres->getIAccount(), $fromdate, $todate);
        }
       
    }

     public function executeMobile(sfWebRequest $request)
	{

        $c = new Criteria();
        $mobile_no=$_POST['mobile_no'];
        $c->add(EmployeePeer::MOBILE_NUMBER,  $mobile_no);
            if(EmployeePeer::doSelectOne($c)){

                echo "no";
            }else{
               echo "yes";
            }
        }

}
