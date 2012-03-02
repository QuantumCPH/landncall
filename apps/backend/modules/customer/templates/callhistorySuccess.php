<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<style>
    input.submitBtn {
      /*background-color:#76B55F;*/
      background:url('../../zerocall/images/bg-login-btn.jpg') no-repeat;
      padding:0px 5px 7px 5px;
      color:#ffffff;
      border:0px;
      width:77px;
      height:28px;
      margin-top: -6px;
    }
</style>
<script type="text/javascript">
     jQuery(function() {
     
 jQuery( "#startdate" ).datepicker({ maxDate: '0m +0w', dateFormat: 'yy-mm-dd' });
 jQuery( "#enddate" ).datepicker({ maxDate: '0m +0w', dateFormat: 'yy-mm-dd'});


});
    </script>
<div class="alert_bar">
    <?php echo __('Call history is a 5 -10 min delay.') ?>
</div>
<div class="left-col">

    <form action="" id="searchform" method="POST" name="searchform" style="float: left;" >
        <div class="dateBox-pt">
            <div class="formRow-pt" style="float:left;">
                <label class="datelable">From:</label>
                <input type="text"   name="startdate" autocomplete="off" id="startdate" style="width: 110px;" value="<?php echo @$fromdate ? $fromdate : date('Y-m-d', strtotime('-15 days')); ?>" />
            </div>
            <div class="formRow-pt1" style="float:left;margin-left:7px;">
                &nbsp;<label class="datelable">To:</label>
                <input type="text"   name="enddate" autocomplete="off" id="enddate" style="width: 110px;" value="<?php echo @$todate ? $todate : date('Y-m-d'); ?>" />
                <input type="hidden"   name="id" value="<?php echo $customer->getId(); ?>" />
            </div>
            <div class="formRow-pt1" style="float:left;margin-left:7px;">

                <span style="margin-left:10px;"><input type="submit" name="Search" value="Search"  /></span>
            </div>

        </div>

    </form>

    <div style="clear:both;">&nbsp;</div>

    <?php
    $unid = $customer->getUniqueid();
    if ((int) $unid > 200000) {
    ?>


        <table width="70%" cellspacing="0" cellpadding="0" class="callhistory" style="float: left;">
            <tr>
                <th align="left"colspan="5">&nbsp; </th>

            </tr>
            <tr>
                <th align="left" colspan="5">
                    <table border="0" cellspacing="4" cellpadding="4" >  <tr  style="background-color: #838483;color:#FFFFFF;padding: 5px;">
                            <td align="left" ><a  style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="allRegisteredCustomer">View All Customer</a></td>
                            <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="paymenthistory?id=<?php echo $_REQUEST['id']; ?>">Payment History</a></td>
                            <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="customerDetail?id=<?php echo $_REQUEST['id']; ?>">Customer Detail</a></td>


                        </tr> </table></th>

            </tr>
            <tr>
                <th align="left" colspan="5">&nbsp;</th>

            </tr>
            <tr>
                <th align="left" colspan="5"  style="background-color: #CCCCFF;color: #000000;text-align: left;">Call History</th>

            </tr>
            <tr  style="background-color: #CCCCFF;color: #000000;">
                <th width="20%"   align="left"><?php echo __('Date &amp; time') ?></th>
                <th  width="20%"  align="left"><?php echo __('To Number') ?></th>
                <th  width="20%"  align="left"><?php echo __('From Number') ?></th>
                <th width="10%"   align="left"><?php echo __('Duration') ?></th>
                <th width="20%"   align="left"><?php echo __('Cost <small>(Incl. VAT)</small>') ?></th>

            </tr>
        <?php
        $customerid = $customer->getId();
        $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
        $usnumber = UsNumberPeer::doSelectOne($tc);


        $username = "Zapna";
        $password = "ZUkATradafEfA4reYeWr";
        $msisdn = $usnumber->getMsisdn();
        $iccid = $usnumber->getIccid();

        $tomorrow1 = mktime(0, 0, 0, date("m") - 2, date("d") - 15, date("Y"));
        $fromdate = date("Y-m-d h:m:s", $tomorrow1);
        $tomorrow = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
        $todate = date("Y-m-d h:m:s", $tomorrow);

        $url = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";
        $post_string = '<get-subscriber-call-history trid="37543937592">
<authentication>
<username>' . $username . '</username>
<password>' . $password . '</password>
</authentication>
<msisdn>' . $msisdn . '</msisdn>
<iccid>' . $iccid . '</iccid>
<start-date>' . $fromdate . '</start-date>
<end-date>' . $todate . '</end-date>
</get-subscriber-call-history>';


        $header = array();
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: " . strlen($post_string);
        $header[] = "Connection: close";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HEADER, true);


        $data = curl_exec($ch);



        $pieces = explode("<get-subscriber-call-history-response", $data);
        // piece1
        $data = "<get-subscriber-call-history-response" . $pieces[1];    // piece2
        // $data = substr($data, 200);
        $xml_obj = new SimpleXMLElement($data);
        //var_dump($xml_obj);
        // echo  $data = $xml_obj->calls->call[0]->cost;
        // echo "<hr/>";
        foreach ($xml_obj->calls->call as $calls) {
        ?>
            <tr>
                <td ><?php
            $cld = 'called-date';
            echo $calls->$cld; ?></td> <td><?php echo $calls->to; ?></td><td><?php echo $calls->from; ?></td><td> <?php echo $calls->duration; ?></td><td>
        <?php echo $calls->cost; ?></td></tr>
<?php } ?>

        </table>

    <?php } else { ?>

            <table width="70%" cellspacing="0" cellpadding="0" class="callhistory" style="float: left;">
                <tr>
                    <th align="left"colspan="6">&nbsp; </th>

                </tr>
                <tr>
                    <th align="left" colspan="6">
                        <table border="0" cellspacing="4" cellpadding="4" >  <tr  style="background-color: #838483;color:#FFFFFF;padding: 5px;">
                                <td align="left" ><a  style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="allRegisteredCustomer">View All Customer</a></td>
                                <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="paymenthistory?id=<?php echo $_REQUEST['id']; ?>">Payment History</a></td>
                                <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="customerDetail?id=<?php echo $_REQUEST['id']; ?>">Customer Detail</a></td>


                            </tr> </table></th>

                </tr>
                <tr>
                    <th align="left" colspan="6">&nbsp;</th>

                </tr>
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
                $amount_total = 0;




if(isset($_POST['startdate']) && isset($_POST['enddate'])){
    $fromdate=$_POST['startdate'];
    $todate=$_POST['enddate'];
}else{
$tomorrow1 = mktime(0,0,0,date("m"),date("d")-15,date("Y"));
$fromdate=date("Y-m-d", $tomorrow1);
$tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
$todate=date("Y-m-d", $tomorrow);
}



  $getFirstnumberofMobile = substr($customer->getMobileNumber(), 0,1);
                if($getFirstnumberofMobile==0){
                    $TelintaMobile = substr($customer->getMobileNumber(), 1);
                    $TelintaMobile =  '46'.$TelintaMobile ;
                }else{
                    $TelintaMobile = '46'.$customer->getMobileNumber();
                }


 $numbername=$customer->getUniqueid();



                          $tilentaCallHistryResult = Telienta::callHistory($customer, $fromdate, $todate);


                            foreach ($tilentaCallHistryResult->xdr_list as $xdr) {print_r($tilentaCallHistryResult);
                            ?>


                                <tr>
                                    <td><?php echo $xdr->connect_time; ?></td>
                                    <td><?php echo $xdr->CLD; ?></td>
                                    <td><?php echo number_format($xdr->charged_quantity / 60, 2); ?></td>
                                    <td><?php echo number_format($xdr->charged_amount / 4, 2); ?></td>
                                    <td><?php echo number_format($xdr->charged_amount, 2);
                                $amount_total+= number_format($xdr->charged_amount, 2); ?> SEK</td>
                                    <td><?php echo "fff".$xdr->account_id;
                               echo $typecall = substr($xdr->account_id, 0, 1);
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
                        $callRecords=1;    }
?>


        <?php
                $callRecords = 1;
            }
        ?>


        <?php if (count($callRecords) == 0): ?>
                <tr>
                    <td colspan="6"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                </tr>
        <?php else: ?>
                    <tr>
                        <td colspan="4" align="right"><strong><?php echo __('Subtotal') ?></strong></td>
                        <!--
                        <td><?php echo format_number($amount_total - $amount_total * .20) ?> SEK</td>
                                        	 -->
                        <td><?php echo number_format($amount_total, 2, ',', '') ?> SEK</td>
                    <td>&nbsp;</td>
                </tr>
        <?php endif; ?>

                    <tr><td colspan="6" align="left">Samtalstyp  type detail <br/> Int. = Internationella samtal<br/>
                            Cb M = Callback mottaga<br/>
                        	Cb S = Callback samtal<br/>
                        	R = resenummer samtal<br/>
                        </td></tr>
                </table>



    <?php //} ?>




    <!-- end split-form -->
</div> <!-- end left-col -->
