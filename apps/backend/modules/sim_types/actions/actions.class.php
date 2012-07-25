<?php

/**
 * sim_types actions.
 *
 * @package    zapnacrm
 * @subpackage sim_types
 * @author     Your name here
 */
class sim_typesActions extends autosim_typesActions
{
  public function handleErrorSave() {
     $this->forward('sms_text','edit');
  }
}
