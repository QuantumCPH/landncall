<?php use_helper('I18N') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <?php
                $va = new Criteria();
		$va->add(AgentCompanyPeer::ID, $sf_user->getAttribute('agent_company_id', '', 'usersession'));
		$agent_company = AgentCompanyPeer::doSelectOne($va);
       ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <style>
            .error{
                margin-left:150px;
                color:#F00000;
            }
        </style>
       <script type="text/javascript">
            <!--
            // Copyright 2006-2007 javascript-array.com

            var timeout	= 500;
            var closetimer	= 0;
            var ddmenuitem	= 0;

            // open hidden layer
            function mopen(id)
            {
                // cancel close timer
                mcancelclosetime();

                // close old layer
                if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

                // get new layer and show it
                ddmenuitem = document.getElementById(id);
                ddmenuitem.style.visibility = 'visible';

            }
            // close showed layer
            function mclose()
            {
                if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
            }

            // go close timer
            function mclosetime()
            {
                closetimer = window.setTimeout(mclose, timeout);
            }

            // cancel close timer
            function mcancelclosetime()
            {
                if(closetimer)
                {
                    window.clearTimeout(closetimer);
                    closetimer = null;
                }
            }

            // close layer when click-out
            document.onclick = mclose;
            -->

        </script>
    </head>
    <body>
        <div id="basic">
            <div id="header">
                <div id="logo">
                    <?php echo image_tag('/images/zapna_logo_small.png');// link_to(image_tag('/images/logo.gif'), '@homepage'); ?>
                </div>
            </div>
            <div class="clr"></div>
            
                <?php if($sf_user->getAttribute('username', '', 'usersession')){?>
            <div id="slogan">
                <h1><?php echo __('Agent Portal'); ?></h1>
                    <div id="loggedInUser">
                        <?php echo __('Logged in as:') ?><b>&nbsp;<?php echo $sf_user->getAttribute('username', '', 'usersession')?></b><br />
                        <?php
                        if($agent_company){
                        if($agent_company->getIsPrepaid()){ ?>
                        <?php echo __('Your Balance is:') ?> <b><?php echo $agent_company->getBalance(); ?></b>
                        <?php }
                        ?>
                        <?php } ?>
                    </div>
                <div class="clr"></div>
            </div>
                <?php } ?>
                
                <?php
                    $userguide = new Criteria();
                    $userguide->add(ClientdocumentsPeer::ID, 2);
                    $guide = ClientdocumentsPeer::doSelectOne($userguide);
//                $enableCountry = new Criteria();
//                $enableCountry->add(EnableCountryPeer::STATUS, '1');
//
//                $form = new sfFormLanguage(
//                $sf_user,
//                array('languages' => array('en', 'da','pl','sv'))
//                );
//                $widgetSchema = $form->getWidgetSchema();
//                $widgetSchema['language']->setAttribute('style', "width:85px");
//                $widgetSchema['language']->setAttribute('onChange', "this.form.submit();");
//                $widgetSchema['language']->setAttribute('onChange', "this.form.submit();");
//                $widgetSchema['language']->setLabel(false);
                ?>
