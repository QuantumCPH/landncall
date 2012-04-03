<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

<div id="sf_admin_container">
<div class="alert_bar">
    <?php echo __('Call history is a 5 -10 min delay.') ?>
</div>

<?php
$unid = $customer->getUniqueid();

    $cuid=$customer->getId();
    $cp = new Criteria();
    $cp->add(CustomerProductPeer::CUSTOMER_ID, $cuid);
    $custmpr = CustomerProductPeer::doSelectOne($cp);
    $p = new Criteria();
    $p->add(ProductPeer::ID, $custmpr->getProductId());
    $products=ProductPeer::doSelectOne($p);
    $pus = 0;
    $pus=$products->getProductCountryUs();

if($pus==1){

?>


    <div id="sf_admin_content">
        <ul class="customerMenu" style="margin:10px 0;">
            <li><a class="external_link" href="allRegisteredCustomer">View All Customer</a></li>
            <li><a class="external_link" href="paymenthistory?id=<?php echo $_REQUEST['id']; ?>">Payment History</a></li>
            <li><a class="external_link" href="customerDetail?id=<?php echo $_REQUEST['id']; ?>">Customer Detail</a></li>
        </ul>
    </div>
    <h1>Call History</h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>
    <tr class="headings">
        <th width="20%"   align="left"><?php echo __('Date &amp; time') ?></th>
        <th  width="20%"  align="left"><?php echo __('To Number') ?></th>
        <th  width="20%"  align="left"><?php echo __('From Number') ?></th>
        <th width="10%"   align="left"><?php echo __('Duration') ?></th>
        <th width="20%"   align="left"><?php echo __('Cost') ?></th>
        <th width="10%"   align="left"><?php echo __('Typ') ?></th>
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
        <?php echo $calls->cost; ?></td>
             <td> <?php  echo   $calls->type;   ?></td></tr>
<?php } ?>

        </table>

    <?php } else {
        
    if(isset($_POST['startdate']) && isset($_POST['enddate'])){
        $fromdate=$_POST['startdate'];
        $todate=$_POST['enddate'];
    }else{
        $tomorrow1 = mktime(0,0,0,date("m"),date("d")-15,date("Y"));
        $fromdate=date("Y-m-d", $tomorrow1);
        $tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        $todate=date("Y-m-d", $tomorrow);
    }
        
        ?>
    
        <div class="sf_admin_filters">
            <form action="" id="searchform" method="POST" name="searchform">
                <fieldset>
                    <div class="form-row">
                        <label><?php echo __('From');?>:</label>
                        <div class="content">

                            <?php echo input_date_tag('startdate', $fromdate, 'rich=true') ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <label><?php echo __('To');?>:</label>
                        <div class="content">

                            <?php echo input_date_tag('enddate', $todate, 'rich=true') ?>
                        </div>
                    </div>

                </fieldset>

                <ul class="sf_admin_actions">
                   <li><input type="submit" class="sf_admin_action_filter" value="filter" name="filter"></li>
                </ul>
            </form>
        </div>
            <div id="sf_admin_content">
                <ul class="customerMenu" style="margin:10px 0;">
                    <li><a class="external_link" href="allRegisteredCustomer">View All Customer</a></li>
                    <li><a class="external_link" href="paymenthistory?id=<?php echo $_REQUEST['id']; ?>">Payment History</a></li>
                    <li><a class="external_link" href="customerDetail?id=<?php echo $_REQUEST['id']; ?>">Customer Detail</a></li>
                </ul>
                <h1>Call History</h1>

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
                $amount_total = 0;








  $getFirstnumberofMobile = substr($customer->getMobileNumber(), 0,1);
                if($getFirstnumberofMobile==0){
                    $TelintaMobile = substr($customer->getMobileNumber(), 1);
                    $TelintaMobile =  '46'.$TelintaMobile ;
                }else{
                    $TelintaMobile = '46'.$customer->getMobileNumber();
                }


 $numbername=$customer->getUniqueid();



                          $tilentaCallHistryResult = Telienta::callHistory($customer, $fromdate, $todate);


                            foreach ($tilentaCallHistryResult->xdr_list as $xdr) { //echo "<pre>";echo var_dump($tilentaCallHistryResult);echo "</pre>";
                          //  die;
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
                        $callRecords=1;    }
?>


        <?php
                $callRecords = 1;
            }
        ?>


        <?php if (count($callRecords) == 0): ?>
                <tr>
                    <?php    if($pus==0){  ?>
                    <td colspan="6"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                    <?php } ?>
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
        <?php endif;
          if($pus==0){
        ?>

                    <tr><td colspan="6" align="left">Samtalstyp  type detail <br/> Int. = Internationella samtal<br/>
                            Cb M = Callback mottaga<br/>
                        	Cb S = Callback samtal<br/>
                        	R = resenummer samtal<br/>
                        </td></tr>

                    <?php } ?>
                </table>



    <?php //} ?>




    <!-- end split-form -->
</div> <!-- end left-col -->
