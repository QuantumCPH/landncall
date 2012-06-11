<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<script>
     
	$(function() {
                $('body').css('overflow','hidden');
                $("#wrap").width("629px");
                $("#newCustomerForm").width("629px");
                $('.split-form-sign-up').width("629px");
        });
       
	</script>
<form method="post" action="<?php url_for('@signup_step1') ?>" id="newCustomerForm" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <div class="left-col">
    <div class="split-form-sign-up">
        <div class="step-details"> <strong><?php echo __('Become a Customer') ?> <span class="active">- <?php echo __('Step 1') ?>: <?php echo __('Registrera') ?> </span><span class="inactive">- <?php echo __('Step 2') ?>: <?php echo __('Payment') ?></span></strong>
            <br></br><br></br> * obligatoriska fält att att fylla i</div>
      
      <div class="fl col">
        <?php echo $form->renderHiddenFields() ?>
          <ul>
            <?php 
            $error_mobile_number = false;
            if($form['mobile_number']->hasError())
            	$error_mobile_number = true;
            ?>
            <li>
             <?php echo $form['mobile_number']->renderLabel() ?>
             <?php echo $form['mobile_number'];
              $emailWidget = new sfWidgetFormInput(array(), array('class' => 'required email'));?>
             <?php if ($error_mobile_number): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
                
             <div class='inline-error'><?php echo $error_mobile_number?$form['mobile_number']->renderError():'&nbsp;'?>
                 </div>
                <label style="font-size: x-small;float:right;width:84px;<?php if ($error_mobile_number): ?> margin-right:84px; <?php endif; ?>">ex. 0701234567</label>
            </li>
            <!-- end mobile_number -->

                <?php   if(isset($_REQUEST['pid'])  && $_REQUEST['pid']!=""){       ?>
              <li>

             <?php echo $form['product']->renderLabel() ?>  <?php
              $cp = new Criteria();
            $cp->add(ProductPeer::ID, $_REQUEST['pid']);
                $product=ProductPeer::doSelectOne($cp); ?>
                <b>
         <?php    echo $product->getName();  ?></b>
                <input type="hidden" name="customer[product]" id="customer_product" value="<?php echo $product->getId();  ?>" />
  <span id="cardno_decl" class="alertstep1">&nbsp;
  </span>
  <div class='inline-error'>&nbsp;</div>
            </li>
              <?php  }else{ ?>
            <?php
            $error_product = false;;
            if($form['product']->hasError())
            	$error_product = true;
            ?>
            <li>
             <?php echo $form['product']->renderLabel() ?>
                
             <?php echo $form['product'] ?>
             <?php if ($error_product): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_product?$form['product']->renderError():'&nbsp;'?></div>
            </li>

            <?php  } ?>
            <!--  end product -->
            <?php
            $error_first_name = false;;
            if($form['first_name']->hasError())
            	$error_first_name = true;
            ?>
            <li>
             <?php echo $form['first_name']->renderLabel() ?>
             <?php echo $form['first_name'] ?>
             <?php if ($error_first_name): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_first_name?$form['first_name']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end first name -->
            <?php
            $error_last_name = false;;
            if($form['last_name']->hasError())
            	$error_last_name = true;
            ?>
            <li>
             <?php echo $form['last_name']->renderLabel() ?>
             <?php echo $form['last_name'] ?>
             <?php if ($error_last_name): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_last_name?$form['last_name']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end last name -->
            <?php
            $error_address = false;;
            if($form['address']->hasError())
            	$error_address = true;
            ?>
            <li>
             <?php echo $form['address']->renderLabel() ?>
             <?php echo $form['address'] ?>
             <?php if ($error_address): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_address?$form['address']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end address -->
            <?php
            $error_po_box_number = false;;
            if($form['po_box_number']->hasError())
            	$error_po_box_number = true;
            ?>
            <li>
             <?php echo $form['po_box_number']->renderLabel() ?>
             <?php echo $form['po_box_number'] ?>
             <?php if ($error_po_box_number): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_po_box_number?$form['po_box_number']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end pobox number -->
            <?php
            $error_city = false;;
            if($form['city']->hasError())
            	$error_city = true;
            ?>
            <li>
             <?php echo $form['city']->renderLabel() ?>
             <?php echo $form['city'] ?>
             <?php if ($error_city): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_city?$form['city']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end city -->
            <?php
            $error_country_id = false;;
            if($form['country_id']->hasError())
            	$error_country_id = true;
            ?>
            <li style="display:none">
             <?php //echo $form['country_id']->renderLabel() ?>
             <?php echo $form['country_id'] ?>
             <?php if ($error_country_id): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_country_id?$form['country_id']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end country -->
          
            <!-- end date of birth -->
          </ul>

      </div>
      <div class="fr col">
        <ul>
            
            <?php
            $error_password = false;;
            if($form['password']->hasError())
            	$error_password = true;
            ?>
            <li>
             <?php echo $form['password']->renderLabel() ?>
             <?php echo $form['password'] ?>
             <?php if ($error_password): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_password?$form['password']->renderError():'&nbsp;'?></div>
             <label style="float:right;width:84px;<?php if ($error_mobile_number): ?> margin-right:84px; <?php endif; ?>"><?php echo __('Minimum 6 digits') ?></label>
            </li>
            <!-- end password -->
            <?php
            $error_password_confirm = false;;
            if($form['password_confirm']->hasError())
            	$error_password_confirm = true;
            ?>
            <li>
             <?php echo $form['password_confirm']->renderLabel() ?>
             <?php echo $form['password_confirm'] ?>
             <?php if ($error_password_confirm): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_password_confirm?$form['password_confirm']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end confirm password -->
            <?php
            $error_email = false;;
            if($form['email']->hasError())
            	$error_email = true;
            ?>
            <li>
             <?php echo $form['email']->renderLabel() ?>
             <?php echo $form['email'] ?>
             <?php if ($error_email): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_email?$form['email']->renderError():'&nbsp;'?></div>
            </li>
            <!-- end email -->
           
            <!-- end telecom operator -->
            
            <!-- 
          <li class="fr"><img src="<?php echo image_path('../zerocall/images/moto-flipout.png') ?>" alt=" " /></li>
           -->
          <!-- end device -->
		  <div id="container" style="width:400px;">
		  <div id="navbar" style="float: left;  width: 40%;">
            <?php
            $error_terms_conditions = false;;
            if($form['terms_conditions']->hasError())
            	$error_terms_conditions = true;
            ?>
            <?php if($error_terms_conditions) { ?>
            <li class="error">
            	<?php echo $form['terms_conditions']->renderError() ?>
            </li>
            <?php } ?>
            <li style=" width: 200px;">
             <?php echo $form['terms_conditions'] ?>
             <span><a href="http://www.landncall.com/index.php?option=com_content&view=article&id=70" target="_blank" style="outline:none"><?php echo $form['terms_conditions']->renderHelp() ?></a></span>
             </li>
			 <?php
            $error_is_newsletter_subscriber = false;;
            if($form['is_newsletter_subscriber']->hasError())
            	$error_is_newsletter_subscriber = true;
            ?>
            <?php if($error_is_newsletter_subscriber) { ?>
            <li class="error">
            	<?php echo $form['is_newsletter_subscriber']->renderError() ?>
            </li>
            <?php } ?>
            <li style="display:none">
             <?php echo $form['is_newsletter_subscriber'] ?>
             <span><?php echo $form['is_newsletter_subscriber']->renderHelp() ?></span>
            </li>
			</div>
          <!-- end newsletter -->
		  
			 <div id="content" style="float:none;clear:both;"  >
			 	<input type="submit" class="butonsigninsmall" name="submit" style="cursor: pointer;"  value="<?php echo __('Next') ?>" ></div>
			
            </li>
			</div>
          <!-- end terms and conditions -->
            
          <li class="fr buttonplacement">     </li>
        </ul>   
                 
     
      </div>
    </div>
	<?php //include_partial('signup/steps_indicator', array('active_step'=>1)) ?>
  </div>
</form>
<script type="text/javascript">
     jQuery('#customer_po_box_number').blur(function(){
        var poid=jQuery("#customer_po_box_number").val();
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
        }else{
           //var fulvalue=poida+poidb+poidc;
        }
       jQuery("#customer_po_box_number").val(fulvalue);
       //  alert(fulvalue);

        });
	$('form li em').prev('label').append(' *');
	$('form li em').remove();

        $("#customer_manufacturer").change(function() {
		var url = "<?php echo url_for('customer/getmobilemodel') ?>";
		var value = $(this).val();
			$.get(url, {device_id: value}, function(output) {
				$("#customer_device_id").html(output);
			});
	});

        $('#customer_manufacturer').trigger('change');

</script>