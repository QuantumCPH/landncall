<div id="sf_admin_container">
	<div id="sf_admin_content">
	<!-- employee/list?filters[company_id]=1 -->
	<a href="<?php echo url_for('employee/index').'?company_id='.$company->getId()."&filter=filter" ?>" class="external_link" target="_self">Employees (<?php echo count($company->getEmployees()) ?>)</a>
	<a href="<?php echo url_for('company/usage').'?company_id='.$company->getId(); ?>" class="external_link" target="_self">Usage</a>
        <a href="<?php echo url_for('company/paymenthistory').'?company_id='.$company->getId().'&filter=filter' ?>" class="external_link" target="_self">Payment History</a>
        </div> <h1><?php echo __('Call History'); ?></h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>


        <tr class="headings">
                    <th width="20%"   align="left"><?php echo __('Date &amp; time') ?></th>
                    <th  width="20%"  align="left"><?php echo __('Phone Number') ?></th>
                    <th width="10%"   align="left"><?php echo __('Duration') ?></th>
                    <th  width="10%"  align="left"><?php echo __('VAT') ?></th>
                    <th width="20%"   align="left"><?php echo __('Cost') ?></th>
                    <th  width="20%"   align="left">Samtalstyp</th>
                  </tr>
   <?php
   $callRecords=0;

   $amount_total = 0;




foreach ($callHistory->xdr_list as $xdr) {
        ?>


            <tr>
                <td><?php echo $xdr->connect_time; ?></td>
                <td><?php echo $xdr->CLD; ?></td>
                <td><?php  echo  date('i:s',$xdr->charged_quantity); ?></td>
                <td><?php echo number_format($xdr->charged_amount / 4, 2); ?></td>
                <td><?php echo number_format($xdr->charged_amount, 2);
            $amount_total+= number_format($xdr->charged_amount, 2); ?> SEK</td>
            <td><?php
                $typecall = substr($xdr->account_id, 0, 1);
                if ($typecall == 'a') {
                    echo "Int.";
                }
                if ($typecall == '4') {
                    echo "R";
                }
                if ($typecall == 'c') {
                    if ($CLI == '**24') {
                        echo "Cb M";
                    } else {
                        echo "Cb S";
                    }
                } ?> </td>
        </tr>

        <?php
                $callRecords = 1;
            }
?>        <?php if($callRecords==0){ ?>
                <tr>
                	<td colspan="6"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                </tr>
                <?php }else{ ?>
                <tr>
                	<td colspan="4" align="right"><strong><?php echo __('Subtotal') ?></strong></td>
                	<!--
                	<td><?php echo format_number($amount_total-$amount_total*.20) ?> SEK</td>
                	 -->
                	<td><?php echo number_format($amount_total, 2, ',', '') ?> SEK</td>
                        <td>&nbsp;</td>
                </tr>
                <?php } ?>

                <tr><td colspan="6" align="left">Samtalstyp  type detail <br/> Int. = Internationella samtal<br/>
Cb M = Callback mottaga<br/>
	Cb S = Callback samtal<br/>
	R = resenummer samtal<br/>
</td></tr>
              </table></div>