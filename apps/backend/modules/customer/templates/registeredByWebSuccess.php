<?php    ?>

<ul>
            <li>
                <div id="sf_admin_content">
		<table width="100%" cellspacing="0" cellpadding="2" style="float:left;">
                  <thead ><tr style="background-color: #CCCCFF;color: #000000;">
                    <th id="sf_admin_list_th_id" >Id</th>
                    <th id="sf_admin_list_th_first_name">First Name</th>
                    <th id="sf_admin_list_th_last_name" >Last Name</th>
					<th id="sf_admin_list_th_mobile_number" >Mobile Number</th>
					 <th id="sf_admin_list_th_mobile_number" >Password</th>
<!--					 <th id="sf_admin_list_th_fonet_customer">Fonet Customer ID</th>    -->
					 <th id="sf_admin_list_th_address">Address</th>
                    <th id="sf_admin_list_th_city" >City</th>
                    <th id="sf_admin_list_th_po_box_number" >PO-BOX Number</th>                    
                    <th id="sf_admin_list_th_email" >Email</th>
                    <th id="sf_admin_list_th_created_at" >Created At</th>                    
                    
                                                                          
                    <th id="sf_admin_list_th_date_of_birth">Date Of Birth</th>
                    <th id="sf_admin_list_th_auto_refill">Auto Refill</th>
                     <th id="sf_admin_list_th_auto_refill">Unique ID</th>
                       <th id="sf_admin_list_th_auto_refill">Active No</th></tr>
                  </thead>
                  <tfoot>
                    <tr><th colspan="16">
                    <div class="float:right">
                    </div>
                    <?php echo count($customers)." - Results" ?></th></tr>
                  </tfoot>
                  <tbody>
                <?php foreach($customers as $customer): ?>
                 <?php
                  if($incrment%2==0){
                  $colorvalue="#FFFFFF";
                  }else{

                      $colorvalue="#EEEEFF";
                      }

                  ?>
                  <tr style="background-color:<?php echo $colorvalue;   ?>">
                  <td><?php  echo $customer->getId() ?></td>
                  <td><?php echo  $customer->getFirstName() ?></td>
                  <td><?php echo  $customer->getLastName() ?></td>
				  <td><?php echo  $customer->getMobileNumber() ?></td>
				    <td><?php echo  $customer->getPlainText() ?></td>
<!--				  <td><?php echo  $customer->getFonetCustomerId() ?></td>-->
				  <td><?php echo  $customer->getAddress() ?></td>
                  <td><?php echo  $customer->getCity() ?></td>
                  <td><?php echo  $customer->getPoBoxNumber() ?></td>                 
                  <td><?php echo  $customer->getEmail() ?></td>
                  <td><?php echo  $customer->getCreatedAt() ?></td>                                  
                  <td><?php echo  $customer->getDateOfBirth() ?></td>
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
                  
                  
                </tr>
                <?php endforeach; ?>

                
                  </tbody>
              </table>
                </div>
            </li>
            
          </ul>
   



