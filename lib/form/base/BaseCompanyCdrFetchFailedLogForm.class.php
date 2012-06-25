<?php

/**
 * CompanyCdrFetchFailedLog form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseCompanyCdrFetchFailedLogForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'company_id' => new sfWidgetFormInput(),
      'to_date'    => new sfWidgetFormDateTime(),
      'from_date'  => new sfWidgetFormDateTime(),
      'status'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'CompanyCdrFetchFailedLog', 'column' => 'id', 'required' => false)),
      'company_id' => new sfValidatorInteger(),
      'to_date'    => new sfValidatorDateTime(),
      'from_date'  => new sfValidatorDateTime(),
      'status'     => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('company_cdr_fetch_failed_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CompanyCdrFetchFailedLog';
  }


}
