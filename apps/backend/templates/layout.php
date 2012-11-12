<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <?php //use_javascript('jquery-ui-1.8.16.custom.min.js', '', array('absolute'=>true)) ?>
        <?php //use_stylesheet('ui-lightness/jquery-ui-1.8.16.custom.css', 'last', array('absolute'=>true)) ?>
        <link rel="shortcut icon" href="/favicon.ico" />
       
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                <div class="logo">
                    <?php echo image_tag('/images/zapna_logo_small.png') ?>
                </div>
                <div class="clr"></div>

            </div>
            <?php
                    if ($sf_user->isAuthenticated()):
                        $modulName = $sf_context->getModuleName();
                        $actionName = $sf_context->getActionName();
            ?>
                        <div id="slogan">
                            <h1><?php echo __('Admin Portal'); ?></h1>
                        </div>
  <div class="menuarrange" id="sddm">           
  <ul class="menu">
      <li class="dropdown">
          <a href="#">B2B</a>
           <ul class="submenu">
            <li> 
                <?php echo link_to('Companies list', 'company/index') ?>
            </li>
            <li>    
                <?php echo link_to('Employee lists', 'employee/index') ?>
            </li>
            <li>     
                <?php echo link_to('Payment History', 'company/paymenthistory') ?>
            </li>
               <li>     
                <?php echo link_to('Invoices', 'company/invoices') ?>
            </li>
            <li>    
                <?php echo link_to('Refill', 'company/refill'); ?>
            </li>  
                <?php //echo link_to('support activity', 'support_activity/index'); ?>
                <?php //echo link_to('usage', 'cdr/index'); ?>
                <?php //echo link_to('invoices', 'invoice/index'); ?>
                <?php //echo link_to('Product orders', 'product_order/index')  ?>
           </ul>
        </li>
        <li class="dropdown">
           <a href="#">B2C</a>
             <ul class="submenu">
              <li> 
                 <?php
                  echo link_to('All Registered Customer', 'customer/allRegisteredCustomer');
                 ?>
              </li>
              <li>
                 <?php
                   echo link_to('Registered Customer(Web)', 'customer/registeredByWeb');
                  ?>
              </li>
              <li>
                 <?php 
                 echo link_to('Registered Customer(Agent)', 'customer/registeredByAgent');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Registered Customer(Agent Link)', 'customer/registeredByAgentLink');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Partial Registeration(Web)', 'customer/partialRegisteredByWeb');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Partial Registeration(Agent)', 'customer/partialRegisteredByAgent');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Partial Registeration(Agent Link)', 'customer/partialRegisteredByAgentLink');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Charge Customer', 'customer/selectChargeCustomer');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Refill Customers', 'customer/selectRefillCustomer');
                 ?>
              </li>
              <li>
                 <?php
                 echo link_to('Payment History', 'customer/completePaymenthistory');
                 ?>
              </li>    
            </ul>
         </li>
         <li class="dropdown">
           <a href="#">Agents</a>
             <ul class="submenu">
                 <li>  
                   <?php echo link_to('company list', 'agent_company/index') ?>
                 </li>
                 <li>
                  <?php echo link_to('user lists', 'agent_user/index') ?>
                 </li>
                 <li>    
                <?php //echo link_to('commission', 'agent_commission/index') ?>
                <?php echo link_to('Agent Per Product', 'agent_commission/selectCompany') ?>
                </li>
                 <li>     
                <?php //echo link_to('bank info', 'agent_bank/index') ?>
                <?php echo link_to('agent commission package', 'agent_commission_package/index') ?>
                </li>
                 <li>      
                <?php echo link_to(__('Refil Agent Company'), 'agent_company/selectCompany'); ?>
                     </li>
                 <li>
                <?php echo link_to(__('Charge Agent Company'), 'agent_company/chargeCompany'); ?>
                </li>
                 <li>     
                  <?php echo link_to(__('Payment History'), 'agent_company/agentCompanyPayment'); ?>
                </li>
              </ul>
     </li>
             <li class="dropdown">
             <a href="#" >Updates</a>
             <ul class="submenu">
                 <li>  
                 <?php echo link_to('List All Updates', 'agent_company/newsList') ?>
                 </li>
                 <li>
                 <?php echo link_to('New Update', 'agent_company/newsUpdate') ?>
                 </li>
                 <li>
                 <?php echo link_to('FAQ', 'faqs/index') ?>
                 </li> 
                 <li>
                 <?php echo link_to('User Guide', 'userguide/index') ?>
                </li> 
             </ul>
            </li>

        <!--   <li class="dropdown">
                 <a href="#">SMS</a><?php echo link_to('Send Bulk SMS', 'sms/sendSms') ?>
               </li> -->
           <li class="dropdown">
             <a href="#">Reports</a>
               <ul class="submenu">
                   <li>   
                     <?php echo link_to('Low Credit Alert Report ', 'invoice/selectIntervalAlert'); ?>
                   </li>    
               </ul>
           </li>

                    <!-- <li class="dropdown">
			<a href="#">Fonet</a>
                                   <ul class="submenu">
                                    <li>
                                          <?php echo link_to('Get Info', 'fonetAmin/info'); ?>
                                    </li>
                                    <li>
                                       <?php echo link_to('Recharge', 'fonetAmin/recharge'); ?>
                                    </li>
                                    <li>
                                      <?php echo link_to('Activate', 'fonetAmin/activate'); ?>
                                    </li>
                                    <li>
                                      <?php echo link_to('DeActivate', 'fonetAmin/delete'); ?>
                                   </li> 
                                   </ul>
                   </li>-->
           <li class="dropdown" style="display:none">
               <a href="#">Company</a>
               <ul class="submenu">
                   <li>
                <?php echo link_to('companies list', 'company/index') ?>
                   </li>
                   <li>
                <?php echo link_to('employee lists', 'employee/index') ?>
                   </li>
                   <li>
                <?php echo link_to('sale activity', 'sale_activity/index'); ?>
                   </li>
                   <li>    
                <?php echo link_to('support activity', 'support_activity/index'); ?>
                   </li>
                   <li>    
                <?php echo link_to('usage', 'cdr/index'); ?>
                   </li>
                   <li>    
                <?php echo link_to('invoices', 'invoice/index'); ?>
                   </li>
                   <li>    
                <?php echo link_to('product orders', 'product_order/index') ?>
                   </li>
                </ul>
            </li>
            <li class="dropdown">
              <a href="#" >Admin Users</a>
              <ul class="submenu">
                 <li><?php echo link_to('user', 'user/index'); ?></li>
                 <!--<li><?php //echo link_to('role', 'role/index'); ?></li>
                 <li><?php //echo link_to('permission', 'permission/index'); ?></li>
                 <li><?php //echo link_to('role permission', 'role_permission/index');  ?></li>-->
               </ul>
            </li>
           <li class="dropdown">
              <a href="#">Download</a>
                <ul class="submenu">
                 <li><?php echo link_to('Downlaod User Guide', 'client_documents/index') ?> </li>
                </ul> 
           </li>
           <li class="dropdown"><a href="#">Settings</a>
               <ul class="submenu">  
                      <li>
                        <?php
                             // As per Omair Instruction - He need these changes - AHTSHAM - 08/17/2011
                             //echo link_to('<b>General Setting</b>', '') ?>
                             <a href="javascript:;"><b>General Setting</b></a>
                        <?php echo link_to('products', 'product/index') ?>
                      </li> 
                      <li>  
                        <?php echo link_to('Country List', 'enable_country/index') ?>
                      </li>
                      <li>     
                        <?php echo link_to('Cities', 'city/index') ?>
                      </li>
                      <li>    
                        <?php echo link_to('SMS TEXT', 'sms_text/index') ?>
                      </li>
                      <li>    
                        <?php echo link_to('Low Credit Alert', 'usage_alert/index') ?>
                      </li>
                      <li>    
                        <?php echo link_to('Low Credit Alert Sender', 'usage_alert_sender/index') ?>
                      </li>
                      <li>    
                        <?php echo link_to('Telecom Operator', 'telecom_operator/index') ?>
                     </li>
                      <li>     
                        <?php echo link_to('DeActivat eCustomer', 'customer/deActivateCustomer') ?>
                     </li>
                      <li>     
                        <?php echo link_to('Transaction Description', 'transactionDescription/index');?>
                     </li>
                      <li>     
                         <?php  echo link_to('Edit B2B Credit Limit', 'company/indexAll'); ?>
                      </li>
               </ul>
           </li>
          <li class="dropdown last">
                 <?php echo link_to('Logout', 'user/logout'); ?>
           </li>
          </ul>
                     </div><div style="clear:both"></div><br/>
