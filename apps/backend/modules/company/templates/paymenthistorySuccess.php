<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php if ($sf_user->hasFlash('message')): ?>
<div style="color:#FF0000">
 <?php echo __($sf_user->getFlash('message')) ?>
</div>
<?php endif; ?>
<?Php if($companyval!=''){?><div id="sf_admin_container">
	<div id="sf_admin_content">
            <a href="<?php echo url_for('employee/index').'?company_id='.$companyval."&filter=filter" ?>" class="external_link" target="_self">Employees</a>
            <a href="<?php echo url_for('company/usage').'?company_id='.$companyval; ?>" class="external_link" target="_self">Usage</a>
            <a href="<?php echo url_for('company/Paymenthistory').'?company_id='.$companyval.'&filter=filter' ?>" class="external_link" target="_self">Payment History</a>
        </div>
    </div>
<?}?>

<table width="75%" cellspacing="0" cellpadding="0" class="callhistory" style="float: left;">

<tr>
    <th colspan="4"  style="text-align: left; height: 35px">Payment History</th>
</tr>

<tr style="background-color: #CCCCFF;color: #000000;">
    <th align="left"><?php echo __('Date &amp; Time') ?></th>
    <th align="left"><?php echo __('Company &amp; Name') ?></th>
    <th align="left"><?php echo __('Description') ?></th>
    <th align="left"><?php echo __('Amount') ?></th>
</tr>
<?php 
$amount_total = 0;
$incrment=1;
foreach($transactions as $transaction):

if($incrment%2==0){
$colorvalue="#FFFFFF";
}else{

$colorvalue="#EEEEFF";
}
$incrment++;
?>
<tr  style="background-color:<?php echo $colorvalue;?>">
    <td><?php echo  $transaction->getCreatedAt() ?></td>
    <td><?php echo ($transaction->getCompany()?$transaction->getCompany():'N/A')?></td>
    <td><?php echo $transaction->getDescription() ?></td>
    <td><?php echo $transaction->getAmount(); $amount_total += $transaction->getAmount(); echo ('SEK'); ?></td>
</tr>
<?php endforeach; ?>
<?php if(count($transactions)==0): ?>
<tr>
    <td colspan="5"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
</tr>
<?php else: ?>
<tr>
    <td colspan="3" align="right"><strong>Total:&nbsp;&nbsp;</strong></td>
    <td><?php echo format_number($amount_total); echo ('SEK');echo php_info(); ?></td>
    <td>&nbsp;</td>
</tr>	
<?php endif; ?>
</table>
