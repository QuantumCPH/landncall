<?php

/**
 * invoicemethod actions.
 *
 * @package    zapnacrm
 * @subpackage invoicemethod
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class invoicemethodActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->invoice_method_list = InvoiceMethodPeer::doSelect(new Criteria());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new InvoiceMethodForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new InvoiceMethodForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($invoice_method = InvoiceMethodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object invoice_method does not exist (%s).', $request->getParameter('id')));
    $this->form = new InvoiceMethodForm($invoice_method);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($invoice_method = InvoiceMethodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object invoice_method does not exist (%s).', $request->getParameter('id')));
    $this->form = new InvoiceMethodForm($invoice_method);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($invoice_method = InvoiceMethodPeer::retrieveByPk($request->getParameter('id')), sprintf('Object invoice_method does not exist (%s).', $request->getParameter('id')));
    $invoice_method->delete();

    $this->redirect('invoicemethod/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $invoice_method = $form->save();

      $this->redirect('invoicemethod/edit?id='.$invoice_method->getId());
    }
  }
}
