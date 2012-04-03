<div id="sf_admin_container">
    <div id="sf_admin_content">
        <a href="<?php echo url_for('employee/view').'?id='.$employee->getId() ?>" class="external_link" target="_self">Employee Detail</a>
    </div>
<br>
<h1><?php echo __('Call History') ?></h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>
        <tr class="headings">
        <th width="20%"   align="left"><?php echo __('Date &amp; time') ?></th>
        <th  width="20%"  align="left"><?php echo __('Phone Number') ?></th>
        <th width="10%"   align="left"><?php echo __('Duration') ?></th>
        <th  width="10%"  align="left"><?php echo __('VAT') ?></th>
        <th width="20%"   align="left"><?php echo __('Cost <small>(Incl. VAT)</small>') ?></th>
        <th  width="20%"   align="left">Samtalstyp</th>
    </tr>
<?php

$callRecords=0;
$callRecordscb=0;
$callRecordsrese=0;
$amount_total = 0;

foreach ($callHistory->xdr_list as $xdr) {
        ?>


            <tr>
                <td><?php echo $xdr->connect_time; ?></td>
                <td><?php echo $xdr->CLD; ?></td>
                <td><?php echo number_format($xdr->charged_quantity / 60, 2); ?></td>
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

        foreach ($callHistorycb->xdr_list as $xdrcb) {
        ?>


            <tr>
                <td><?php echo $xdrcb->connect_time; ?></td>
                <td><?php echo $xdrcb->CLD; ?></td>
                <td><?php echo number_format($xdrcb->charged_quantity / 60, 2); ?></td>
                <td><?php echo number_format($xdrcb->charged_amount / 4, 2); ?></td>
                <td><?php echo number_format($xdrcb->charged_amount, 2);
            $amount_total+= number_format($xdrcb->charged_amount, 2); ?> SEK</td>
                <td><?php
            $typecall = substr($xdrcb->account_id, 0, 1);
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
            $callRecordscb = 1;
        }


$regtype=$employee->getRegistrationType();

if(isset($regtype) && $regtype==1){
$voip = new Criteria();

$voip->add(SeVoipNumberPeer::CUSTOMER_ID, $employee->getCountryMobileNumber());
$voip->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
$voipv = SeVoipNumberPeer::doSelectOne($voip);

if(isset ($voipv)){
    
foreach ($callHistoryres->xdr_list as $xdrres) {
        ?>


    <tr>
        <td><?php echo $xdrres->connect_time; ?></td>
        <td><?php echo $xdrres->CLD; ?></td>
        <td><?php echo number_format($xdrres->charged_quantity / 60, 2); ?></td>
        <td><?php echo number_format($xdrres->charged_amount / 4, 2); ?></td>
        <td><?php echo number_format($xdrres->charged_amount, 2);
    $amount_total+= number_format($xdrres->charged_amount, 2); ?> SEK</td>
        <td><?php
    $typecall = substr($xdrcb->account_id, 0, 1);
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
     $callRecordsrese = 1;
 }
}
}
?>

<?php if($callRecords==0 and $callRecordscb==0 and $callRecordsrese==0){ ?>
    <tr>
        <td colspan="6"><p><?php echo __('There are currently no call records to show.') ?></p></td>
    </tr>
<?php }else{ ?>
    <tr>
        <td colspan="4" align="right"><strong><?php echo __('Subtotal') ?></strong></td>
        <td><?php echo number_format($amount_total, 2, ',', '') ?> SEK</td>
        <td>&nbsp;</td>
    </tr>
<?php } ?>

    <tr>
        <td colspan="6" align="left">Samtalstyp  type detail <br/> Int. = Internationella samtal<br/>
            Cb M = Callback mottaga<br/>
            Cb S = Callback samtal<br/>
            R = resenummer samtal<br/>
        </td>
    </tr>
</table></div>