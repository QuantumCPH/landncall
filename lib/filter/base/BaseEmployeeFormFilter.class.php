<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Employee filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseEmployeeFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'company_id'    => new sfWidgetFormPropelChoice(array('model' => 'Company', 'add_empty' => true)),
      'first_name'    => new sfWidgetFormFilterInput(),
      'last_name'     => new sfWidgetFormFilterInput(),
      'email'         => new sfWidgetFormFilterInput(),
      'mobile_model'  => new sfWidgetFormFilterInput(),
      'mobile_number' => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'company_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Company', 'column' => 'id')),
      'first_name'    => new sfValidatorPass(array('required' => false)),
      'last_name'     => new sfValidatorPass(array('required' => false)),
      'email'         => new sfValidatorPass(array('required' => false)),
      'mobile_model'  => new sfValidatorPass(array('required' => false)),
      'mobile_number' => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('employee_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Employee';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'company_id'    => 'ForeignKey',
      'first_name'    => 'Text',
      'last_name'     => 'Text',
      'email'         => 'Text',
      'mobile_model'  => 'Text',
      'mobile_number' => 'Text',
      'created_at'    => 'Date',
    );
  }
}
