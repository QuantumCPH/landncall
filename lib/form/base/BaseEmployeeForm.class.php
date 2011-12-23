<?php

/**
 * Employee form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseEmployeeForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'company_id'    => new sfWidgetFormPropelChoice(array('model' => 'Company', 'add_empty' => true)),
      'first_name'    => new sfWidgetFormInput(),
      'last_name'     => new sfWidgetFormInput(),
      'email'         => new sfWidgetFormInput(),
      'mobile_model'  => new sfWidgetFormInput(),
      'mobile_number' => new sfWidgetFormInput(),
      'created_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'Employee', 'column' => 'id', 'required' => false)),
      'company_id'    => new sfValidatorPropelChoice(array('model' => 'Company', 'column' => 'id', 'required' => false)),
      'first_name'    => new sfValidatorString(array('max_length' => 50)),
      'last_name'     => new sfValidatorString(array('max_length' => 50)),
      'email'         => new sfValidatorString(array('max_length' => 150)),
      'mobile_model'  => new sfValidatorString(array('max_length' => 50)),
      'mobile_number' => new sfValidatorString(array('max_length' => 50)),
      'created_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('employee[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Employee';
  }


}
