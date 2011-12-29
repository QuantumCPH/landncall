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


        $c = new Criteria();
        $this->companys = CompanyPeer::doSelect($c);

        $pr = new Criteria();
      //  $pr->add(ProductPeer::IS_IN_ZAPNA, 1);
        $this->products = ProductPeer::doSelect($pr);
    }

    public function executeSaveEmployee($request) {

        
  $contrymobilenumber = $request->getParameter('country_code') . $request->getParameter('mobile_number');
  $employeMobileNumber=$contrymobilenumber;


      $c = new Criteria();
       $c->addAnd(CompanyPeer::ID, $request->getParameter('company_id'));
      $this->companys = CompanyPeer::doSelectOne($c);
      $companyCVR=$this->companys->getVatNo();
      $companyCVRNumber=$companyCVR;
    
      
    
     

        if(!CompanyEmployeActivation::telintaRegisterEmployee($employeMobileNumber, $companyCVRNumber)){
                 $this->message = "employee added successfully";
            $this->redirect('employee/add?message=error');
die;
        }

     
     $rtype=$request->getParameter('registration_type');
      if($rtype==1){
      ////////////////////////////////////////////////
        $c = new Criteria();
                $c->setLimit(1);
                $c->add(SeVoipNumberPeer::IS_ASSIGNED, 0);

                if (!$voip_customer = SeVoipNumberPeer::doSelectOne($c))
                    return false;

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

                    
                    $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=' . $voipnumbers . '&customer=' . $companyCVR . '&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=' . $TelintaMobile . '&billing_model=1&password=asdf1asd');

                       if(!$telintaAddAccount){
                       emailLib::sendErrorInTelinta("Error in B2b employee  Resenmuber registration", "We have faced an issue in employee Resenmuber registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=". $voipnumbers ."&customer=". $companyCVR ."&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=". $TelintaMobile ."&billing_model=1&password=asdf1asd. <br/> Please Investigate.");

                    }
                    parse_str($telintaAddAccount);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in B2b employee  Resenmuber registration", "We have faced an issue in employee Resenmuber registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=". $voipnumbers ."&customer=". $companyCVR ."&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=". $TelintaMobile ."&billing_model=1&password=asdf1asd. <br/> Please Investigate.");
                     
                    }




                }
      }
        $employee = new Employee();
        $employee->setCompanyId($request->getParameter('company_id'));
        $employee->setFirstName($request->getParameter('first_name'));
        $employee->setLastName($request->getParameter('last_name'));
        $employee->setCountryCode($request->getParameter('country_code'));
        $employee->setCountryMobileNumber($contrymobilenumber);
        $employee->setMobileNumber($request->getParameter('mobile_number'));
        $employee->setEmail($request->getParameter('email'));
           $employee->setRegistrationType($request->getParameter('registration_type'));
        $employee->setProductId($request->getParameter('productid'));
        $employee->setProductPrice($request->getParameter('price'));
        $employee->save();
        $this->message = "employee added successfully";
        $this->redirect('employee/index?message=add');
    }

    public function executeUpdateEmployee(sfWebRequest $request) {


  $contrymobilenumber = $request->getParameter('country_code') . $request->getParameter('mobile_number');
  $employeMobileNumber=$contrymobilenumber;

  $rtype=$request->getParameter('registration_type');
            if($rtype==1){
      ////////////////////////////////////////////////
        $c = new Criteria();
                $c->setLimit(1);
                $c->add(SeVoipNumberPeer::IS_ASSIGNED, 0);

                if (!$voip_customer = SeVoipNumberPeer::doSelectOne($c))
                    return false;

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


                    $telintaAddAccount = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=' . $voipnumbers . '&customer=' . $companyCVR . '&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=' . $TelintaMobile . '&billing_model=1&password=asdf1asd');

                       if(!$telintaAddAccount){
                       emailLib::sendErrorInTelinta("Error in B2b employee  Resenmuber registration", "We have faced an issue in employee Resenmuber registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=". $voipnumbers ."&customer=". $companyCVR ."&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=". $TelintaMobile ."&billing_model=1&password=asdf1asd. <br/> Please Investigate.");

                    }
                    parse_str($telintaAddAccount);
                    if(isset($success) && $success!="OK"){
                        emailLib::sendErrorInTelinta("Error in B2b employee  Resenmuber registration", "We have faced an issue in employee Resenmuber registrtion on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=activate&name=". $voipnumbers ."&customer=". $companyCVR ."&opening_balance=0&credit_limit=&product=YYYLandncall_Forwarding&outgoing_default_r_r=2034&activate_follow_me=Yes&follow_me_number=". $TelintaMobile ."&billing_model=1&password=asdf1asd. <br/> Please Investigate.");

                    }




                }
      }



        $contrymobilenumber = $request->getParameter('country_code') . $request->getParameter('mobile_number');
        $employee = EmployeePeer::retrieveByPk($request->getParameter('id'));
      //  $employee->setCompanyId($request->getParameter('company_id'));
        $employee->setFirstName($request->getParameter('first_name'));
        $employee->setLastName($request->getParameter('last_name'));
        $employee->setCountryCode($request->getParameter('country_code'));
        $employee->setCountryMobileNumber($contrymobilenumber);
        $employee->setMobileNumber($request->getParameter('mobile_number'));
        $employee->setEmail($request->getParameter('email'));
   /*     $employee->setAppCode($request->getParameter('app_code'));
        $employee->setIsAppRegistered($request->getParameter('is_app_registered'));
        $employee->setPassword($request->getParameter('password'));*/
        $employee->setRegistrationType($request->getParameter('registration_type'));
        $employee->setProductId($request->getParameter('productid'));
        $employee->setProductPrice($request->getParameter('price'));
        $employee->setDeleted($request->getParameter('deleted'));
        $employee->save();
        $this->message = "employee updated successfully";
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
        $mobileNumber=$employees->getCountryMobileNumber();
        $telintaRegisterCus = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=a'.$mobileNumber.'&type=account');
        $telintaRegisterCus1 = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=delete&name=cb'.$mobileNumber.'&type=account');
        $this->forward404Unless($employee = EmployeePeer::retrieveByPk($request->getParameter('id')), sprintf('Object employee does not exist (%s).', $request->getParameter('id')));
        $employee->delete();
        $this->redirect('employee/index');
    }

}
