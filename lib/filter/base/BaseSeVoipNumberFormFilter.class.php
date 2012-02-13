<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * SeVoipNumber filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseSeVoipNumberFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'number'      => new sfWidgetFormFilterInput(),
      'customer_id' => new sfWidgetFormFilterInput(),
      'is_assigned' => new sfWidgetFormFilterInput(),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'number'      => new sfValidatorPass(array('required' => false)),
      'customer_id' => new sfValidatorPass(array('required' => false)),
      'is_assigned' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('se_voip_number_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SeVoipNumber';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'number'      => 'Text',
      'customer_id' => 'Text',
      'is_assigned' => 'Number',
      'updated_at'  => 'Date',
    );
  }
}
