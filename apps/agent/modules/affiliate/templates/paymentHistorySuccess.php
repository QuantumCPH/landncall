<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<style type="text/css">
	table {
		margin-bottom: 10px;
	}
	
	table.summary td {
		font-size: 1.3em;
		font-weight: normal;
	}
</style>
<div class="report_container">
<div id="sf_admin_container"><h1><?php echo __('Payment History') ?></h1></div>

 <div class="borderDiv"> 
    <table cellspacing="0" width="100%" class="summary">
   
  <tr>
    <th width="25%"><?php echo __('Transaction Type') ?></th>
    <th width="25%"><?php echo __('Amount') ?> </th>
    <th width="25%"><?php echo __('Remaining Balance') ?> </th>
    <th width="25%"><?php echo __('Date') ?> </th>
      
  </tr>
 <?php
    $i = 0;
    foreach($agents as $agent) {
        $i++;
        ?>

  
  <tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
  <td><?php  $expensetype=$agent->getExpeneseType(); 
    if($expensetype==1){  echo __("Customer Registration");  }
    if($expensetype==2){  echo __("Customer Refill");  }
    if($expensetype==3){  echo __("Agent Account Refill");  }
    if($expensetype==4){  echo __("Customer Refill from Shop");  }
    if($expensetype==6){  echo __("Change Number");  }
    if($expensetype==9){
        $ctd = new Criteria();
        $ctd->add(TransactionDescriptionPeer::ID,$agent->getOrderDescription());        
        $tdd = TransactionDescriptionPeer::doSelectOne($ctd);
        echo $tdd->getTitle();        
    }
     ?></td>
  <td align="center"><?php  echo $agent->getAmount();   ?></td>
    <td  align="center"><?php  echo $agent->getRemainingBalance();  ?></td>
      <td><?php  echo $agent->getCreatedAt();  ?></td>
      
  </tr>
  <?php  } ?>
  </table>



</div>

</div>

