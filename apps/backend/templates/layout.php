<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="da" lang="da">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
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
  	<div id="wrapper">
  	<div id="header">
  		<p style="float: right">
  		<?php echo image_tag('/images/zapna_logo_small.png') ?>
  		</p>
  	</div>
    <?php if($sf_user->isAuthenticated()): ?>
      <ul class="admin-navigation">
  		
      </ul>
      <ul id="sddm">
            <li>
                <a href="#"
                onmouseover="mopen('m5')"
                onmouseout="mclosetime()">Landncall</a>
                <div id="m5"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php echo link_to('All Registered Customer', 'customer/allRegisteredCustomer'); ?>
                    <?php echo link_to('Registered Customer(Web)', 'customer/registeredByWeb'); ?>
                    <?php echo link_to('Registered Customer(Agent)', 'customer/registeredByAgent'); ?>
                    <?php echo link_to('Registered Customer(Agent Link)', 'customer/registeredByAgentLink'); ?>
                    <?php echo link_to('Registered Customer(Agent SMS)', 'customer/registeredBySms'); ?>
                    <?php echo link_to('Registered Customer(Mobile App)', 'customer/registeredByApp'); ?>
                    <?php echo link_to('Partial Registeration(Web)', 'customer/partialRegisteredByWeb'); ?>
                    <?php echo link_to('Partial Registeration(Agent)', 'customer/partialRegisteredByAgent'); ?>
                    <?php echo link_to('Partial Registeration(Agent Link)', 'customer/partialRegisteredByAgentLink'); ?>
                </div>
            </li>

          <li>
                <a href="#"
                onmouseover="mopen('m3')"
                onmouseout="mclosetime()">Agents</a>
                <div id="m3"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">

                    <?php echo link_to('company list', 'agent_company/index') ?>
                    <?php echo link_to('user lists', 'agent_user/index') ?>
                    <?php //echo link_to('commission', 'agent_commission/index') ?>
                    <?php echo link_to('Agent Per Product', 'agent_commission/selectCompany') ?>
                    <?php //echo link_to('bank info', 'agent_bank/index') ?>
                    <?php echo link_to('agent commission package', 'agent_commission_package/index') ?>
                </div>
            </li>
<li>
                <a href="#"
                onmouseover="mopen('m7')"
                onmouseout="mclosetime()">Updates</a>
                <div id="m7"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
		    <?php echo link_to('List All Updates', 'agent_company/newsList') ?>
                    <?php echo link_to('New Update', 'agent_company/newsUpdate') ?>
                      <?php echo link_to('FAQ', 'faqs/index') ?>
                    <?php echo link_to('User Guide', 'userguide/index') ?>

                </div>
            </li>

       <!--   <li>
                <a href="#"
                onmouseover="mopen('m8')"
                onmouseout="mclosetime()">SMS</a>
                <div id="m8"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
		    <?php echo link_to('Send Bulk SMS', 'sms/sendSms') ?>



                </div>
            </li> -->
            <li>
                <a href="#"
                onmouseover="mopen('m9')"
                onmouseout="mclosetime()">Revenue</a>
                <div id="m9"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
		    <?php echo link_to('Revenue Report', 'invoice/selectIntervalCompanyList') ?>


                </div>
            </li>

 <!--         <li>
				<a href="#"
                onmouseover="mopen('m6')"
                onmouseout="mclosetime()">Fonet</a>
                <div id="m6"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php echo link_to('Get Info', 'fonetAmin/info'); ?>
                    <?php echo link_to('Recharge', 'fonetAmin/recharge'); ?>
                    <?php echo link_to('Activate', 'fonetAmin/activate'); ?>
                    <?php echo link_to('DeActivate', 'fonetAmin/delete'); ?>
                </div>


            </li>-->
          
            <li style="display:none"><a href="#"
                onmouseover="mopen('m2')"
                onmouseout="mclosetime()">Company</a>
                <div id="m2"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">

                    <?php echo link_to('companies list', 'company/index') ?>
                    <?php echo link_to('employee lists', 'employee/index') ?>
                    <?php echo link_to('sale activity', 'sale_activity/index'); ?>
                    <?php echo link_to('support activity', 'support_activity/index'); ?>
                    <?php echo link_to('usage', 'cdr/index'); ?>
                    <?php echo link_to('invoices', 'invoice/index'); ?>
                    <?php echo link_to('product orders', 'product_order/index') ?>
                </div>
            </li>
            
            <li>
                <a href="#"
                onmouseover="mopen('m4')"
                onmouseout="mclosetime()">Security</a>
                <div id="m4"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php echo link_to('user', 'user/index'); ?>
                    <?php //echo link_to('role', 'role/index'); ?>
                    <?php //echo link_to('permission', 'permission/index'); ?>
                    <?php //echo link_to('role permission', 'role_permission/index'); ?>
                </div>
            </li>
            
            
			
			
          
