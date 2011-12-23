<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?> 
  <div class="left-col">
    <div class="split-form-sign-up">
      <div class="step-details"> <strong>Become a Customer <span class="active">- Step 1: Register </span><span class="inactive">- Step 2: Payment</span></strong> </div>
      <div class="fl col">
        <form method="post" action="<?php url_for('signup/index') ?>" id="newCustomerForm">
        <?php echo $form->renderHiddenFields() ?>
          <ul>
            <?php 
            $error_mobile_number = false;
            if($form['mobile_number']->hasError())
            	$error_mobile_number = true;
            ?>
            <li>
             <?php echo $form['mobile_number']->renderLabel() ?>
             <?php echo $form['mobile_number'] ?>
            </li>
            
            <?php if($error_mobile_number) { ?>
            <li class="error">
            	<?php echo $form['mobile_number']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end mobile_number -->
            <?php
            $error_product = false;;
            if($form['product']->hasError())
            	$error_product = true;
            ?>
            <li>
             <?php echo $form['product']->renderLabel() ?>
             <?php echo $form['product'] ?>
            </li>
            
            <?php if($error_product) { ?>
            <li class="error">
            	<?php echo $form['product']->renderError() ?>
            </li>
            <?php } ?>
            <!--  end product -->
            <?php
            $error_first_name = false;;
            if($form['first_name']->hasError())
            	$error_first_name = true;
            ?>
            <li>
             <?php echo $form['first_name']->renderLabel() ?>
             <?php echo $form['first_name'] ?>
            </li>
            
            <?php if($error_first_name) { ?>
            <li class="error">
            	<?php echo $form['first_name']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end first name -->
            <?php
            $error_last_name = false;;
            if($form['last_name']->hasError())
            	$error_last_name = true;
            ?>
            <li>
             <?php echo $form['last_name']->renderLabel() ?>
             <?php echo $form['last_name'] ?>
            </li>
            
            <?php if($error_last_name) { ?>
            <li class="error">
            	<?php echo $form['last_name']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end last name -->
            <?php
            $error_address = false;;
            if($form['address']->hasError())
            	$error_address = true;
            ?>
            <li>
             <?php echo $form['address']->renderLabel() ?>
             <?php echo $form['address'] ?>
            </li>
            
            <?php if($error_address) { ?>
            <li class="error">
            	<?php echo $form['address']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end address -->
            <?php
            $error_po_box_number = false;;
            if($form['po_box_number']->hasError())
            	$error_po_box_number = true;
            ?>
            <li>
             <?php echo $form['po_box_number']->renderLabel() ?>
             <?php echo $form['po_box_number'] ?>
            </li>
            
            <?php if($error_po_box_number) { ?>
            <li class="error">
            	<?php echo $form['po_box_number']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end pobox number -->
            <?php
            $error_city = false;;
            if($form['city']->hasError())
            	$error_city = true;
            ?>
            <li>
             <?php echo $form['city']->renderLabel() ?>
             <?php echo $form['city'] ?>
            </li>
            
            <?php if($error_city) { ?>
            <li class="error">
            	<?php echo $form['city']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end city -->
            <?php
            $error_country_id = false;;
            if($form['country_id']->hasError())
            	$error_country_id = true;
            ?>
            <li>
             <?php echo $form['country_id']->renderLabel() ?>
             <?php echo $form['country_id'] ?>
            </li>
            
            <?php if($error_country_id) { ?>
            <li class="error">
            	<?php echo $form['country_id']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end country -->
            <?php
            $error_password = false;;
            if($form['password']->hasError())
            	$error_password = true;
            ?>
            <li>
             <?php echo $form['password']->renderLabel() ?>
             <?php echo $form['password'] ?>
            </li>
            
            <?php if($error_password) { ?>
            <li class="error">
            	<?php echo $form['password']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end password -->
            <?php
            $error_password_confirm = false;;
            if($form['password_confirm']->hasError())
            	$error_password_confirm = true;
            ?>
            <li>
             <?php echo $form['password_confirm']->renderLabel() ?>
             <?php echo $form['password_confirm'] ?>
            </li>
            
            <?php if($error_password_confirm) { ?>
            <li class="error">
            	<?php echo $form['password_confirm']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end confirm password -->
            <?php
            $error_email = false;;
            if($form['email']->hasError())
            	$error_email = true;
            ?>
            <li>
             <?php echo $form['email']->renderLabel() ?>
             <?php echo $form['email'] ?>
            </li>
            
            <?php if($error_email) { ?>
            <li class="error">
            	<?php echo $form['email']->renderError() ?>
            </li>
            <?php } ?>
            <!-- end email -->
          </ul>
        </form>
      </div>
      <div class="fr col">
        <ul>
            <?php
            $error_manufacturer = false;;
            if($form['manufacturer']->hasError())
            	$error_manufacturer = true;
            ?>
            <li>
             <?php echo $form['manufacturer']->renderLabel() ?>
             <?php echo $form['manufacturer'] ?>
            </li>
            
            <?php if($error_manufacturer) { ?>
            <li class="error">
            	<?php echo $form['manufacturer']->renderError() ?>
            </li>
            <?php } ?>
          <!-- end manufacturer -->
            <?php
            $error_device_id = false;;
            if($form['device_id']->hasError())
            	$error_device_id = true;
            ?>
            <li>
             <?php echo $form['device_id']->renderLabel() ?>
             <?php echo $form['device_id'] ?>
            </li>
            
            <?php if($error_device_id) { ?>
            <li class="error">
            	<?php echo $form['device_id']->renderError() ?>
            </li>
            <?php } ?>
          <li class="fr"><img src="images/moto-flipout.png" alt=" " /></li>
          <!-- end device -->
            <?php
            $error_terms_conditions = false;;
            if($form['terms_conditions']->hasError())
            	$error_terms_conditions = true;
            ?>
            <li>
             <?php echo $form['terms_conditions'] ?>
             <span><?php echo $form['terms_conditions']->renderHelp() ?></span>
            </li>
            
            <?php if($error_terms_conditions) { ?>
            <li class="error">
            	<?php echo $form['terms_conditions']->renderError() ?>
            </li>
            <?php } ?>
          <!-- end terms and conditions -->
            <?php
            $error_is_newsletter_subscriber = false;;
            if($form['is_newsletter_subscriber']->hasError())
            	$error_is_newsletter_subscriber = true;
            ?>
            <li>
             <?php echo $form['is_newsletter_subscriber'] ?>
             <span><?php echo $form['is_newsletter_subscriber']->renderHelp() ?></span>
            </li>
            
            <?php if($error_is_newsletter_subscriber) { ?>
            <li class="error">
            	<?php echo $form['is_newsletter_subscriber']->renderError() ?>
            </li>
            <?php } ?>
          <!-- end newsletter -->
          <li class="fr buttonplacement">
<!--        <button onclick="$('#newCustomerForm').submit();" style="cursor: pointer"><?php echo __('Next') ?></button>-->
          </li>
        </ul>
      </div>
    </div>
	<?php include_partial('steps_indicator', array('active_step'=>1)) ?>
  </div>
<script>



</script>