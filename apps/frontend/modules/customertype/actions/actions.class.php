<?php

/**
 * customertype actions.
 *
 * @package    zapnacrm
 * @subpackage customertype
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class customertypeActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->customer_type_list = CustomerTypePeer::doSelect(new Criteria());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CustomerTypeForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new CustomerTypeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($customer_type = CustomerTypePeer::retrieveByPk($request->getParameter('id')), sprintf('Object customer_type does not exist (%s).', $request->getParameter('id')));
    $this->form = new CustomerTypeForm($customer_type);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($customer_type = CustomerTypePeer::retrieveByPk($request->getParameter('id')), sprintf('Object customer_type does not exist (%s).', $request->getParameter('id')));
    $this->form = new CustomerTypeForm($customer_type);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($customer_type = CustomerTypePeer::retrieveByPk($request->getParameter('id')), sprintf('Object customer_type does not exist (%s).', $request->getParameter('id')));
    $customer_type->delete();

    $this->redirect('customertype/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $customer_type = $form->save();

      $this->redirect('customertype/edit?id='.$customer_type->getId());
    }
  }
}
