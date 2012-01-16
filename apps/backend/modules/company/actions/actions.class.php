<?php
require_once(sfConfig::get('sf_lib_dir').'/company_employe_activation.class.php');
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');
/**
 * autoCompany actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage autoCompany
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: actions.class.php 16948 2009-04-03 15:52:30Z fabien $
 */
class companyActions extends sfActions {

    public function executeCountrycity(sfWebRequest $request) {

        $this->country_id = $request->getParameter('country_id');

        $c = new Criteria();
        $c->add(CityPeer::COUNTRY_ID, $this->country_id);
        $c->addAscendingOrderByColumn('name');
        $Lcities = CityPeer::doSelect($c);
        $cities_List = $Lcities;
        foreach ($Lcities as $city) {

            $cities_List[$city->getId()] = $city->getName();
        }
        $this->cities_list = $cities_List;
        $this->setLayout(false);
    }

    public function executeIndex(sfWebRequest $request) {
        return $this->forward('company', 'list');
    }

    public function executeList($request) {


        $this->processSort();

        $this->processFilters();

        $this->filters = $this->getUser()->getAttributeHolder()->getAll('sf_admin/company/filters');

        // pager
        $this->pager = new sfPropelPager('Company', 1000);
        $c = new Criteria();
        $this->addSortCriteria($c);
        $this->addFiltersCriteria($c);
        $this->pager->setCriteria($c);
        $this->pager->setPage($this->getRequestParameter('page', $this->getUser()->getAttribute('page', 1, 'sf_admin/company')));
        $this->pager->init();

        // save page
        if ($this->getRequestParameter('page')) {
            $this->getUser()->setAttribute('page', $this->getRequestParameter('page'), 'sf_admin/company');
        }
    }

    public function executeCreate(sfWebRequest $request) {
        return $this->forward('company', 'edit');
    }

    public function executeSave(sfWebRequest $request) {
        return $this->forward('company', 'edit');
    }

    public function executeDeleteSelected(sfWebRequest $request) {
        $this->selectedItems = $this->getRequestParameter('sf_admin_batch_selection', array());

        try {
            foreach (CompanyPeer::retrieveByPks($this->selectedItems) as $object) {
                $object->delete();
            }
        } catch (PropelException $e) {
            $request->setError('delete', 'Could not delete the selected Companys. Make sure they do not have any associated items.');
            return $this->forward('company', 'list');
        }

        return $this->redirect('company/list');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->company = $this->getCompanyOrCreate();

        if ($request->isMethod('post')) {
            $this->updateCompanyFromRequest();

            try {
                $this->saveCompany($this->company);
            } catch (PropelException $e) {
                $request->setError('edit', 'Could not save the edited Company.');
                return $this->forward('company', 'list');
            }

            $this->getUser()->setFlash('notice', 'Your modifications have been saved');

            if ($this->getRequestParameter('save_and_add')) {
                return $this->redirect('company/create');
            } else if ($this->getRequestParameter('save_and_list')) {
                return $this->redirect('company/list');
            } else {
                return $this->redirect('company/edit?id=' . $this->company->getId());
            }
        } else {
            $this->labels = $this->getLabels();
        }
    }

    public function executeDelete(sfWebRequest $request) {
        $this->company = CompanyPeer::retrieveByPk($this->getRequestParameter('id'));
        $this->forward404Unless($this->company);

        try {
            $this->deleteCompany($this->company);
        } catch (PropelException $e) {
            $request->setError('delete', 'Could not delete the selected Company. Make sure it does not have any associated items.');
            return $this->forward('company', 'list');
        }

        $currentFile = sfConfig::get('sf_upload_dir') . "//" . $this->company->getFilePath();
        if (is_file($currentFile)) {
            unlink($currentFile);
        }

        return $this->redirect('company/list');
    }

    public function handleErrorEdit() {
        $this->preExecute();
        $this->company = $this->getCompanyOrCreate();
        $this->updateCompanyFromRequest();

        $this->labels = $this->getLabels();

        return sfView::SUCCESS;
    }

