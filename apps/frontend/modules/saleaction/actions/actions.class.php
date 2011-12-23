<?php

/**
 * saleaction actions.
 *
 * @package    zapnacrm
 * @subpackage saleaction
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class saleactionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->sale_action_list = SaleActionPeer::doSelect(new Criteria());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SaleActionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new SaleActionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($sale_action = SaleActionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object sale_action does not exist (%s).', $request->getParameter('id')));
    $this->form = new SaleActionForm($sale_action);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($sale_action = SaleActionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object sale_action does not exist (%s).', $request->getParameter('id')));
    $this->form = new SaleActionForm($sale_action);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($sale_action = SaleActionPeer::retrieveByPk($request->getParameter('id')), sprintf('Object sale_action does not exist (%s).', $request->getParameter('id')));
    $sale_action->delete();

    $this->redirect('saleaction/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $sale_action = $form->save();

      $this->redirect('saleaction/edit?id='.$sale_action->getId());
    }
  }
}
