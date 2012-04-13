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
<div id="sf_admin_container"><h1><?php echo __('Reciepts For Agent Account Refills') ?></h1></div>

  <div class="borderDiv">
<table cellspacing="0" width="100%" class="summary">
	<tr>
		<th style="text-align:left">&nbsp;</th>
		<th style="text-align:left">Date</th>
		<th style="text-align:left">Amount</th>
		<th style="text-align:left">Show Reciept</th>

	</tr>
        <?php $i=0 ?>
        <?php foreach($agentOrders as $agentOrder){ ?>
        <tr <?php echo 'bgcolor="'.($i%2 == 0?'#f0f0f0':'#ffffff').'"' ?>>
            <td><?php echo ++$i ?>.</td>
            <td><?php echo $agentOrder->getCreatedAt() ?></td>
            <td><?php echo $agentOrder->getAmount() ?></td>
            <td><a href="<?php echo url_for('affiliate/printAgentReceipt?aoid='.$agentOrder->getId(), true) ?>" > Reciept</a>
            </td>
            
        </tr>

        <?php } ?>
</table>
</div></div>