    protected function saveCompany($company) {
        $companyData = $this->getRequestParameter('company');

        $res = CompanyEmployeActivation::telintaRegisterCompany($companyData['vat_no']);
        $company->isNew().":".$res; 

        if($company->isNew()&& $res){

            var_dump($companyData);
            var_dump($company);
            $company->save();

            $transaction = new CompanyTransaction();
            $transaction->setAmount(5000);
            $transaction->setCompanyId($company->getId());
            $transaction->setExtraRefill(5000);
            $transaction->setTransactionStatusId(3);
            $transaction->setPaymenttype(1);//Registered
            $transaction->setDescription('Company Registered');
            $transaction->save();

        }elseif(!$company->isNew()){
            $company->save();
        }elseif(!$res){
            throw new PropelException("You cannot save an object that has been deleted.");
        }

    }

    protected function deleteCompany($company) {
        $company->delete();
    }

    protected function updateCompanyFromRequest() {
        $company = $this->getRequestParameter('company');


        if (isset($company['name'])) {
            $this->company->setName($company['name']);
        }
        if (isset($company['vat_no'])) {
            $this->company->setVatNo($company['vat_no']);
        }
        if (isset($company['address'])) {
            $this->company->setAddress($company['address']);
        }
        if (isset($company['post_code'])) {
            $this->company->setPostCode($company['post_code']);
        }
        if (isset($company['city_id'])) {
            $this->company->setCityId($company['city_id'] ? $company['city_id'] : null);
        }
        if (isset($company['country_id'])) {
            $this->company->setCountryId($company['country_id'] ? $company['country_id'] : null);
        }
        if (isset($company['contact_name'])) {
            $this->company->setContactName($company['contact_name']);
        }
        if (isset($company['email'])) {
            $this->company->setEmail($company['email']);
        }
        if (isset($company['head_phone_number'])) {
            $this->company->setHeadPhoneNumber($company['head_phone_number']);
        }
        if (isset($company['fax_number'])) {
            $this->company->setFaxNumber($company['fax_number']);
        }
        if (isset($company['website'])) {
            $this->company->setWebsite($company['website']);
        }
        if (isset($company['status_id'])) {
            $this->company->setStatusId($company['status_id'] ? $company['status_id'] : null);
        }
        if (isset($company['company_size_id'])) {
            $this->company->setCompanySizeId($company['company_size_id'] ? $company['company_size_id'] : null);
        }
        if (isset($company['company_type_id'])) {
            $this->company->setCompanyTypeId($company['company_type_id'] ? $company['company_type_id'] : null);
        }
        if (isset($company['customer_type_id'])) {
            $this->company->setCustomerTypeId($company['customer_type_id'] ? $company['customer_type_id'] : null);
        }
        if (isset($company['invoice_method_id'])) {
            $this->company->setInvoiceMethodId($company['invoice_method_id'] ? $company['invoice_method_id'] : null);
        }
        if (isset($company['agent_company_id'])) {
            $this->company->setAgentCompanyId($company['agent_company_id'] ? $company['agent_company_id'] : null);
        }
        if (isset($company['registration_date'])) {
            if ($company['registration_date']) {
                try {
                    $dateFormat = new sfDateFormat($this->getUser()->getCulture());
                    if (!is_array($company['registration_date'])) {
                        $value = $dateFormat->format($company['registration_date'], 'I', $dateFormat->getInputPattern('g'));
                    } else {
                        $value_array = $company['registration_date'];
                        $value = $value_array['year'] . '-' . $value_array['month'] . '-' . $value_array['day'] . (isset($value_array['hour']) ? ' ' . $value_array['hour'] . ':' . $value_array['minute'] . (isset($value_array['second']) ? ':' . $value_array['second'] : '') : '');
                    }
                    $this->company->setRegistrationDate($value);
                } catch (sfException $e) {
                    // not a date
                }
            } else {
                $this->company->setRegistrationDate(null);
            }
        }
        if (isset($company['created_at'])) {
            if ($company['created_at']) {
                try {
                    $dateFormat = new sfDateFormat($this->getUser()->getCulture());
                    if (!is_array($company['created_at'])) {
                        $value = $dateFormat->format($company['created_at'], 'I', $dateFormat->getInputPattern('g'));
                    } else {
                        $value_array = $company['created_at'];
                        $value = $value_array['year'] . '-' . $value_array['month'] . '-' . $value_array['day'] . (isset($value_array['hour']) ? ' ' . $value_array['hour'] . ':' . $value_array['minute'] . (isset($value_array['second']) ? ':' . $value_array['second'] : '') : '');
                    }
                    $this->company->setCreatedAt($value);
                } catch (sfException $e) {
                    // not a date
                }
            } else {
                $this->company->setCreatedAt(null);
            }
        }
        $currentFile = sfConfig::get('sf_upload_dir') . "//" . $this->company->getFilePath();
        if (!$this->getRequest()->hasErrors() && isset($company['file_path_remove'])) {
            $this->company->setFilePath('');
            if (is_file($currentFile)) {
                unlink($currentFile);
            }
        }

        if (!$this->getRequest()->hasErrors() && $this->getRequest()->getFileSize('company[file_path]')) {
            $fileName = md5($this->getRequest()->getFileName('company[file_path]') . time() . rand(0, 99999));
            $ext = $this->getRequest()->getFileExtension('company[file_path]');
            if (is_file($currentFile)) {
                unlink($currentFile);
            }
            $this->getRequest()->moveFile('company[file_path]', sfConfig::get('sf_upload_dir') . "//" . $fileName . $ext);
            $this->company->setFilePath($fileName . $ext);
        }
    }

