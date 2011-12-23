<?php

/**
 * supportissue actions.
 *
 * @package    zapnacrm
 * @subpackage supportissue
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class supportissueActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->support_issue_list = SupportIssuePeer::doSelect(new Criteria());
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SupportIssueForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new SupportIssueForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($support_issue = SupportIssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object support_issue does not exist (%s).', $request->getParameter('id')));
    $this->form = new SupportIssueForm($support_issue);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($support_issue = SupportIssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object support_issue does not exist (%s).', $request->getParameter('id')));
    $this->form = new SupportIssueForm($support_issue);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($support_issue = SupportIssuePeer::retrieveByPk($request->getParameter('id')), sprintf('Object support_issue does not exist (%s).', $request->getParameter('id')));
    $support_issue->delete();

    $this->redirect('supportissue/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $support_issue = $form->save();

      $this->redirect('supportissue/edit?id='.$support_issue->getId());
    }
  }
}