<?php endif; ?>
                 

<?php echo $sf_content ?>
                         </div> <!--  end wrapper -->

 <script type="text/javascript">
    //<![CDATA[
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
                         <script type="text/javascript">
                             jQuery(function(){
                                
                                 jQuery('#sf_admin_edit_form').validate({

                                     rules: {
                                         "company[name]": "required",
                                         "company[vat_no]": "required",
                                         "company[post_code]": "required",
                                         "company[address]": "required",
                                         "company[contact_name]": "required",
                                         "company[head_phone_number]": "required",
                                         "company[fax_number]": "digits",
                                         "company[created_at]": "required",
                                         "company[email]": "required email"
                                     }
                                 });
                             });
                         </script>

                         <script type="text/javascript">
                             jQuery('#company_post_code').blur(function(){
                                 var poid=jQuery("#company_post_code").val();
                                 poid = poid.replace(/\s+/g, '');
                                 var poidlenght=poid.length;
                                 //alert(poidlenght);
                                 var poida= poid.charAt(0);
                                 var poidb= poid.charAt(1);
                                 var poidc= poid.charAt(2);
                                 var poidd= poid.charAt(3);
                                 var poide= poid.charAt(4);
                                 if(poidlenght>4){
                                     var fulvalue=poida+poidb+poidc+" "+poidd+poide;
                                 }
                                 else{
                                     //var fulvalue=poida+poidb+poidc;
                                 }
                                 jQuery("#company_post_code").val(fulvalue);
                                 //  alert(fulvalue);

                             });

                         </script>

                         <script language="javascript" type="text/javascript">

                             jQuery('#company_vat_no').blur(function(){
                                 //remove all the class add the messagebox classes and start fading
                                 jQuery("#msgbox").removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");

                                 var val=jQuery(this).val();

                                 if(val==''){
                                     jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
                                     {
                                         //add message and change the class of the box and start fading
                                         jQuery(this).html('Enter Vat Number').addClass('messageboxerror').fadeTo(900,1);
                                     });
                                     jQuery('#error').val("error");
                                 }else{
                                     //check the username exists or not from ajax
                                     jQuery.post("<?PHP echo sfConfig::get('app_backend_url') ?>company/vat",{ vat_no:val } ,function(data)
                                     {//alert(data);
                                         if(data=='no') //if username not avaiable
                                         {
                                             jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
                                             {
                                                 //add message and change the class of the box and start fading
                                                 jQuery(this).html('This Vat No Already exists').addClass('messageboxerror').fadeTo(900,1);
                                             });jQuery('#error').val("error");
                                         }
                                         else
                                         {
                                             jQuery("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
                                             {
                                                 //add message and change the class of the box and start fading
                                                 jQuery(this).html('Vat No is available').addClass('messageboxok').fadeTo(900,1);
                                             });jQuery('#error').val("");
                                         }

                                     });
                                 }
                             });

                             jQuery('#employee_mobile_number').blur(function(){
                                 //remove all the class add the messagebox classes and start fading
                                 jQuery("#msgbox").removeClass().addClass('messagebox').text('Checking...').fadeIn("slow");
                                 //check the username exists or not from ajax
                                 var val=jQuery(this).val();

                                 if(val==''){
                                     jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
                                     {
                                         //add message and change the class of the box and start fading
                                         jQuery(this).html('Enter Mobile Number').addClass('messageboxerror').fadeTo(900,1);
                                     });
                                     jQuery('#error').val("error");
                                 }else{
                                     if(val.length >7){

                                         if(val.substr(0, 1)==0){
                                             jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
                                             {
                                                 //add message and change the class of the box and start fading
                                                 jQuery(this).html('Please enter a valid mobile number not starting with 0').addClass('messageboxerror').fadeTo(900,1);
                                             });
                                             jQuery('#error').val("error");
                                         }else{

                                             jQuery.post("<?PHP echo sfConfig::get('app_backend_url') ?>employee/mobile",{ mobile_no: val} ,function(data)
                            {
                                if(data=='no') //if username not avaiable
                                {
                                    jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
                                    {
                                        //add message and change the class of the box and start fading
                                        jQuery(this).html('This Mobile No Already exists').addClass('messageboxerror').fadeTo(900,1);
                                    });jQuery('#error').val("error");
                                }
                                else
                                {
                                    jQuery("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
                                    {
                                        //add message and change the class of the box and start fading
                                        jQuery(this).html('Mobile No is available').addClass('messageboxok').fadeTo(900,1);
                                    });jQuery('#error').val("");
                                }

                            });
                        }}}
            });

            jQuery("#sf_admin_form").submit(function() {
                if (jQuery("#error").val() == "error") {

                    return false;
                }else{
                    return true;
                }


            });
            jQuery("#sf_admin_edit_form").submit(function() {
                if (jQuery("#error").val() == "error") {

                    return false;
                }else{
                    return true;
                }


            });

            jQuery(function(){

                // add multiple select / deselect functionality
                jQuery("#selectall").click(function () {
                    jQuery('.case').attr('checked', this.checked);
                });

                // if all checkbox are selected, check the selectall checkbox
                // and viceversa
                jQuery(".case").click(function(){

                    if(jQuery(".case").length == jQuery(".case:checked").length) {
                        jQuery("#selectall").attr("checked", "checked");
                    } else {
                        jQuery("#selectall").removeAttr("checked");
                    }

                });
            });


        </script>
        <style type="text/css">
            .messagebox{
                position:absolute;
                width:100px;
                margin-left:30px;
                border:1px solid #c93;
                background:#ffc;
                padding:3px;
            }
            .messageboxok{
                position:absolute;
                width:auto;
                margin-left:30px;
                border:1px solid #349534;
                background:#C9FFCA;
                padding:3px;
                font-weight:bold;
                color:#008000;

            }
            .messageboxerror{
                position:absolute;
                width:auto;
                margin-left:21px;
                border:1px solid #CC0000;
                background:#F7CBCA;
                padding:6px;
                font-weight:bold;
                color:#CC0000;
            }

        </style>
    </body>
</html>
