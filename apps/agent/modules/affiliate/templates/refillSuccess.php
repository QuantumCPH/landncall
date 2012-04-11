<div id="sf_admin_container"><h1><?php echo __('Refill') ?></h1></div>
<div class="borderDiv"> 
<form method="post"  class="split-form-sign-up" id="refill_form" action="<?php url_for('affiliate/refill') ?>">

        <?php if($error_msg){?>
            <strong><?php echo $error_msg ?></strong>
        <?php } ?>
	<ul class="fl col">
	
            
            <li>
             <?php echo $form['mobile_number']->renderLabel() ?>
             <?php echo $form['mobile_number'] ?>
                <label class="validnumber">Enter mobile number without leading 0</label>
             <?php if ($error_mobile_number): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_mobile_number ?></div>
            </li>
            
             <?php
            $error_extra_refill = false;
            if($form['extra_refill']->hasError())
            	$error_extra_refill = true;
            ?>
            <li>
             <?php echo $form['extra_refill']->renderLabel() ?>
             <?php echo $form['extra_refill'] ?>
             <?php if ($error_extra_refill): ?>
             <span id="cardno_decl" class="alertstep1">
			  	<?php echo image_tag('../zerocall/images/decl.png', array('absolute'=>true)) ?>
			 </span>
			 <?php endif; ?>
             <div class='inline-error'><?php echo $error_extra_refill?></div>
             <?php
          if( $browser->getBrowser() == Browser::BROWSER_IE  )
          {
                   ?>
          <li class="fr buttonplacement" style="margin-left:20px ">
               <input type="submit" value="Next" style="margin-left:115px;">
          </li>

          <?php } else{ ?>
	          <li class="fr buttonplacement">
	            <button onclick="$('#refill_form').submit();" style="cursor: pointer; left: -115px"><?php echo __('Refill') ?></button>
	          </li>
	<?php }?>
			  
	</ul>

</form>
 <div class="clr"></div>
</div>
