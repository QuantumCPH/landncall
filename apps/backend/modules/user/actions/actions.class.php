<?php

/**
 * user actions.
 *
 * @package    zapnacrm
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 5125 2007-09-16 00:53:55Z dwhittle $
 */
class userActions extends autouserActions
{
    public function executeLogin($request){

        $this->loginForm = new LoginForm();
        
        if($request->getMethod() != 'post'){
            $this->loginForm->bind($request->getParameter('login'), $request->getFiles('login'));
            if($this->loginForm->isValid()){

                $email = $this->loginForm->getValue('email');
                $password = $this->loginForm->getValue('password');

                $c = new Criteria();
                $c->add(UserPeer::EMAIL, $email);
                $c->addAnd(UserPeer::PASSWORD, $password);
                $c->addAnd(UserPeer::IS_SUPER_USER, 1);

                $user = UserPeer::doSelectOne($c);

                if($user){
                    $this->getUser()->setAuthenticated(true);
                    $this->getUser()->setAttribute('user_id', $user->getId(), 'backendsession');
					$this->getUser()->setAttribute('role_id', $user->getRoleId(), 'backendsession');
                    $this->getUser()->setFlash('message', 'Welcome '.$user->getName());
                    
                    //if ($redirect_path = $request->getParameter('referer'))
                    //	$this->redirect($redirect_path);
                    //else
                    	//$this->redirect('@homepage');
                    	$this->redirect('customer/allRegisteredCustomer');
                } else {
                    $this->getUser()->setFlash('message', 'You are not Authorized / or you have submitted incorrect e-mail and password');
                }
                
            }
        }
    }

    public function executeLogout($request){
        $this->getUser()->getAttributeHolder()->removeNamespace('backendsession');
        $this->getUser()->setAuthenticated(false);
        $this->redirect('@homepage');
    }
}
