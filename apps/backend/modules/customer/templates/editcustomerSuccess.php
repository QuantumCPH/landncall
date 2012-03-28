<form method="post" action="" name="edit" enctype="multipart/form-data" >
    <input type="hidden" name="customerID" value="<?php echo $editCust->getId();?>" />
<p><?php echo @$message;?></p>
<table border="0" cellspacing="4" cellpadding="4" >  <tr  style="background-color: #838483;color:#FFFFFF;padding: 5px;">
    <td align="left" ><a  style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="../../allRegisteredCustomer">View All Customer</a></td>
    <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="../../paymenthistory?id=<?php echo $editCust->getId();  ?>">Payment History</a></td>
    <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="../../callhistory?id=<?php echo $editCust->getId();  ?>">Call History</a></td>
     <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="../../customerDetail?id=<?php echo $editCust->getId();  ?>">Customer Detail</a></td>
 </tr> </table>
    <table style="margin-left:10px;">
         <tr >
             <th colspan="2"> </th>

                      </tr>
        <tr><td colspan="2"><h2>Edit Customer</h2></td></tr>
        <tr>
            <td>First Name</td>
            <td><input type="text" name="firstName" value="<?php echo $editCust->getFirstName();?>" />
            </td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type="text" name="lastName" value="<?php echo $editCust->getLastName();?>" />
            </td>
        </tr>
        <tr>
            <td>Address</td>
            <td><input type="text" name="address" value="<?php echo $editCust->getAddress();?>" />
            </td>
        </tr>
        <tr>
            <td>City</td>
            <td><input type="text" name="city" value="<?php echo $editCust->getCity();?>" />
            </td>
        </tr>
        <tr>
            <td>PO-BOX Number</td>
            <td><input type="text" name="pob" value="<?php echo $editCust->getPoBoxNumber();?>" />
            </td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" value="<?php echo $editCust->getEmail();?>" />
            </td>
        </tr> 
        <tr>
            <td>Date Of Birth</td>
            <td>
                <?php
                $dt = "";
                $dd = "";
                $dm = "";
                $dy ="";
                $dt = $editCust->getDateOfBirth();
                if($dt){
                          $dd = date('d',strtotime($dt));
                          $dm = date('m',strtotime($dt));
                          $dy = date('Y',strtotime($dt));
                         } 
                ?>
                <select name="dd">
                    <option value="">Day</option>
                    <?php
                    for($d = 1;$d<=31; $d++){
                    ?>
                    <option value="<?php echo $d;?>"<?php echo (@$dd!=$d)?'':' selected="selected" ' ?> ><?php echo $d;?></option>
                    <?php    
                    }
                    ?>
                </select>&nbsp;
                <select name="dm">
                    <option value="">Month</option>
                    <?php
                    for($m = 1;$m<=12; $m++){
                    ?>
                    <option value="<?php echo $m;?>"<?php echo (@$dm!=$m)?'':' selected="selected" ' ?> ><?php echo $m;?></option>
                    <?php    
                    }
                    ?>
                </select>&nbsp;
                <select name="dy">
                    <option value="">Year</option>
                    <?php
                    for($y =1901;$y<=1998; $y++){
                    ?>
                    <option value="<?php echo $y;?>"<?php echo (@$dy!=$y)?'':' selected="selected" ' ?> ><?php echo $y;?></option>
                    <?php    
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Usage Email Alerts</td>
            <td>
                <input type="checkbox" name="usage_email" <?php if($editCust->getUsageAlertEmail()) echo" checked=checked"?> />&nbsp;
                
            </td>
        </tr>
        <tr>
            <td>Usage SMS Alerts</td>
            <td>
                <input type="checkbox" name="usage_sms" <?php if($editCust->getUsageAlertSMS()) echo" checked=checked"?> />&nbsp;
                
            </td>
        </tr>
        
        <tr>

            <td colspan="2"><br /><input type="submit" name="submit"  value="update"></td>
        </tr>




    </table>





</form>