<li><a href="#"
                onmouseover="mopen('m1')"
                onmouseout="mclosetime()">Settings</a>
                <div id="m1"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">

                      <?php
                        // As per Omair Instruction - He need these changes - AHTSHAM - 08/17/2011
                        ?>
                    <!--
                        <a href="javascript:;"><b>Zapna Setting</b></a>
                        <?php echo link_to('Customer types', 'customer_type/index') ?>
                        <?php echo link_to('Invoice methods', 'invoice_method/index') ?>
                        <?php echo link_to('Company size', 'company_size/index') ?>
                        <?php echo link_to('Company type', 'company_type/index') ?>
                        <?php echo link_to('Packages', 'package/index') ?>
                        -->
                        <?php // As per Omair Instruction - 08/17/2011
                        //echo link_to('sale activity status', 'sale_activity_status/index') ?>
                        <?php // As per Omair Instruction - 08/17/2011
                        //echo link_to('support activity status', 'support_activity_status/index') ?>
                        <?php //echo link_to('sale actions', 'sale_action/index') ?>
                        <?php //echo link_to('support issues', 'support_issue/index') ?>

                        <?php
                        // As per Omair Instruction - He need these changes - AHTSHAM - 08/17/2011
                         //echo link_to('<b>Zerocall Setting</b>', '') ?>
                        <a href="javascript:;"><b>LandnCall Setting</b></a>
                        <?php echo link_to('Mobile Models', 'device/index'); ?>
                        <?php echo link_to('Mobile Brands', 'manufacturer/index'); ?>
                        <?php echo link_to('Mobile Operator', 'telecom_operator/index') ?>
                        <?php echo link_to('Postal charges', 'postal_charges/index') ?>
                        <?php
                        // As per Omair Instruction - He need these changes - AHTSHAM - 08/17/2011
                        // echo link_to('<b></b>', '') ?>
                        <a href="javascript:;"><b>Agent Setting</b></a>
                         <?php echo link_to('Agent commission Invoices Note', 'invoice_note/index') ?>
                        <?php //echo link_to('apartment form', 'apartment_form/index') ?>
                        <?php //echo link_to('commission period', 'commission_period/index') ?>
                        <?php //echo link_to('revenue interval', 'revenue_interval/index') ?>
                        <?php //echo link_to('destination rates', 'destination_rate/index') ?>


                        <?php
                        // As per Omair Instruction - He need these changes - AHTSHAM - 08/17/2011
                         //echo link_to('<b>General Setting</b>', '') ?>
                        <a href="javascript:;"><b>General Setting</b></a>
                        <?php echo link_to('products', 'product/index') ?>
                        <?php echo link_to('Language Type', 'enable_country/index') ?>
                        <?php echo link_to('Cities', 'city/index') ?>
                        <?php echo link_to('SMS TEXT', 'sms_text/index') ?>
                        <?php echo link_to('Usage Alert', 'usage_alert/index') ?>
                        <?php echo link_to('Usage Alert Sender', 'usage_alert_sender/index') ?>
                        <?php echo link_to('Telecom Operator', 'telecom_operator/index') ?>
                        <?php echo link_to('DeActivat eCustomer', 'customer/deActivateCustomer') ?>
                        <a href="http://landncall.zerocall.com/backend_dev.php/client_documents">Upload Client Document</a>
                        <?php //echo link_to('global setting', 'global_setting/index') ?>
                        <?php //echo link_to('employee products', 'employee_product/index') ?>

                        <?php //echo link_to('Customer Commission', 'customer_commision/index') ?>


                    <!--
                    <?php echo link_to('status', 'status/index'); ?>
                    <?php echo link_to('customer types', 'customer_type/index') ?>
                    <?php echo link_to('invoice methods', 'invoice_method/index') ?>
                    <?php echo link_to('sale activity status', 'sale_activity_status/index') ?>
                    <?php echo link_to('support activity status', 'support_activity_status/index') ?>
                    <?php echo link_to('sale actions', 'sale_action/index') ?>
                    <?php echo link_to('support issues', 'support_issue/index') ?>
                    <?php echo link_to('company size', 'company_size/index') ?>
                    <?php echo link_to('company type', 'company_type/index') ?>
                    <?php echo link_to('device', 'device/index'); ?>
                    <?php echo link_to('manufacturer', 'manufacturer/index'); ?>
                    <?php echo link_to('apartment form', 'apartment_form/index') ?>
                    <?php echo link_to('commission period', 'commission_period/index') ?>
                    <?php echo link_to('revenue interval', 'revenue_interval/index') ?>
                    <?php echo link_to('destination rates', 'destination_rate/index') ?>
                    <?php echo link_to('packages', 'package/index') ?>
                    <?php echo link_to('products', 'product/index') ?>
                    <?php echo link_to('Usage Alert', 'usage_alert/index') ?>
                    <?php echo link_to('Usage Alert Sender', 'usage_alert_sender/index') ?>

                    <?php echo link_to('global setting', 'global_setting/index') ?>
                    <?php echo link_to('employee products', 'employee_product/index') ?>
                    <?php echo link_to('Language Type', 'enable_country/index') ?>
                    <?php echo link_to('Telecom Operator', 'telecom_operator/index') ?>
                    <?php echo link_to('Customer Commission', 'customer_commision/index') ?>
                    <?php echo link_to('Cities', 'city/index') ?>
                    <?php echo link_to('DeActivat eCustomer', 'customer/deActivateCustomer') ?>
		    <a href="http://landncall.zerocall.com/backend_dev.php/client_documents">Upload Client Document</a>
                    -->
                </div>
            </li>


			<li>
                <?php echo link_to('Logout', 'user/logout'); ?>
            </li>
        </ul>
      <?php endif; ?>

      <div style="clear:both"></div>
    <?php echo $sf_content ?>
    </div> <!--  end wrapper -->
  </body>
</html>