    protected function getCompanyOrCreate($id = 'id') {
        if ($this->getRequestParameter($id) === ''
                || $this->getRequestParameter($id) === null) {
            $company = new Company();
        } else {
            $company = CompanyPeer::retrieveByPk($this->getRequestParameter($id));

            $this->forward404Unless($company);
        }

        return $company;
    }

    protected function processFilters() {
        if ($this->getRequest()->hasParameter('filter')) {
            $this->getUser()->getAttributeHolder()->removeNamespace('sf_admin/company/filters');

            $filters = $this->getRequestParameter('filters');
            if (is_array($filters)) {
                $this->getUser()->getAttributeHolder()->removeNamespace('sf_admin/company');
                $this->getUser()->getAttributeHolder()->removeNamespace('sf_admin/company/filters');
                $this->getUser()->getAttributeHolder()->add($filters, 'sf_admin/company/filters');
            }
        }
    }

    protected function processSort() {
        if ($this->getRequestParameter('sort')) {
            $this->getUser()->setAttribute('sort', $this->getRequestParameter('sort'), 'sf_admin/company/sort');
            $this->getUser()->setAttribute('type', $this->getRequestParameter('type', 'asc'), 'sf_admin/company/sort');
        }

        if (!$this->getUser()->getAttribute('sort', null, 'sf_admin/company/sort')) {
            
        }
    }

    protected function addFiltersCriteria($c) {
        if (isset($this->filters['company_name_is_empty'])) {
            $criterion = $c->getNewCriterion(CompanyPeer::COMPANY_NAME, '');
            $criterion->addOr($c->getNewCriterion(CompanyPeer::COMPANY_NAME, null, Criteria::ISNULL));
            $c->add($criterion);
        } else if (isset($this->filters['company_name']) && $this->filters['company_name'] !== '') {
            $c->add(CompanyPeer::COMPANY_NAME, $this->filters['company_name']);
        }
        if (isset($this->filters['vat_no_is_empty'])) {
            $criterion = $c->getNewCriterion(CompanyPeer::VAT_NO, '');
            $criterion->addOr($c->getNewCriterion(CompanyPeer::VAT_NO, null, Criteria::ISNULL));
            $c->add($criterion);
        } else if (isset($this->filters['vat_no']) && $this->filters['vat_no'] !== '') {
            $c->add(CompanyPeer::VAT_NO, $this->filters['vat_no']);
        }
    }

    protected function addSortCriteria($c) {
        if ($sort_column = $this->getUser()->getAttribute('sort', null, 'sf_admin/company/sort')) {
            // camelize lower case to be able to compare with BasePeer::TYPE_PHPNAME translate field name
            $sort_column = CompanyPeer::translateFieldName(sfInflector::camelize(strtolower($sort_column)), BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME);
            if ($this->getUser()->getAttribute('type', null, 'sf_admin/company/sort') == 'asc') {

                $c->addAscendingOrderByColumn($sort_column);
            } else {
                $c->addDescendingOrderByColumn($sort_column);
            }
        }
    }

