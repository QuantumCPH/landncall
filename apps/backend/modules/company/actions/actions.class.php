<?php

/**
 * company actions.
 *
 * @package    zapnacrm
 * @subpackage company
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 5125 2007-09-16 00:53:55Z dwhittle $
 */
class companyActions extends autocompanyActions
{

	
	public function executeCountrycity($request){
		
		$this->country_id = $request->getParameter('country_id');
		$this->city_id = $request->getParameter('city_id');
		
		
		$c = new Criteria();
		$c->addAscendingOrderByColumn('name');
		$Lcountries = CountryPeer::doSelect($c);
		
		$c = new Criteria();
		
		if (!$this->country_id)
		{
			/*
			if (($_country_id = $this->__getDefaultCountry()))
			{
				$c->add(CityPeer::COUNTRY_ID, $_country_id->getId());
			}
			*/
			$this->country_id = $this->__getDefaultCountry()->getId();	
		}
			$c->add(CityPeer::COUNTRY_ID, $this->country_id);
                        $c->addAscendingOrderByColumn('name');
			$Lcities = CityPeer::doSelect($c);
			
		
		
		$cities_List = '';
		foreach($Lcountries as $country)
		{
			$countries_List[''.$country->getId()] = $country->getName();
		}
		
		if(isset($Lcities)){
			
			foreach($Lcities as $city)
			{
				$cities_List[''.$city->getId()] = $city->getName();
			}
		}
		
		$this->cities_list = $cities_List;
		$this->countries_list = $countries_List;
		
		if (($country = $this->__getDefaultCountry())!=null)
		{
			$this->default_country_id = $country->getId();
		}
		else
		{
			$this->default_country_id = null;
		}
		
		$this->setLayout(false);
		
	}
	
	public function executeView($request){
		$this->company = CompanyPeer::retrieveByPK($request->getParameter('id'));
	}
	
	function __getDefaultCountry()
	{
		$c = new Criteria();
		$c->add(CountryPeer::CODE, 'DK');
		$country = CountryPeer::doSelectOne($c);
		
		return $country;
	}
	
	protected function addFiltersCriteria ($c)
	{

	  if (isset($this->filters['id']) && $this->filters['id'] !== '')
	  {
			$c->add(CompanyPeer::ID, $this->filters['id']);
	  }

	  parent::addFiltersCriteria($c);

		
	}
}
