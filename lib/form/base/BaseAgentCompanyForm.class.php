<?php

/**
 * AgentCompany form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseAgentCompanyForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                          => new sfWidgetFormInputHidden(),
      'name'                        => new sfWidgetFormInput(),
      'cvr_number'                  => new sfWidgetFormInput(),
      'ean_number'                  => new sfWidgetFormInput(),
      'address'                     => new sfWidgetFormInput(),
      'post_code'                   => new sfWidgetFormInput(),
      'country_id'                  => new sfWidgetFormPropelChoice(array('model' => 'Country', 'add_empty' => true)),
      'city_id'                     => new sfWidgetFormPropelChoice(array('model' => 'City', 'add_empty' => true)),
      'contact_name'                => new sfWidgetFormInput(),
      'email'                       => new sfWidgetFormInput(),
      'mobile_number'               => new sfWidgetFormInput(),
      'head_phone_number'           => new sfWidgetFormInput(),
      'fax_number'                  => new sfWidgetFormInput(),
      'website'                     => new sfWidgetFormInput(),
      'status_id'                   => new sfWidgetFormPropelChoice(array('model' => 'Status', 'add_empty' => true)),
      'company_type_id'             => new sfWidgetFormPropelChoice(array('model' => 'CompanyType', 'add_empty' => true)),
      'product_detail'              => new sfWidgetFormInput(),
      'commission_period_id'        => new sfWidgetFormPropelChoice(array('model' => 'CommissionPeriod', 'add_empty' => true)),
      'account_manager_id'          => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
      'created_at'                  => new sfWidgetFormDateTime(),
      'agent_commission_package_id' => new sfWidgetFormPropelChoice(array('model' => 'AgentCommissionPackage', 'add_empty' => false)),
      'sms_code'                    => new sfWidgetFormInput(),
      'is_prepaid'                  => new sfWidgetFormInputCheckbox(),
      'balance'                     => new sfWidgetFormInput(),
      'invoice_method_id'           => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                          => new sfValidatorPropelChoice(array('model' => 'AgentCompany', 'column' => 'id', 'required' => false)),
      'name'                        => new sfValidatorString(array('max_length' => 255)),
      'cvr_number'                  => new sfValidatorInteger(),
      'ean_number'                  => new sfValidatorInteger(array('required' => false)),
      'address'                     => new sfValidatorString(array('max_length' => 255)),
      'post_code'                   => new sfValidatorInteger(),
      'country_id'                  => new sfValidatorPropelChoice(array('model' => 'Country', 'column' => 'id', 'required' => false)),
      'city_id'                     => new sfValidatorPropelChoice(array('model' => 'City', 'column' => 'id', 'required' => false)),
      'contact_name'                => new sfValidatorString(array('max_length' => 150)),
      'email'                       => new sfValidatorString(array('max_length' => 255)),
      'mobile_number'               => new sfValidatorString(array('max_length' => 255)),
      'head_phone_number'           => new sfValidatorInteger(),
      'fax_number'                  => new sfValidatorInteger(),
      'website'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status_id'                   => new sfValidatorPropelChoice(array('model' => 'Status', 'column' => 'id', 'required' => false)),
      'company_type_id'             => new sfValidatorPropelChoice(array('model' => 'CompanyType', 'column' => 'id', 'required' => false)),
      'product_detail'              => new sfValidatorInteger(array('required' => false)),
      'commission_period_id'        => new sfValidatorPropelChoice(array('model' => 'CommissionPeriod', 'column' => 'id', 'required' => false)),
      'account_manager_id'          => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
      'created_at'                  => new sfValidatorDateTime(array('required' => false)),
      'agent_commission_package_id' => new sfValidatorPropelChoice(array('model' => 'AgentCommissionPackage', 'column' => 'id')),
      'sms_code'                    => new sfValidatorString(array('max_length' => 4, 'required' => false)),
      'is_prepaid'                  => new sfValidatorBoolean(array('required' => false)),
      'balance'                     => new sfValidatorNumber(array('required' => false)),
      'invoice_method_id'           => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('agent_company[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AgentCompany';
  }


}