<!--                <div style="position:absolute; left: 846px; top: 54px;">
                  <form action="">
                    <?php   echo $form ;
                    ?>
                    <input type="hidden" value="<?php echo $sf_user->getAttribute('product_ids') ?>" name="pid" />
                    <input type="hidden" value="<?php echo $sf_user->getAttribute('cusid') ?>" name="cid" />
                </form>
                </div>-->
           <?php if($sf_user->isAuthenticated()){
                    $modulName = $sf_context->getModuleName();
                    $actionName = $sf_context->getActionName();
               ?>
            <div id="menu">
                <ul id="sddm">

                        <li>
                            <?php
                            if ($actionName == 'report' && $modulName == "affiliate" && $sf_request->getParameter('show_summary') == 1) {
                                 echo link_to(__('Overview'), 'affiliate/report?show_summary=1', array('class' => 'current'));
                        } else {
                                echo link_to(__('Overview'), 'affiliate/report?show_summary=1');
                            }
                            ?>
                        </li>
                        <li>
                            <a onmouseover="mopen('m2')" onmouseout="mclosetime()" href="#" onclick="return false;"
                            <?php echo $actionName == 'registerCustomer' || $actionName == 'setProductDetails' || $actionName == 'refill' ? 'class="current"' : ''; ?>><?php echo __('Services'); ?></a>
                        <div id="m2" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                            <?php
                            if ($modulName == "affiliate" && $actionName == 'registerCustomer' || $actionName == 'setProductDetails') {
                                echo link_to(__('Register a Customer'), '@customer_registration_step1', array('class' => 'subSelect'));
                            } else {
                                echo link_to(__('Register a Customer'), '@customer_registration_step1');
                            }
                            if ($modulName == "affiliate" && $actionName == 'refill') {
                                echo link_to(__('Refill'), 'affiliate/refill', array('class' => 'subSelect'));
                            } else {
                                 echo link_to(__('Refill'), 'affiliate/refill');
                            }
                            if ($modulName == "affiliate" && $actionName == 'changenumberservice') {
                                echo link_to(__('Change Number'), 'affiliate/changenumberservice', array('class' => 'subSelect'));
                            } else {
                                 echo link_to(__('Change Number'), 'affiliate/changenumberservice');
                            }
                             ?>
                            </div>
                        </li>
                        <li>
                            <?php
                            if ($modulName == "affiliate" && $actionName == 'receipts') {
                                echo link_to(__('Receipts'), 'affiliate/receipts', array('class' => 'current'));
                            } else {
                                echo link_to(__('Receipts'), 'affiliate/receipts');
                            }    
                            ?>
                        </li>
                        <li>
                            <?php
                                if ($modulName == "affiliate" && $actionName == 'report' && $sf_request->getParameter('show_details') == 1) {
                                    echo link_to(__('My Earnings'), 'affiliate/report?show_details=1', array('class' => 'current'));
                                } else {
                                echo link_to(__('My Earnings'), 'affiliate/report?show_details=1');
                                }
                            ?>
                        </li>
                        <li>
                            <?php
                                if ($modulName == "agentcompany" && $actionName == 'view' || $actionName == 'accountRefill' || $actionName == 'agentOrder' || $actionName == 'paymentHistory') {
                                echo link_to(__('My Company Info'), 'agentcompany/view', array('class' => 'current'));
                            } else {
                                echo link_to(__('My Company Info'), 'agentcompany/view');
                            }
                            ?></li>
                        <li>
                            <?php
                                if ($modulName == "affiliate" && $actionName == 'supportingHandset') {
                                echo link_to(__('Supporting Handsets'), 'affiliate/supportingHandset', array('class' => 'current'));
                            } else {
                                echo link_to(__('Supporting Handsets'), 'affiliate/supportingHandset');
                            }
                             ?>
                        </li>
                        <li>
                             <a onmouseover="mopen('m3')" onmouseout="mclosetime()" href="#" onclick="return false;"
                            <?php echo $actionName == 'userguide'? 'class="current"' : ''; ?>><?php echo __('User Guide'); ?></a>
                            <div id="m3" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                            <?php
                            if ($modulName == "affiliate" && $actionName == 'userguide') {
                                echo link_to(__('Smarter Sim User Guide'), 'affiliate/userguide', array('class' => 'current'));
                            } else {
                                echo link_to(__('Smarter Sim User Guide'), 'affiliate/userguide');
                            }?>
                                <a href="/uploads/documents/<?php echo $guide->getFilename();?>" target="_blank"><?php echo $guide->getTitle();?></a>
                            </div>
                        </li>
                        <li><?php
                            if ($modulName == "affiliate" && $actionName == 'faq') {
                                echo link_to(__('FAQ'), 'affiliate/faq', array('class' => 'current'));
                            } else {
                                echo link_to(__('FAQ'), 'affiliate/faq');
                            }
                            ?>
                          
                        </li>
                        <li class="last"><?php echo link_to(__('Logout'), 'agentUser/logout');?></li>
                    </ul>
                <div class="clr"></div>
            </div>
                <?php } ?>
            
            <div id="content">

                <?php if($sf_user->hasFlash('message')): ?>
                    <div id="info-message" class="grid_9 save-ok">
                        <?php echo $sf_user->getFlash('message'); ?>                        
                    </div>
                <?php endif; ?>
               

                <?php if($sf_user->hasFlash('decline')): ?>
                    <div id="info-message" class="grid_9 save-decl">
                        <?php echo $sf_user->getFlash('decline'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if($sf_user->hasFlash('error')): ?>
                    <div id="error-message" class="grid_9 save-ok">
                        <?php echo $sf_user->getFlash('error'); ?>                        
                    </div>
                <?php endif; ?>

                
                <?php if($sf_user->isAuthenticated()): ?>
                 <div class="clr"></div>
                    <p>
                        <?php echo __('Provide this link to your customers while they signup with your reference.') ?>
                        <a href="<?php echo sfConfig::get('app_url'); ?>b2c/signup/step1?ref=<?php echo $sf_user->getAttribute('agent_company_id', '', 'usersession') ?>">
                            <?php echo sfConfig::get('app_url'); ?>b2c/signup/step1?ref=<?php echo $sf_user->getAttribute('agent_company_id', '', 'usersession')?>
			</a>			
                    </p>
                    <?php endif; ?>

                <?php echo $sf_content ?>
            </div>
            <div class="clear"></div>
        </div>
    </body>
</html>
