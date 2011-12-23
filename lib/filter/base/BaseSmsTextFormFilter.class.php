<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * SmsText filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseSmsTextFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'for_text'     => new sfWidgetFormFilterInput(),
      'text_heading' => new sfWidgetFormFilterInput(),
      'message_text' => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'for_text'     => new sfValidatorPass(array('required' => false)),
      'text_heading' => new sfValidatorPass(array('required' => false)),
      'message_text' => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('sms_text_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmsText';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'for_text'     => 'Text',
      'text_heading' => 'Text',
      'message_text' => 'Text',
      'created_at'   => 'Date',
    );
  }
}
