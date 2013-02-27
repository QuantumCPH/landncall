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
  
 
</style>
<div id="sf_admin_container" style="width: 75%;">

    <h1>Call Report</h1><br />
<table  width="100%" cellspacing="0" cellpadding="2" class="tblAlign">
                    <tr class="headings">
                        <th> Serial# </th>
                        <th> Customer name</th>
                        <th>Phone no.  </th>
                        <th>Unique Id  </th>
                        <th>Calls</th>
                        <th>Last Call Date</th>
                    </tr>
                     <?php
	
        $i=1;
        foreach($customers as $customer){
          if($i%2==0){
                  $class= 'class="even"';
                  }else{
                  $class= 'class="odd"';
                      }

                  ?><tr <?php echo $class;   ?> >
                           <td>&nbsp;   <?php echo $i;  ?></td>
                      <td>&nbsp;   <?php echo $customer->getFirstName()." ".$customer->getLastName();   ?></td>
                         <td>&nbsp;   <?php echo $customer->getMobileNumber();   ?> </td>
                      <td>&nbsp;   <?php echo $customer->getUniqueId();   ?> </td>
                       <td>&nbsp;   <?php echo $customer->getCalls();   ?> </td>
                     <td>&nbsp; <?php if($customer->getCalls()=="Yes") echo $customer->getLastCall()->getConnectTime();   ?> </td>
                                           </tr>
<?php

$i++;

}  ?>
                </table>

</div>              

                             