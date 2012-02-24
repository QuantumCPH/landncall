<div id="sf_admin_container">
    <div id="sf_admin_content">
        <a href="<?php echo url_for('employee/view').'?id='.$employee->getId() ?>" class="external_link" target="_self">Employee Detail</a>
    </div>
</div><br>
<table width="75%">
    <tr>
        <th align="left" colspan="6"  style="background-color: #CCCCFF;color: #000000;text-align: left;">Call History</th>
    </tr>
    <tr  style="background-color: #CCCCFF;color: #000000;">
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

$tomorrow1 = mktime(0,0,0,date("m"),date("d")-15,date("Y"));
$fromdate=date("Y-m-d", $tomorrow1);
$tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
$todate=date("Y-m-d", $tomorrow);

$vatnumber=$companys->getVatNo();
$mobilenumber=$employee->getCountryMobileNumber();

$urlval = 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=get_xdrs&name=a'.$mobilenumber.'&tz=Europe/Stockholm&from_date='.$fromdate.'&to_date='.$todate.'&customer='.$vatnumber;
//$urlval = 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=get_xdrs&name=a46732801013&tz=Europe/Stockholm&from_date=2010-01-01&to_date=2012-01-01&customer=100059';

$res = file_get_contents($urlval);
$csv = new parseCSV();
$csvFileName = $res;
# Parse '_books.csv' using automatic delimiter detection...
$csv->auto($csvFileName);

foreach ($csv->data as $key => $row) {

    $timstampscsv = date('Y-m-d h:i:S');
    $counters = 0;
    foreach ($row as $value) {

        if ($csv->titles[$counters] == 'class') {
            $csv->titles[$counters] = 'lstclasses';
        }
            ${$csv->titles[$counters]} = $value;
            $counters++;
    } ?>

    <tr>
        <td><?php echo $connect_time; ?></td>
        <td><?php echo  $CLD; ?></td>
        <td><?php echo number_format($charged_quantity/60 ,2);  ?></td>
        <td><?php echo  number_format($charged_amount/4,2); ?></td>
        <td><?php echo number_format($charged_amount,2);      $amount_total+= number_format($charged_amount,2); ?> SEK</td>
        <td><?php $account_id;    $typecall=substr($account_id,0,1);
           if($typecall=='a'){ echo "Int.";  }
           if($typecall=='4'){ echo "R";  }
           if($typecall=='c'){ if($CLI=='**24'){  echo "Cb M"; }else{ echo "Cb S"; }      }  ?>
        </td>
   </tr>
<?php
$callRecords=1;
}


$urlvalcb = 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=get_xdrs&name=cb'.$mobilenumber.'&tz=Europe/Stockholm&from_date='.$fromdate.'&to_date='.$todate.'&customer='.$vatnumber;
//$urlvalcb = 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=get_xdrs&name=cb34619485107&tz=Europe/Stockholm&from_date=2010-01-01&to_date=2012-01-01&customer=80020';
$rescb = file_get_contents($urlvalcb);
$csvcb = new parseCSV();
$csvFileNamecb = $rescb;
# Parse '_books.csv' using automatic delimiter detection...
$csvcb->auto($csvFileNamecb);

foreach ($csvcb->data as $keycb => $rowcb) {

    $timstampscsvcb = date('Y-m-d h:i:S');
    $counterscb = 0;
    foreach ($rowcb as $valuecb) {

        if ($csvcb->titles[$counterscb] == 'class') {
            $csvcb->titles[$counterscb] = 'lstclasses';
        }
            ${$csvcb->titles[$counterscb]} = $valuecb;
            $counterscb++;
    } ?>

    <tr>
        <td><?php echo $connect_time; ?></td>
        <td><?php echo  $CLD; ?></td>
        <td><?php echo number_format($charged_quantity/60 ,2);  ?></td>
        <td><?php echo  number_format($charged_amount/4,2); ?></td>
        <td><?php echo number_format($charged_amount,2);      $amount_total+= number_format($charged_amount,2); ?> SEK</td>
        <td><?php $account_id;    $typecall=substr($account_id,0,1);
           if($typecall=='a'){ echo "Int.";  }
           if($typecall=='4'){ echo "R";  }
           if($typecall=='c'){ if($CLI=='**24'){  echo "Cb M"; }else{ echo "Cb S"; }      }  ?>
        </td>
   </tr>
<?php
$callRecordscb=1;
}

$regtype=$employee->getRegistrationType();

if(isset($regtype) && $regtype==1){
$voip = new Criteria();

$voip->add(SeVoipNumberPeer::CUSTOMER_ID, $employee->getCountryMobileNumber());
$voip->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
$voipv = SeVoipNumberPeer::doSelectOne($voip);

if(isset ($voipv)){
    
$resenummer=$voipv->getNumber();
$resenummer = substr($resenummer, 2);
$urlvalrese = 'https://mybilling.telinta.com/htdocs/zapna/zapna.pl?type=account&action=get_xdrs&name='.$resenummer.'&tz=Europe/Stockholm&from_date='.$fromdate.'&to_date='.$todate.'&customer='.$vatnumber;
$resrese = file_get_contents($urlvalrese);
$csvrese = new parseCSV();
$csvFileNamerese = $resrese;
# Parse '_books.csv' using automatic delimiter detection...
$csvrese->auto($csvFileNamerese);

foreach ($csvrese->data as $keyrese => $rowrese) {

    $timstampscsvrese = date('Y-m-d h:i:S');
    $countersrese = 0;
    foreach ($rowrese as $valuerese) {

        if ($csvrese->titles[$countersrese] == 'class') {
            $csvrese->titles[$countersrese] = 'lstclasses';
        }
            ${$csvrese->titles[$countersrese]} = $valuerese;
            $countersrese++;
    } ?>

    <tr>
        <td><?php echo $connect_time; ?></td>
        <td><?php echo  $CLD; ?></td>
        <td><?php echo number_format($charged_quantity/60 ,2);  ?></td>
        <td><?php echo  number_format($charged_amount/4,2); ?></td>
        <td><?php echo number_format($charged_amount,2);     // $amount_total+= number_format($charged_amount,2); ?> SEK</td>
        <td><?php $account_id;    $typecall=substr($account_id,0,1);
           if($typecall=='a'){ echo "Int.";  }
           if($typecall=='4'){ echo "R";  }
           if($typecall=='c'){ if($CLI=='**24'){  echo "Cb M"; }else{ echo "Cb S"; }      }  ?>
        </td>
   </tr>
<?php
$callRecordsrese=1;
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
</table>