<?php
use_helper('I18N');
use_helper('Number');
?>
<style>
	p {
		margin: 8px auto;
	}
	
	table.receipt {
		width: 600px;
		//font-family: arial;
		//font-size: .7em;
		
		border: 2px solid #ccc;
	}
	
	table.receipt td, table.receipt th {
		padding:5px;
	}
	
	table.receipt th {
		text-align: left;
	}
	
	table.receipt .payer_details {
		padding: 10px 0;
	}
	
	table.receipt .receipt_header, table.receipt .order_summary_header {
		font-weight: bold;
		text-transform: uppercase;
	}
	
	table.receipt .footer
	{
		font-weight: bold;
	}
	
	
</style>

<?php
$wrap_content  = isset($wrap)?$wrap:false;

//wrap_content also tells  wheather its a refill or 
//a product order. we wrap the receipt with extra
// text only if its a product order.

 ?>
 
<?php if($wrap_content): ?>
	<p><?php echo __('Dear Customer') ?></p>
	
	<p>
	<?php echo __('Thank you for ordering <b>%1%</b> and becoming SmartSim Customer. We welcome you to a new and huge mobile world.', array('%1%'=>$order->getProduct()->getName())) ?> Ditt kundnummer &auml;r  <?php echo $customer->getUniqueid();?>. Det kan du anv&auml;nda i din kontakt med kundservice.
	</p>
	
	<p>
	<?php echo __('With <b>%1%</b>, you can easily call your friends and family for free.', array('%1%'=>$order->getProduct()->getName())) ?></p>
	
	<p>
	<?php echo __('Below is the receipt of the product indicated.') ?>
	</p>
	<br />
<?php endif; ?>
<table width="600px">
<tr style="border:0px solid #fff">
		<td colspan="4" align="right" style="text-align:right; border:0px solid #fff"><?php echo image_tag('http://landncall.zerocall.com/images/logo.gif');?></td>
	</tr>
</table>
<table class="receipt" cellspacing="0" width="600px">
	
  <tr bgcolor="#CCCCCC" class="receipt_header">   	
    <th colspan="3"><?php echo __('Order Receipt') ?></th>
    <th><?php echo __('Order No.') ?> <?php echo $order->getId() ?></th>
  </tr>
  <tr> 
    <td colspan="4" class="payer_summary">
      <?php echo __('Customer Number') ?>   <?php echo $customer->getUniqueId(); ?><br/>
      <?php echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName())?><br/>
      <?php echo $customer->getAddress() ?><br/>
      <?php echo sprintf('%s, %s', $customer->getCity(), $customer->getPoBoxNumber()) ?><br/>
      <?php 
	  $eC = new Criteria();
	  $eC->add(EnableCountryPeer::ID, $customer->getCountryId());
	  $eC = EnableCountryPeer::doSelectOne($eC);
	  echo $eC->getName();
	  //echo $customer->getCountry()->getName() ?> 
      <br /><br />
      
      
      <?php    $unid=$customer->getUniqueid();
       $usvar=substr($unid,0,2);
      if($usvar=="us"){?>
         <?php echo __('US Mobile Number') ?>: <br />
      <?php    $eCu = new Criteria();
	  $eCu->add(UsNumberPeer::CUSTOMER_ID, $customer->getId());
	  $eCum = UsNumberPeer::doSelectOne($eCu);
	  echo $eCum->getUsMobileNumber();  ?>
     
<?php     }else{   ?>
     <?php     $customer->getMobileNumber()    ?>
      <?php echo __('Mobile Number') ?>: <br />
      <?php echo $customer->getMobileNumber() ?>
 
<?php }
?>      



    </td>
  </tr>
  <tr class="order_summary_header" bgcolor="#CCCCCC"> 
    <td><?php echo __('Date') ?></td>
    <td><?php echo __('Description') ?></td>
    <td><?php echo __('Quantity') ?></td>
    <td><?php echo __('Amount') ?></td>
  </tr>
  <tr> 
    <td><?php echo $order->getCreatedAt('m-d-Y') ?></td>
    <td>
    <?php if ($order->getIsFirstOrder())
    {
        echo $order->getProduct()->getName();
        if($transaction->getDescription()=="Registrering inkl. taletid"){
          echo "<br />[Smartsim inklusive pott]";
        }else{
            echo  '<br />['. $transaction->getDescription() .']';
        }		
    }
    else
    {
        if($transaction->getDescription()=="LandNCall AB Refill"){
          echo "Refill ".$transaction->getAmount();
        }else{
          echo $transaction->getDescription(); 
        }		   	
    }
    ?>
	</td>
    <td><?php echo $order->getQuantity() ?></td>
    <td><?php echo format_number($subtotal = $transaction->getAmount()-$vat) //($order->getProduct()->getPrice() - $order->getProduct()->getPrice()*.2) * $order->getQuantity()) ?></td>
  </tr>
  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer"> 
    <td>&nbsp;</td>
    <td><?php echo __('Subtotal') ?></td>
    <td>&nbsp;</td>
    <td><?php echo format_number($subtotal) ?></td>
  </tr>
  <tr class="footer"> 
    <td>&nbsp;</td>
    <td><?php echo __('VAT') ?> (<?php echo $vat==0?'0%':'25%' ?>)</td>
    <td>&nbsp;</td>
    <td><?php echo format_number($vat) ?></td>
  </tr>
    <tr class="footer"> 
    <td>&nbsp;</td>
    <td><?php echo __('Delivery Charges') ?></td>
    <td>&nbsp;</td>
    <td><?php echo format_number($transaction->getPostalCharges()) ?></td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Total') ?></td>
    <td>&nbsp;</td>
    <td><?php echo format_number($transaction->getAmount()) ?></td>
  </tr>
  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer">
    <td class="payer_summary" colspan="4" style="font-weight:normal; white-space: nowrap;"> 
    SmartSim &nbsp;&nbsp;&nbsp;Smedeholm 13 B&nbsp;&nbsp;&nbsp; 2730 København</td>    
  </tr>
</table>
<?php if($wrap_content): ?>
<br />
<p>
<?php
	$c = new  Criteria();
	$c->add(GlobalSettingPeer::NAME, 'expected_delivery_time_agent_order');
	
	$global_setting_expected_delivery = GlobalSettingPeer::doSelectOne($c);
	
	if ($global_setting_expected_delivery)
		$expected_delivery = $global_setting_expected_delivery->getValue();
	else
		$expected_delivery = "3 business days";
?>
<p style="font-weight: bold;">
	<?php echo __('You will receive your package within %1%.', array('%1%'=>$expected_delivery)) ?> 
</p>
<?php endif; ?>

<p style="font-weight: bold;">
	<?php echo __('If you have any questions please feel free to contact our customer support center at'); ?>
	<a href="mailto:support@smartsim.se">support@smartsim.se</a>
</p>

<p style="font-weight: bold;"><?php echo __('Cheers') ?></p>

<p style="font-weight: bold;">
<?php echo __('Support') ?>&nbsp;SmartSim
</p>
