<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Call History')) ) ?>

  <div class="left-col">
    <?php include_partial('navigation', array('selected'=>'callhistory', 'customer_id'=>$customer->getId())) ?>
      <div class="split-form-btn" style="margin-top: 70px;">
          
          <input type="button" class="butonsigninsmall"  name="button" onclick="window.location.href='<?php echo url_for('customer/paymenthistory', true); ?>'" style="cursor: pointer"  value="<?php echo __('Övrig historik') ?>" >
           
      </div>
      <br />
        <div class="alert_bar" style="width: 470px;">
            <?php echo __('Call history is updated after every 5-10 minutes.') ?>
        </div>
<?php if ($customer->getC9CustomerNumber() ):?>
	<div style="clear: both;"></div>
<span style="margin: 20px;">
	<center>

		<form action="/index.php/customer/c9Callhistory" method="post">
			<INPUT TYPE="submit" VALUE="<?php echo __('Se LandNCall AB Global opkaldsoversigt') ?>">
		</form>
	</center>
</span>
<?php endif; ?>
      <div class="split-form">
      <div class="fl col">
        <form action="<?php echo url_for('customer/callhistory') ?>" method="post">
          <ul>
<?php ?>
            <li>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="callhistory">
                  <tr>
                    <td class="title"><?php echo __('Date &amp; time') ?></td>
                    <td class="title" width="40%"><?php echo __('Phone Number') ?></td>
                    <td class="title"><?php echo __('Duration') ?></td>
                    <td class="title"><?php echo __('VAT') ?></td>
                    <td class="title"><?php echo __('Cost <small>(Incl. VAT)</small>') ?></td>
                  </tr>

                <?php 
                $amount_total = 0;
                foreach($callRecords as $callRecord): ?>
                <tr>
                  <td><?php  echo $callRecord->getTime()  ?>

                  </td>
                  <td><?php echo  $callRecord->getToNumber() ?></td>
                  
                  <td><?php echo $callRecord->getDurationMinutes() ?></td>
                  <td><?php echo number_format($callRecord->getVat(), 2, ',', '') ?> </td>

                  <td><?php $amount_total += $callRecord->getCallCost(); echo number_format($callRecord->getCallCost(), 2, ',', '') ?> SEK</td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($callRecords)==0): ?>
                <tr>
                	<td colspan="5"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                </tr>
                <?php else: ?>
                <tr>
                	<td colspan="4" align="right"><strong><?php echo __('Subtotal') ?></strong></td>
                	<!--
                	<td><?php echo format_number($amount_total-$amount_total*.20) ?> SEK</td>
                	 -->
                	<td><?php echo number_format($amount_total, 2, ',', '') ?> SEK</td>
                </tr>	
                <?php endif; ?>
              </table>
            </li>
            <?php if($total_pages>1): ?>
            <li>
            	<ul class="paging">
            	<?php for ($i=1; $i<=$total_pages; $i++): ?>
            		<li <?php echo $i==$page?'class="selected"':'' ?>><a href="<?php echo url_for('customer/callhistory?page='.$i) ?>"><?php echo $i ?></a></li>
            	<?php endfor; ?>
            	</ul>
            </li>
            <?php endif; ?>
          </ul>
        </form>
      </div>
    </div> <!-- end split-form -->
  </div> <!-- end left-col -->
  <?php include_partial('sidebar') ?>