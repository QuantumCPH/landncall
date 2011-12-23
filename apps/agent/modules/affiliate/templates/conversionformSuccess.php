<script type="text/javascript" language="javascript">

jQuery(function(){

jQuery('#conversion_form').validate({

});
});
</script>


<form  name="conversion_form" method="post"  class="split-form-sign-up" id="conversion_form" action="registrationstep1">

       <h2><?php echo __('Convert Zerocall Free customer to Zerocall Out') ?> </h2> 
	<ul class="fl col">
	
	      
            <li><label> <?php echo __('Mobile Number') ?>  </label>
                <input  type="text" name="mobileno" id="mobileno" class="required number" maxlength="8"  minlength="8" />
	          </li>
               <li style="float:right"><label>&nbsp;  </label>
           <input  type="submit" name="Convert" value="<?php echo __('Convert') ?>" />
	          </li>
	
			  
	</ul>
</form>


