<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Refill') ) ) ?>
<?php
$customer_form = new CustomerForm($customer);
$customer_form->unsetAllExcept(array('auto_refill_amount', 'auto_refill_min_balance'));

$is_auto_refill_activated = $customer_form->getObject()->getAutoRefillAmount()!=null;
?>
<script type="text/javascript">

	function checkForm()
	{
		var objForm = document.getElementById("refill");
		var valid = true;

		$('#total').val($('#extra_refill').val()*100);
                   att1= $('#user_attr_1').val();
                 att2= $('#user_attr_2').val();
                att3= $('#user_attr_3').val();
                var accepturlstr = "<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@epay_refill_accept', true)  ?>?user_attr_1="+att1+"&user_attr_2="+att2+"&user_attr_3="+att3+"&accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount="+$('#extra_refill').val()*100;
                 var callbackurlstr = "<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@dibs_refill_accept', true)  ?>?user_attr_1="+att1+"&user_attr_2="+att2+"&user_attr_3="+att3+"&accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount="+$('#extra_refill').val()*100;
                $('#accepturl').val(accepturlstr);
                 $('#callbackurl').val(callbackurlstr);

		if(isNaN(objForm.amount.value) || objForm.amount.value < <?php echo 0//$amount ?>)
		{
			alert("<?php echo __('amount error') ?>!");
			objForm.amount.focus();

			valid = false;
		}

		

		

		return valid;
	}

	function toggleAutoRefill()
	{
		document.getElementById('user_attr_2').disabled = ! document.getElementById('user_attr_1').checked;
		document.getElementById('user_attr_3').disabled = ! document.getElementById('user_attr_1').checked;
                      if(document.getElementById('user_attr_1').checked){
                $("#autorefilop").html('<input type="hidden" name="maketicket" value="foo" />');
                   $('#user_attr_1').val(1);
		}else{
                    
                     $("#autorefilop").html('');  
                     $('#user_attr_1').val(0);
                }
	}

	

	$(document).ready(function(){
		

		toggleAutoRefill();
	});




</script>
<form action="https://payment.architrade.com/paymentweb/start.action"  method="post" id="refill" onsubmit="return checkForm()">
  <div class="left-col">
    <?php include_partial('navigation', array('selected'=>'refill', 'customer_id'=>$customer->getId())) ?>
	<div class="split-form">
      <div class="fl col">
         <ul>
         	<!-- customer product -->
 			<?php
            $error_customer_product = false;;
            if($form['customer_product']->hasError())
            	$error_customer_product = true;
            ?>
            <?php if($error_customer_product) { ?>
            <li class="error">
            	<?php echo $form['customer_product']->renderError() ?>
            </li>
            <?php } ?>
            <li>
              <label for="customer_product"><?php echo __('Produkt:') ?></label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $form['customer_product'] ?>
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
        <?php
        echo $form->renderHiddenFields();
        define("DIBS_MD5KEY2","r!oRvYT8}L5%,7XFj~Rlr$+Y[W3t3vho");
        define("DIBS_MD5KEY1","cBI&R8y*KsGD.o}1z^WF]HqK5,*R[Y^w");
        //define("PATH_WEB","http://landncall.zerocall.com/");
        $part2 = rand (99,99999);
        $part3 = date("s");
        $randomOrderId = $order->getId().$part2.$part3;
        $md5key   =  md5(DIBS_MD5KEY2.md5(DIBS_MD5KEY1.'merchant=90049676&orderid='.$randomOrderId.'&currency=752&amount='.$order->getExtraRefill()));
        ?>

        <input type="hidden" name="merchant" value="90049676" />
        <input type="hidden" name="amount" id="total" value="<?php echo $order->getExtraRefill(); ?>" />
        <input type="hidden" name="currency" value="752" />
        <input type="hidden" name="orderid" value="<?php echo $randomOrderId; ?>" />
        <input type="hidden" name="textreply" value="true" />
        <input type="hidden" name="test" value="foo" />
        <input type="hidden" name="lang" value="sv" />
        <input type="hidden" name="account" value="YTIP" />
        <input type="hidden" name="addfee" value="0" />
        <input type="hidden" name="status" value="" />
            <div id="autorefilop" >
                          </div>
        <!--  <input type="hidden" name="md5key" value="<?php echo $md5key;?>">-->
        <input type="hidden" name="cancelurl" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@epay_refill_reject', true)  ?>?accept=cancel&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=<?php echo $order->getExtraRefill(); ?>">
        <input type="hidden" name="callbackurl" id="callbackurl" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@dibs_refill_accept', true)  ?>?accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=<?php echo $order->getExtraRefill(); ?>">
        <input type="hidden" name="accepturl" id="accepturl" value="<?php echo sfConfig::get('app_epay_relay_script_url').url_for('@epay_refill_accept', true)  ?>?accept=yes&subscriptionid=&orderid=<?php echo $order->getId(); ?>&amount=<?php echo $order->getExtraRefill(); ?>">
             
        <span class="refillButton">
                <input type="submit" class="butonsigninsmall" name="button" style="cursor: pointer;float: right; margin-left: 134px; margin-top: -10px;"  value="<?php echo __('Fyll på') ?>" >			
          </span>
       </div>
      <div class="split-form" style="border: 1px dotted #FFF899; padding: 1px;">  
   
        <ul>
            <!-- auto fill -->
                       
            <li>&nbsp;</li>
            <li>
                
                Smidigaste sättet att fylla på samtalspotten är 
                att aktivera automatisk <br />påfyllning (nedan). Då behöver
                du inte oroa dig för att potten tar slut.<br /> 
                Särskilt viktigt vid t.ex. utlandsresa då det kan vara svårt att fylla på på annat sätt.<br /><br />
                Smidigaste sättet att fylla på samtalspotten är att aktivera automatisk<br />
                påfyllning (nedan). Då behöver du inte oroa dig för att potten tar slut.<br />

 
            </li>
            <li>
                <label><?php 
              $switch_class = $is_auto_refill_activated?'':'off';
              echo __('Automatisk påfyllning är: <span><u>%1%</u></span>', array('%1%'=> $is_auto_refill_activated?'Aktivt':'Inte aktivt')) ?></label>
            </li> 
            <li>
            	<input type="checkbox" class="fl" style="width:20px;" onchange="toggleAutoRefill()" name="user_attr_1" id="user_attr_1" <?php echo $is_auto_refill_activated?'checked="checked"':'' ?>  value="1" />
 				<label for="user_attr_1" style="padding-top:0; text-indent: 5px;">Jag vill aktivera funktionen automatisk påfyllning</label>&nbsp;&nbsp;
            </li>
            <li id="user_attr_3_field">
                <label for="user_attr_3" style="margin-right: 50px;"><?php echo __('Auto refill minimum balance:') ?></label>
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
    </div><!-- end form-split -->
  </div>
</form>
  <?php include_partial('sidebar') ?>
