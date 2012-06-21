<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Refill') ) ) ?>
<?php
$customer_form = new CustomerForm($customer);
$customer_form->unsetAllExcept(array('auto_refill_amount', 'auto_refill_min_balance'));

$is_auto_refill_activated = $customer_form->getObject()->getAutoRefillAmount()!=null;
?>
 <?php

        $part2 = rand (99,99999);
        $part3 = date("s");
        $randomOrderId = $order->getId().$part2.$part3;
           ?>
<script type="text/javascript">



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




</script>
<?php   
if($is_auto_refill_activated){  ?>  <div class="left-col">
	   <?php include_partial('navigation', array('selected'=>'refill', 'customer_id'=>$customer->getId())) ?>
    
    <div style="width:500px;">
    
    
     <div  style="width:500px;clear:both;"> <br/> <br/>
   <b>  Automatisk påfyllning är:<span style="text-decoration:underline"> aktiv</span>
   </b>
     
     <br/> <br/>


Om ditt kreditkort som är registrerat för tjänsten av
någon anledning inte längre är aktivt kan du avaktivera
tjänsten och sedan aktivera den på nytt med ett annat
kreditkort.
 
     
      </div> <br/> 
               <br/>
     
    <div  style="width:500px;">
    <div style="float:left;width:250px;font-weight:bold;"> Du har valt automatisk påfyllning när potten underskrider: </div>
    <div  style="margin-left: 20px;float:left;width:100px;font-weight:bold;"> <?php echo   $customer_form->getObject()->getAutoRefillMinBalance() ?> SEK</div>
    <div  style="float:left;width:150px;"></div> 
    </div>
  
    <div  style="width:500px;clear:both;">
               <br/>
    <div  style="float:left;width:250px;font-weight:bold; ">Potten fylls då på med:</div>
    <div  style="margin-left: 20px;float:left;width:100px;font-weight:bold;">  <?php echo   $customer_form->getObject()->getAutoRefillAmount() ?> SEK</div>
    <div style="float: left; margin-top: 61px; text-align: left; width: 134px;">
    <form method="post" action="<?php echo sfConfig::get('app_main_url'); ?>customer/deActivateAutoRefill">
    <input type="hidden" name="customer_id" value="<?php echo   $customer_form->getObject()->getId() ?>" />
                <input type="submit" class="butonsigninsmall" name="button" style="cursor: pointer;float: right; margin-left: 134px; margin-top: -10px;"  value="<?php echo __('Avaktivera') ?>" >
                </form>			
          </div>
    </div>
     
</div>
</div>
    <?php
}else{ ?>
	
	
  <div class="left-col">
     
    <?php include_partial('navigation', array('selected'=>'refill', 'customer_id'=>$customer->getId())) ?>
	<div class="split-form">
    <div style="width:500px;">
              <div> Smidigaste sättet att fylla på samtalspotten är 
                att aktivera automatisk <br />påfyllning (nedan). Då behöver
                du inte oroa dig för att potten tar slut.<br /> 
                Särskilt viktigt vid t.ex. utlandsresa då det kan vara svårt att fylla på på annat sätt.<br /><br /></div> 
            <div>     <b style="text-decoration:underline;">Automatisk påfyllning</b> </div>
                 <br />
              <div>   <b>Automatisk påfyllning är: ej aktiv</b></div>
                
      <div class="fl col">
      <div class="split-form">  
   <form action="https://payment.architrade.com/paymentweb/start.action" method="post" id="frmarchitrade" >
  <input type="hidden" name="merchant" value="90049676" />
  <input type="hidden" name="amount" value="1" />
      <input type="hidden" name="customerid" value="<?php echo   $customer_form->getObject()->getId() ?>" />
  <input type="hidden" name="currency" value="752" />
  <input type="hidden" name="orderid" value="<?php echo $randomOrderId; ?>" />

<!--<input type="hidden" name="test" value="yes" />-->

  <input type="hidden" name="calcfee" value="yes" />
   <input type="hidden" name="account" value="YTIP" />
  <input type="hidden" name="lang" value="sv" />
  <input type="hidden" name="preauth" value="true">
  <input type="hidden" name="cancelurl" value="<?php echo sfConfig::get('app_main_url'); ?>customer/dashboard" />
  <input type="hidden" name="callbackurl" id="idcallbackURLauto" value="<?php echo sfConfig::get('app_main_url'); ?>pScripts/activateAutoRefill?customerid=<?php echo   $customer_form->getObject()->getId() ?>" />
  <input type="hidden" name="accepturl" value="<?php echo sfConfig::get('app_main_url'); ?>customer/dashboard" />
 <div style="width:348px;float:left;">
        <ul>
            <!-- auto fill -->
                       
           
           
            <li id="user_attr_3_field">
                <label for="user_attr_3" style="margin-right: 50px;"><?php echo __('Fyll på automatiskt <br /> när potten understiger:') ?></label>
                &nbsp;
			  <?php echo $customer_form['auto_refill_min_balance']->render(array(
			  										'name'=>'user_attr_3',
			  										'style'=>'width: 80px;'
			  									)) 
			  ?> SEK        
            </li>
            
            
            <li id="user_attr_2_field">
                 <label for="user_attr_2" style="margin-right: 50px;"><?php echo __('Auto refill amount:') ?></label>              
		 &nbsp; <?php echo $customer_form['auto_refill_amount']->render(array(
			  													'name'=>'user_attr_2',
                                                                                                                                'style'=>'width: 80px;'
			  												)); 
			  ?> SEK&nbsp;           
            </li> </ul>
            </div>
          <div style="width:135px;float:left;padding-top:15px;">  <input type="submit" class="butonsigninsmall" name="button" style="cursor: pointer;float: right;width:132px"  value="<?php echo __('Aktivera') ?>" >	</div>
  </form>
  </div>
    
<br/>
<br/>
  <form action="https://payment.architrade.com/paymentweb/start.action"  method="post" id="refill" >
     <div style="width:500px;">
     <div  style="width:340px;float:left;">    <ul>
         	<!-- customer product -->
 			  <li>
              <label for="customer_product" style="text-decoration:underline;"><?php echo __('Manuell påfyllning:') ?></label>
             
            </li>
          	<!-- extra_refill -->
            <?php
            $error_extra_refill = false;;
            if($form['extra_refill']->hasError())
            	$error_extra_refill = true;
            ?>
            <?php if($error_extra_refill) { ?>
            <li class="error">
            	<?php echo $form['extra_refill']->renderError() ?>
            </li>
            <?php } ?>
            <li>
              <label for="extra_refill"><?php echo __('Välj belopp att fylla på med:') ?></label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;<?php echo $form['extra_refill']?> SEK
            </li>

            <?php if($sf_user->hasFlash('error_message')): ?>
            <li class="error">
            	<?php echo $sf_user->getFlash('error_message'); ?>
            </li>
            <?php endif; ?>


          </ul>
  
        <!-- hidden fields -->
      
        <input type="hidden" name="merchant" value="90049676" />
        <input type="hidden" name="amount" id="total" value="" />
        <input type="hidden" name="currency" value="752" />
        <input type="hidden" name="orderid" value="<?php echo $randomOrderId; ?>" />
<!--        <input type="hidden" name="test" value="yes" />-->
        <input type="hidden" name="lang" value="sv" />
        <input type="hidden" name="account" value="YTIP" />
        <input type="hidden" name="addfee" value="0" />
        <input type="hidden" name="status" value="" />
       <input type="hidden" name="cancelurl" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@epay_refill_reject', true)  ?>?accept=cancel&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=<?php echo $order->getExtraRefill(); ?>" />
        <input type="hidden" name="callbackurl" id="idcallbackurl" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@dibs_refill_accept', true)  ?>?accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=" />
        <input type="hidden" name="accepturl" id="idaccepturl" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@epay_refill_accept', true)  ?>?accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=<?php echo $order->getExtraRefill(); ?>" />
        <input type="hidden" id="callbackurlfixed" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@dibs_refill_accept', true)  ?>?accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=" />
            </div>
          <div style="width:140px;float:left;padding-top:30px;">   
       
                <input type="submit" class="butonsigninsmall" name="button" style="cursor: pointer;float: right; margin-left: 134px; margin-top: -10px;"  value="<?php echo __('Fyll på') ?>" >			
        </div>
        </div></form> 
       </div>
      
    </div><!-- end form-split -->
  </div><div style="clear:both"></div>
</div>
 <?php
}

?>

  <?php include_partial('sidebar') ?>
