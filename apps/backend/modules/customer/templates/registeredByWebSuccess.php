<table width="100%" cellspacing="0" cellpadding="2" style="float:left; border-collapse: collapse" border="1">
    <thead ><tr style="background-color: #CCCCFF;color: #000000;">
        <td id="sf_admin_list_th_id" ><b>Id</b></td>
        <td id="sf_admin_list_th_id" ><b>Customer No</b></td>
        <td id="sf_admin_list_th_first_name"><b>Name</b></td>
        <!--                    <th id="sf_admin_list_th_last_name" >Last Name</th>-->
        <td id="sf_admin_list_th_mobile_number" ><b>Mobile No</b></td>
        <td id="sf_admin_list_th_mobile_number" ><b>Password</b></td>
        <!--					 <th id="sf_admin_list_th_fonet_customer">Fonet Customer ID</th>    -->
        <td id="sf_admin_list_th_address"><b>Address</b></td>
        <td id="sf_admin_list_th_city" ><b>City</b></td>
        <td id="sf_admin_list_th_po_box_number" ><b>PO-BOX</b></td>
        <td id="sf_admin_list_th_email" ><b>Email</b></td>
        <td id="sf_admin_list_th_created_at" ><b>Created At</b></td>


<!--        <td id="sf_admin_list_th_date_of_birth"><b>Date Of Birth</b></td>-->
        <td id="sf_admin_list_th_auto_refill"><b>Auto Refill</b></td>
        <td id="sf_admin_list_th_auto_refill"><b>Unique ID</b></td>
        <td id="sf_admin_list_th_auto_refill"><b>Active No</b></td></tr>
        </thead>
        <tfoot>
        <tr><th colspan="16">
        <div class="float:right">
        </div>
        <?php echo count($customers)." - Results" ?></th></tr>
        </tfoot>
        <tbody> <?php   $incrment=1;    ?>
        <?php foreach($customers as $customer): ?>
        <?php
        if($incrment%2==0){
        $colorvalue="#FFFFFF";
        }else{

        $colorvalue="#EEEEFF";
        }

        ?>
        <tr style="background-color:<?php echo $colorvalue;   ?>">
        <td><?php  echo $incrment; ?></td>
        <td><?php  echo $customer->getId() ?></td>
        <td><?php echo  $customer->getFirstName()." ".$customer->getLastName(); ?></td>
        <!--                  <td><?php echo  $customer->getLastName() ?></td>-->
        <td><?php echo  $customer->getMobileNumber() ?></td>
        <td><?php echo  $customer->getPlainText() ?></td>
        <!--				  <td><?php echo  $customer->getFonetCustomerId() ?></td>-->
        <td><?php echo  $customer->getAddress() ?></td>
        <td><?php echo  $customer->getCity() ?></td>
        <td><?php echo  $customer->getPoBoxNumber() ?></td>
        <td><?php echo  $customer->getEmail() ?></td>
        <td><?php echo  $customer->getCreatedAt() ?></td>
<!--        <td><?php //echo  $customer->getDateOfBirth() ?></td>-->
        <?php if ($customer->getAutoRefillAmount()!=NULL && $customer->getAutoRefillAmount()>1){ ?>
        <td>Yes</td>

        <?php } else
        { ?>
        <td>No</td>
        <?php } ?>


        <td>  <?php  echo $customer->getUniqueid();     ?>   </td>

        <td>  <?php  $unid   =  $customer->getUniqueid();
        if(isset($unid) && $unid!=""){
        $un = new Criteria();
        $un->add(CallbackLogPeer::UNIQUEID, $unid);
        $un -> addDescendingOrderByColumn(CallbackLogPeer::CREATED);
        $unumber = CallbackLogPeer::doSelectOne($un);
        echo $unumber->getMobileNumber();
        }else{  }  ?> </td>


        </tr><?php   $incrment++;    ?>
        <?php endforeach; ?>


    </tbody>
</table>

