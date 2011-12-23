<?php

/**
 * company actions.
 *
 * @package    zapnacrm
 * @subpackage company
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class companyActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->company_list = CompanyPeer::doSelect(new Criteria());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CompanyForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new CompanyForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($company = CompanyPeer::retrieveByPk($request->getParameter('id')), sprintf('Object company does not exist (%s).', $request->getParameter('id')));
    $this->form = new CompanyForm($company);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($company = CompanyPeer::retrieveByPk($request->getParameter('id')), sprintf('Object company does not exist (%s).', $request->getParameter('id')));
    $this->form = new CompanyForm($company);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($company = CompanyPeer::retrieveByPk($request->getParameter('id')), sprintf('Object company does not exist (%s).', $request->getParameter('id')));
    $company->delete();

    $this->redirect('company/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $company = $form->save();

      $this->redirect('company/edit?id='.$company->getId());
    }
  }
}