    protected function getLabels() {
        return array(
            'company{name}' => 'Name:',
            'company{vat_no}' => 'Vat no:',
            'company{address}' => 'Address:',
            'company{post_code}' => 'Post code:',
            'company{city_id}' => 'City:',
            'company{country_id}' => 'Country:',
            'company{contact_name}' => 'Contact name:',
            'company{email}' => 'Email:',
            'company{head_phone_number}' => 'Head phone number:',
            'company{fax_number}' => 'Fax number:',
            'company{website}' => 'Website:',
            'company{status_id}' => 'Status:',
            'company{company_size_id}' => 'Company size:',
            'company{company_type_id}' => 'Company type:',
            'company{customer_type_id}' => 'Customer type:',
            'company{invoice_method_id}' => 'Invoice method:',
            'company{agent_company_id}' => 'Agent company:',
            'company{registration_date}' => 'Registration date:',
            'company{created_at}' => 'Created at:',
            'company{file_path}' => 'Registration Doc:',
        );
    }

    public function executeView($request) {
        $this->company = CompanyPeer::retrieveByPK($request->getParameter('id'));
    }

    public function executeUsage($request) {
        $this->company = CompanyPeer::retrieveByPK($request->getParameter('company_id'));
    }

    public function executeRefill(sfWebRequest $request)
{

        $c = new Criteria();
        $this->companys = CompanyPeer::doSelect($c);
        if ($request->isMethod('post')){

            $company_id = $request->getParameter('company_id');
            $refill_amount = $request->getParameter('refill');

            $c1 = new Criteria();
            $c1->addAnd(CompanyPeer::ID, $company_id);
            $this->company = CompanyPeer::doSelectOne($c1);
            $companyCVR=$this->company->getVatNo();

            $transaction = new CompanyTransaction();
            $transaction->setAmount($refill_amount);
            $transaction->setCompanyId($company_id);
            $transaction->setExtraRefill($refill_amount);
            $transaction->setTransactionStatusId(1);
            $transaction->setPaymenttype(2);//Refill
            $transaction->setDescription('Company Refill');
            $transaction->save();
          
            if($companyCVR!=''){
                $telintaRefillcustomer = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$companyCVR.'&amount='.$refill_amount.'&type=customer');

                sleep(0.5);

                if(!$telintaRefillcustomer){
                   emailLib::sendErrorInTelinta("Error in B2b company Refill", "Unable to call. We have faced an issue in company refill on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$companyCVR.'&amount='.$refill_amount.'&type=customer. <br/> Please Investigate.");
                   $this->getUser()->setFlash('message', 'Error in B2B Company Refill');
                   $this->redirect('company/paymenthistory');
                   return false;
                }
                parse_str($telintaRefillcustomer, $success);
                if(isset($success['success']) && $success['success']!="OK"){
                    emailLib::sendErrorInTelinta("Error in B2b company Refill", "Unable to call. We have faced an issue in company refill on telinta. this is the error on the following url https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=recharge&name='.$companyCVR.'&amount='.$refill_amount.'&type=customer. <br/> Please Investigate.");
                    $this->getUser()->setFlash('message', 'Error in B2B Company Refill');
                    $this->redirect('company/paymenthistory');
                    return false;
                }

                    $transaction->setTransactionStatusId(3);
                    $transaction->save();
                    $this->getUser()->setFlash('message', 'B2B Company Refill Successfully');
                    $this->redirect('company/paymenthistory');
            }else{

                $this->getUser()->setFlash('message', 'Please Select B2B Company');
                
            }
                    //$telintaAddAccount='success=OK&Amount=$amount{$cust_info->{iso_4217}}';
                    //parse_str($telintaAddAccount, $success);print_r($success);echo $success['success'];

        }
}

public function executePaymenthistory(sfWebRequest $request)
	{

        $c = new Criteria();
        $companyid=$request->getParameter('company_id');
        $this->companyval=$companyid;
        $c->add(CompanyTransactionPeer::TRANSACTION_STATUS_ID,  3);
        if (isset($companyid) && $companyid != '') {
        $c->addAnd(CompanyTransactionPeer::COMPANY_ID,  $companyid);

        }
        $this->transactions = CompanyTransactionPeer::doSelect($c);

	}

        public function executeVat(sfWebRequest $request)
	{

        $c = new Criteria();
        $vat_no=$_POST['vat_no'];
        $c->add(CompanyPeer::VAT_NO,  $vat_no);
            if(CompanyPeer::doSelectOne($c)){

                echo "no";
            }else{
               echo "yes";
            }
        }

}
