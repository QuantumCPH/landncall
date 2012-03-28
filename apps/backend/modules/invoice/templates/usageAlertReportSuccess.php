<?php    
	use_helper('Number');
	$sf_user->setCulture('da_DK');
?>
<style type="text/css">
  .invoice {
  	border: 1px solid #f0f0f0;
  }
</style>
<?php ob_start(); ?>
<style type="text/css">

	
  .invoice *
  {
  font-family: calibri, verdana, "Courier New", Courier, mono;
font-size:16px;
  }
  
  .invoice .call_summary
  {
margin-top: 10px;
  }
  
  .summary_header
  {
    /*background: #c0c0c0;*/
    font-weight: bold;
  }
  
  .group_header
  {
  /*background: #f0f0f0;*/
  font-weight: bold;
}

.summary_header, .group_header, .group_subheader
{
 height: 30px;
 border-bottom:1px solid #000;
}

.group_subheader td.first
{
	text-indent: 10px;
	font-weight: bold;
}



.group_data td.first
{
	text-indent: 20px;
}

.footer
{
	height:25px;
	font-style:italic;
}

.footer.grandtotal
{
	background: #f0f0f0;

}
</style>
<table class='invoice' width="100%">
	<tr>
            <td colspan="2">Report on Dates  From :  <?php echo $startdate; ?>  To : <?php echo $enddate; ?></td>
        </tr>
        <tr>
            <td align="left" colspan="2" > <h2>Total SMS sent : <?php
				  $c = new Criteria();
		$c->add(SmsAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
		$c->addAnd(SmsAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
     echo 	$smsusgae=SmsAlertSentPeer::doCount($c); ?>
	</h2></td></tr>
         <tr>
            <td align="left" colspan="2"> <h2>Total Email sent : <?php
				  $c = new Criteria();
		$c->add(EmailAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
		$c->addAnd(EmailAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
     echo 	$email=EmailAlertSentPeer::doCount($c); ?>
	</h2></td></tr>
        <tr>
            <td align="left" colspan="2">

                <table>
                    <tr style="background-color: #CCCCFF;color: #000000;font-weight: bold;"> <td colspan="9">SMS Detail Report</td></tr>
                    <tr style="background-color: #CCCCFF;color: #000000;">
                        <td> id </td>
                        <td> Customer name</td>
                        <td>Phone no.  </td>
                        <td>Email</td>
                          <td>Description</td>
                        <td>Product</td>
                        <td>Agent name</td>
                        <td>Registration type</td>
                        <td>Sent</td>
                    </tr>
                     <?php
				  $c = new Criteria();
		$c->add(SmsAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
		$c->addAnd(SmsAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
 	$smsuslageDetails=SmsAlertSentPeer::doSelect($c);
        $i=1;
        foreach($smsuslageDetails as $smsuslageDetail){
        ?>  <?php
                  if($i%2==0){
                  $colorvalue="#FFFFFF";
                  }else{

                      $colorvalue="#EEEEFF";
                      }

                  ?>
                     <tr style="background-color:<?php echo $colorvalue;   ?>">
                           <td>&nbsp;   <?php echo $i;  ?></td>
                      <td>&nbsp;   <?php echo $smsuslageDetail->getCustomerName();   ?></td>
                         <td>&nbsp;   <?php echo $smsuslageDetail->getMobileNumber();   ?> </td>
                      <td>&nbsp;   <?php echo $smsuslageDetail->getCustomerEmail();   ?> </td>
                       <td>&nbsp;   <?php echo $smsuslageDetail->getMessageDescerption();   ?> </td>
                     <td> &nbsp;  <?php echo $smsuslageDetail->getCustomerProduct();   ?> </td>
                     <td>&nbsp;   <?php echo $smsuslageDetail->getAgentName();   ?> </td>
                     <td>&nbsp; <?php echo $smsuslageDetail->getRegistrationType();   ?> </td>
                     <td>&nbsp; <?php echo ($smsuslageDetail->getAlertSent()==0) ? 'No' :'Yes';   ?> </td>
                                           </tr>
<?php

$i++;

}  ?>
                </table>


		</td>
	</tr>


           <tr>
            <td align="left" colspan="2">
<br/><br/><br/><br/>
                <table>
                    <tr style="background-color: #CCCCFF;color: #000000;font-size: 14px;font-weight: bold;"> <td colspan="9">Email Detail Report</td></tr>
                    <tr style="background-color: #CCCCFF;color: #000000;font-size: 14px;">
                        <td> id </td>
                        <td> Customer name</td>
                        <td>Phone no.  </td>
                        <td>Email</td>
                        <td>Description</td>
                        <td>Product</td>
                        <td>Agent name</td>
                        <td>Registration type</td>
                        <td>Sent</td>
                    </tr>
                     <?php
				  $c = new Criteria();
		$c->add(EmailAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
		$c->addAnd(EmailAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
 	$EmailuslageDetails=EmailAlertSentPeer::doSelect($c);
        $j=1;
        foreach($EmailuslageDetails as $EmailuslageDetail){
        ?>


                     <?php
                  if($j%2==0){
                  $colorvalue="#FFFFFF";
                  }else{

                      $colorvalue="#EEEEFF";
                      }

                  ?><tr style="background-color:<?php echo $colorvalue;   ?>" >
                           <td>&nbsp;   <?php echo $j;  ?></td>
                      <td>&nbsp;   <?php echo $EmailuslageDetail->getCustomerName();   ?></td>
                         <td>&nbsp;   <?php echo $EmailuslageDetail->getMobileNumber();   ?> </td>
                      <td>&nbsp;   <?php echo $EmailuslageDetail->getCustomerEmail();   ?> </td>
                       <td>&nbsp;   <?php echo $EmailuslageDetail->getMessageDescerption();   ?> </td>
                     <td> &nbsp;  <?php echo $EmailuslageDetail->getCustomerProduct();   ?> </td>
                     <td>&nbsp;   <?php echo $EmailuslageDetail->getAgentName();   ?> </td>
                     <td>&nbsp; <?php echo $EmailuslageDetail->getRegistrationType();   ?> </td>
                     <td>&nbsp; <?php echo ($EmailuslageDetail->getAlertSent()==0) ? 'No' :'Yes';   ?> </td>
                                           </tr>
<?php

$j++;

}  ?>
                </table>


		</td>
	</tr>
</table>
                

                             