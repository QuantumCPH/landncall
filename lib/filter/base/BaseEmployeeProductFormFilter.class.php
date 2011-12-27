<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * EmployeeProduct filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseEmployeeProductFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'employee_id' => new sfWidgetFormFilterInput(),
      'product_id'  => new sfWidgetFormFilterInput(),
      'quantity'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'employee_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'product_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'quantity'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('employee_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmployeeProduct';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'employee_id' => 'Number',
      'product_id'  => 'Number',
      'quantity'    => 'Number',
    );
  }
}
