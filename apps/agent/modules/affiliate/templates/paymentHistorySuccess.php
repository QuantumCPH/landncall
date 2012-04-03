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
 <h2><?php echo __('Payment History') ?></h2>
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

  
  <tr <?php echo 'bgcolor="'.($i%2 == 0?'#f0f0f0':'#ffffff').'"' ?>>
  <td><?php  $expensetype=$agent->getExpeneseType(); 
    if($expensetype==1){  echo __("Customer Registration");  }
    if($expensetype==2){  echo __("Customer Refill");  }
    if($expensetype==3){  echo __("Agent Account Refill");  }
    if($expensetype==6){  echo __("Change Number");  }
     ?></td>
  <td align="center"><?php  echo $agent->getAmount();   ?></td>
    <td  align="center"><?php  echo $agent->getRemainingBalance();  ?></td>
      <td><?php  echo $agent->getCreatedAt();  ?></td>
      
  </tr>
  <?php  } ?>
  </table>



</div>



