<script type="text/javascript">
jQuery(function(){
    jQuery('#changenumber').validate({
        rules: {
            existingNumber:{
                required: true,
                minlength: 8,
                digits: true
            },
           newNumber:{
                required: true,
                minlength: 8,
                digits: true
            }
        },
        messages: {
            existingNumber:{
                required: "Please Enter Old Mobile Number",
                minlength: "Atleast 8 digits are required",
                digits: "Please Enter only digits"
            },
            newNumber:{
                required: "Please Enter New Mobile Number",
                minlength: "Atleast 8 digits are required",
                digits: "Please Enter only digits"
            }
        }
    });
});
</script>
<div id="sf_admin_container"><h1><?php echo __('Change Number') ?></h1></div>
<div class="borderDiv">
<form method="post" name="changenumber" id="changenumber" class="split-form-sign-up" action="<?php echo url_for(sfConfig::get('app_main_url').'affiliate/changenumber') ?>">

    	<ul class="fl col">


            <li>
                <label><?php echo __('Old Mobile Number') ?></label>
                <input type="text" name="existingNumber"/>
            </li>
            <li>
                <label><?php echo __('New Mobile Number') ?></label>
                <input type="text" name="newNumber"/>
            </li>
            <li>
                <label><?php echo __('Country') ?></label>
                <select name="countrycode" id="countrycode" >
                    <?php
                    $enableCountry = new Criteria();
                    $country = CountryPeer::doSelect($enableCountry);
                    foreach($country as $countries){?>
                        <option value="<?php echo $countries->getCallingCode(); ?>" <?php if($countries->getCallingCode()==46){echo 'selected';}?>><?php echo $countries->getName(); ?></option>
                    <?php
                    }
            ?>
                </select>
            </li>
            <li>
                <label><?php echo __('Product Name') ?></label>
                <?php  $c = new Criteria();
                $c->add(ProductPeer::ID, 18);
                $product = ProductPeer::doSelectOne($c);  ?>
                <select name="product">
                    <option value="<?php echo $product->getID(); ?>" ><?php echo $product->getName(); ?></option>
                </select>
            </li>
             <?php
          if( $browser->getBrowser() == Browser::BROWSER_IE  )
          {
                   ?>
          <li class="fr buttonplacement" style="margin-left:20px ">
               <input type="submit" value="Next" style="margin-left:115px;">
          </li>

          <?php } else{ ?>
	          <li class="fr buttonplacement">
	            <button onclick="$('#changenumber').submit();" style="cursor: pointer; left: -115px"><?php echo __('Next') ?></button>
	          </li>
	<?php }?>

	</ul>

</form>
<div class="clr"></div>
</div>