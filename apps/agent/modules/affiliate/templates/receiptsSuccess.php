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
<h2><?php echo __('Registration Receipts') ?> (<?php echo (count($registrations))." receipts" ?>)</h2>
<table cellspacing="0" width="100%" class="summary">	
	<tr>
		<th>&nbsp;</th>
		<th><?php echo __('Date') ?></th>
		<th><?php echo __('Customer name') ?></th>
		<th><?php echo __('Mobile Number') ?></th>
		<th><?php echo __('Amount Refilled') ?></th>
		<th><?php echo __('Description') ?></th>
		<th><?php echo __('Show Receipt') ?></th>
		
	</tr>
	<?php 
	$i = 0;
	foreach($registrations as $registration):
	?>
	<tr <?php echo 'bgcolor="'.($i%2 == 0?'#f0f0f0':'#ffffff').'"' ?>>
		<td><?php echo ++$i ?>.</td>
             
		<td><?php echo $registration->getCreatedAt(); ?>
		<td><?php 
			$customer = CustomerPeer::retrieveByPK($registration->getCustomerId());
			//$customer2 = CustomerPeer::retrieveByPK(72);
			//echo $transaction->getCustomerId();
			echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
			?>
            
		</td>
		<td><?php echo $customer->getMobileNumber()?></td>
		<td >
			<?php echo BaseUtil::format_number($registration->getAmount()) ?>
		</td>
		<td>
		<?php echo $registration->getDescription() ?>
		</td>
		<td><a href="#" class="receipt" onclick="javascript: window.open('<?php echo url_for('affiliate/printReceipt?tid='.$registration->getId(), true) ?>')"> Reciept</a>
		</td>
		
	</tr>
	<?php endforeach; ?>
        
</table>


<h2><?php echo __('Refill Receipts') ?> (<?php echo (count($refills))." receipts" ?>)</h2>
<table cellspacing="0" width="100%" class="summary">
	<tr>
		<th>&nbsp;</th>
		<th><?php echo __('Date') ?></th>
		<th><?php echo __('Customer name') ?></th>
		<th><?php echo __('Mobile Number') ?></th>
		<th><?php echo __('Amount Refilled') ?></th>
		<th><?php echo __('Description') ?></th>
		<th><?php echo __('Show Receipt') ?></th>

	</tr>
	<?php
	$i = 0;
	foreach($refills as $refill):
	?>
	<tr <?php echo 'bgcolor="'.($i%2 == 0?'#f0f0f0':'#ffffff').'"' ?>>
		<td><?php echo ++$i ?>.</td>

		<td><?php echo $refill->getCreatedAt(); ?>
		<td><?php
			$customer = CustomerPeer::retrieveByPK($refill->getCustomerId());
			//$customer2 = CustomerPeer::retrieveByPK(72);
			//echo $transaction->getCustomerId();
			echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
			?>

		</td>
		<td><?php echo $customer->getMobileNumber()?></td>
		<td >
			<?php echo BaseUtil::format_number($refill->getAmount()) ?>
		</td>
		<td>
		<?php echo $refill->getDescription() ?>
		</td>
		<td><a href="#" class="receipt" onclick="javascript: window.open('<?php echo url_for('affiliate/printReceipt?tid='.$refill->getId(), true) ?>')"> <?php echo __('Receipt') ?></a>
		</td>

	</tr>
	<?php endforeach; ?>

</table>

<h2><?php echo __('Mobile Number Change Receipts') ?> (<?php echo (count($numberchanges))." receipts" ?>)</h2>
<table cellspacing="0" width="100%" class="summary">
	<tr>
		<th>&nbsp;</th>
		<th><?php echo __('Date') ?></th>
		<th><?php echo __('Customer name') ?></th>
		<th><?php echo __('Mobile Number') ?></th>
		<th><?php echo __('Change Number Amount') ?></th>
		<th><?php echo __('Description') ?></th>
		<th><?php echo __('Show Receipt') ?></th>

	</tr>
	<?php
	$i = 0;
	foreach($numberchanges as $numberchange):
	?>
	<tr <?php echo 'bgcolor="'.($i%2 == 0?'#f0f0f0':'#ffffff').'"' ?>>
		<td><?php echo ++$i ?>.</td>

		<td><?php echo $numberchange->getCreatedAt(); ?>
		<td><?php
			$customer = CustomerPeer::retrieveByPK($numberchange->getCustomerId());
			//$customer2 = CustomerPeer::retrieveByPK(72);
			//echo $transaction->getCustomerId();
			echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
			?>

		</td>
		<td><?php echo $customer->getMobileNumber()?></td>
		<td >
			<?php echo BaseUtil::format_number($numberchange->getAmount()) ?>
		</td>
		<td>
		<?php echo $numberchange->getDescription() ?>
		</td>
		<td><a href="#" class="receipt" onclick="javascript: window.open('<?php echo url_for(sfConfig::get('app_main_url').'affiliate/printReceipt?tid='.$numberchange->getId(), true) ?>')"> <?php echo __('Receipt') ?></a>
		</td>

	</tr>
	<?php endforeach; ?>

</table>

        <?php echo __('Total Receipts for transactions:') ?> <?php echo (count($registrations)+count($refills)+count($numberchanges)) ?>
</div>