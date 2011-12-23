<?php

/**
 * employee actions.
 *
 * @package    zapnacrm
 * @subpackage employee
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 5125 2007-09-16 00:53:55Z dwhittle $
 */
class employeeActions extends autoemployeeActions
{
	protected function addFiltersCriteria ($c)
	{

	  if (isset($this->filters['vat_no']) && $this->filters['vat_no'] !== '')
	  {
			$c->add(CompanyPeer::VAT_NO, strtr($this->filters['vat_no'], '*', '%'), Criteria::LIKE);
			$c->addJoin(CompanyPeer::ID, EmployeePeer::COMPANY_ID);
			
			$this->filters['company_id'] = '';
	  }
	  else
	  {
	  	parent::addFiltersCriteria($c);
	  }

	    //$c->add(CompanyPeer::VAT_NO, strtr($this->filters['vat_no'], '*', '%'), Criteria::LIKE);
	    //$c->addJoin(CompanyPeer::ID, EmployeePeer::COMPANY_ID);
	    
	    //$tmp = $this->filters['vat_no'];
		
		
	}
	
	public function executeView($request){
		$this->employee = EmployeePeer::retrieveByPK($request->getParameter('id'));
	}
}
