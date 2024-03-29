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
                    $userguide->add(ClientdocumentsPeer::ID, 7);
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
            <div class="menuarrange" id="sddm">
                <ul class="menu">
                    <li class="dropdown">
                            <?php
                            if ($actionName == 'report' && $modulName == "affiliate" && $sf_request->getParameter('show_summary') == 1) {
                                 echo link_to(__('Overview'), 'affiliate/report?show_summary=1', array('class' => 'current'));
                        } else {
                                echo link_to(__('Overview'), 'affiliate/report?show_summary=1');
                            }
                            ?>
                        </li>
                        <li class="dropdown">
                            <a href="#" onclick="return false;"
                            <?php echo $actionName == 'registerCustomer' || $actionName == 'setProductDetails' || $actionName == 'refill' ? 'class="current"' : ''; ?>><?php echo __('Services'); ?></a>
                          <ul class="submenu">
                            <li> 
                           <?php
                            if ($modulName == "affiliate" && $actionName == 'registerCustomer' || $actionName == 'setProductDetails') {
                                echo link_to(__('Register a Customer'), '@customer_registration_step1', array('class' => 'subSelect'));
                            } else {
                                echo link_to(__('Register a Customer'), '@customer_registration_step1');
                            }
                           ?>
                            </li>
                            <li>
                            <?php
                            if ($modulName == "affiliate" && $actionName == 'refill') {
                                echo link_to(__('Refill'), 'affiliate/refill', array('class' => 'subSelect'));
                            } else {
                                 echo link_to(__('Refill'), 'affiliate/refill');
                            }
                            ?>
                            </li>
                            <li>
                            <?php 
                            if ($modulName == "affiliate" && $actionName == 'changenumberservice') {
                                echo link_to(__('Change Number'), 'affiliate/changenumberservice', array('class' => 'subSelect'));
                            } else {
                                 echo link_to(__('Change Number'), 'affiliate/changenumberservice');
                            }
                             ?>
                            </li>
                          </ul>
                        </li>
                        <li class="dropdown">
                            <?php
                            if ($modulName == "affiliate" && $actionName == 'receipts') {
                                echo link_to(__('Receipts'), 'affiliate/receipts', array('class' => 'current'));
                            } else {
                                echo link_to(__('Receipts'), 'affiliate/receipts');
                            }    
                            ?>
                        </li>
                        <li class="dropdown">
                            <?php
                                if ($modulName == "affiliate" && $actionName == 'report' && $sf_request->getParameter('show_details') == 1) {
                                    echo link_to(__('My Earnings'), 'affiliate/report?show_details=1', array('class' => 'current'));
                                } else {
                                echo link_to(__('My Earnings'), 'affiliate/report?show_details=1');
                                }
                            ?>
                        </li>
                        <li class="dropdown">
                            <?php
                                if ($modulName == "agentcompany" && $actionName == 'view' || $actionName == 'accountRefill' || $actionName == 'agentOrder' || $actionName == 'paymentHistory') {
                                echo link_to(__('My Company Info'), 'agentcompany/view', array('class' => 'current'));
                            } else {
                                echo link_to(__('My Company Info'), 'agentcompany/view');
                            }
                            ?></li>
                        <li class="dropdown">
                            <?php
                                if ($modulName == "affiliate" && $actionName == 'supportingHandset') {
                                echo link_to(__('Supporting Handsets'), 'affiliate/supportingHandset', array('class' => 'current'));
                            } else {
                                echo link_to(__('Supporting Handsets'), 'affiliate/supportingHandset');
                            }
                             ?>
                        </li>
                        <li class="dropdown">
                             <a href="#" onclick="return false;"
                            <?php echo $actionName == 'userguide'? 'class="current"' : ''; ?>><?php echo __('User Guide'); ?></a>
                            <ul class="submenu">
                            <li>
                                <?php
                                if ($modulName == "affiliate" && $actionName == 'userguide') {
                                    echo link_to(__('Smarter Sim User Guide'), 'affiliate/userguide', array('class' => 'current'));
                                } else {
                                    echo link_to(__('Smarter Sim User Guide'), 'affiliate/userguide');
                                }?>
                            </li>
                            <li>
                              <a href="/uploads/documents/<?php echo $guide->getFilename();?>" target="_blank"><?php echo $guide->getTitle();?></a>
                            </li>
                            </ul>
                        </li>
                        <li class="dropdown"><?php
                            if ($modulName == "affiliate" && $actionName == 'faq') {
                                echo link_to(__('FAQ'), 'affiliate/faq', array('class' => 'current'));
                            } else {
                                echo link_to(__('FAQ'), 'affiliate/faq');
                            }
                            ?>
                        </li>
                        <li class="dropdown last"><?php echo link_to(__('Logout'), 'agentUser/logout');?></li>
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
        <script type='text/javascript'>//<![CDATA[
jQuery(window).load(function(){
jQuery(function()
{
    var $dropdowns = jQuery('li.dropdown'); // Specifying the element is faster for older browsers

    /**
     * Mouse events
     *
     * @description Mimic hoverIntent plugin by waiting for the mouse to 'settle' within the target before triggering
     */
    $dropdowns
        .on('mouseover', function() // Mouseenter (used with .hover()) does not trigger when user enters from outside document window
        {
            var $this = jQuery(this);

            if ($this.prop('hoverTimeout'))
            {
                $this.prop('hoverTimeout', clearTimeout($this.prop('hoverTimeout')));
            }

            $this.prop('hoverIntent', setTimeout(function()
            {
                $this.addClass('hover');
            }, 250));
        })
        .on('mouseleave', function()
        {
            var $this = jQuery(this);

            if ($this.prop('hoverIntent'))
            {
                $this.prop('hoverIntent', clearTimeout($this.prop('hoverIntent')));
            }

            $this.prop('hoverTimeout', setTimeout(function()
            {
                $this.removeClass('hover');
            }, 250));
        });

    /**
     * Touch events
     *
     * @description Support click to open if we're dealing with a touchscreen
     */
    if ('ontouchstart' in document.documentElement)
    {
        $dropdowns.each(function()
        {
            var $this = $(this);

            this.addEventListener('touchstart', function(e)
            {
                if (e.touches.length === 1)
                {
                    // Prevent touch events within dropdown bubbling down to document
                    e.stopPropagation();

                    // Toggle hover
                    if (!$this.hasClass('hover'))
                    {
                        // Prevent link on first touch
                        if (e.target === this || e.target.parentNode === this)
                        {
                            e.preventDefault();
                        }

                        // Hide other open dropdowns
                        $dropdowns.removeClass('hover');
                        $this.addClass('hover');

                        // Hide dropdown on touch outside
                        document.addEventListener('touchstart', closeDropdown = function(e)
                        {
                            e.stopPropagation();

                            $this.removeClass('hover');
                            document.removeEventListener('touchstart', closeDropdown);
                        });
                    }
                }
            }, false);
        });
    }

});
});//]]>

</script>
    </body>
</html>
