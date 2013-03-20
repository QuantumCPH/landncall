<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer' => $customer, 'section' => __('Refill'))) ?>
<?php
$customer_form = new CustomerForm($customer);
$customer_form->unsetAllExcept(array('auto_refill_amount', 'auto_refill_min_balance'));

$is_auto_refill_activated = $customer_form->getObject()->getAutoRefillAmount() != null;
?>
<?php
$part2 = rand(99, 99999);
$part3 = date("s");
$randomOrderId = $order->getId();
?>
<script type="text/javascript">

    /*
     
     $(document).ready(function(){
     
     $('#frmarchitrade').submit(function() {
     user_attr_2 = jQuery("#user_attr_2 option:selected").val();
     user_attr_3 = jQuery("#user_attr_3 option:selected").val();
     jQuery('#idcallbackURLauto').val(jQuery('#idcallbackURLauto').val()+"&user_attr_2="+user_attr_2+"&user_attr_3="+user_attr_3);
     return true;
     });
     
     
     $('#refill').submit(function() {
     extra_refill = jQuery("#extra_refill option:selected").val();
     extra_refill = parseInt(extra_refill)*100;
     jQuery('#idcallbackurl').val(jQuery('#callbackurlfixed').val()+extra_refill);
     jQuery('#total').val(extra_refill);
     return true;
     });
     
     
     });
     
     
     */

</script>


<br/>
<br/>
<form action="<?php echo $target ?>customer/refilTransaction"  method="post" id="refill" target="_parent" >
    <div style="width:500px;">
        <div  style="width:340px;float:left;">    <ul>
                <!-- customer product -->
                <li>
                    <label for="customer_product" style="text-decoration:underline;"><?php echo __('Manuell påfyllning:') ?></label>

                </li>
                <!-- extra_refill -->
                <?php
                $error_extra_refill = false;
                ;
                if ($form['extra_refill']->hasError())
                    $error_extra_refill = true;
                ?>
                <?php if ($error_extra_refill) { ?>
                    <li class="error">
                        <?php echo $form['extra_refill']->renderError() ?>
                    </li>
                <?php } ?>
                <li>
                    <label for="extra_refill"><?php echo __('Välj belopp att fylla på med:') ?></label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;<?php echo $form['extra_refill'] ?> SEK
                </li>

                <?php if ($sf_user->hasFlash('error_message')): ?>
                    <li class="error">
                        <?php echo $sf_user->getFlash('error_message'); ?>
                    </li>
                <?php endif; ?>


            </ul>

            <!-- hidden fields -->
            <input type="hidden" value="_xclick" name="cmd"> 
            <input type="hidden" value="1" name="no_note">
            <input type="hidden" value="SE" name="lc">
            <input type="hidden" value="SEK" name="currency_code">
            <input type="hidden" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" name="bn">
            <input type="hidden" name="first_name" value="<?php echo $customer->getCustomerFirstName(); ?>"  />
            <input type="hidden" name="last_name" value="<?php echo $customer->getCustomerLastName(); ?>"  />
            <input type="hidden" name="email" value="<?php echo $customer->getCustomerEmail(); ?>"  />
            <input type="hidden" name="city" value="<?php echo $customer->getCustomerCity(); ?>"  />
            <input type="hidden" name="zip" value="<?php echo $customer->getCustomerPoBoxNumber(); ?>"  />
            <input type="hidden" name="address1" value="<?php echo $customer->getCustomerAddress(); ?>"  />

            <input type="hidden" value="<?php echo $randomOrderId; ?>" name="item_number">
            <input type="hidden" name="cancelurl" value="http://www.smartsim.se/mina-sidor" />
            <input type="hidden" name="accepturl" id="idaccepturl" value="http://www.smartsim.se/mina-sidor" />
        </div>
        <div style="width:140px;float:left;padding-top:30px;">   

            <input type="submit" class="butonsigninsmall" name="button" style="cursor: pointer;float: right; margin-left: 134px; margin-top: -10px;"  value="<?php echo __('Fyll på') ?>" >			
        </div>
    </div></form> 
</div>

</div><!-- end form-split -->
</div><div style="clear:both"></div>
</div>


<?php include_partial('sidebar') ?>
