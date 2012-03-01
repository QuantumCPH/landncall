<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

    <table width="50%"  cellspacing="0" cellpadding="2"  style="text-align: left;float: left;"  >
         <tr >
             <td colspan="2"> <table border="0" cellspacing="4" cellpadding="4" >  <tr  style="background-color: #838483;color:#FFFFFF;padding: 5px;">
                                    <td align="left" ><a  style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="allRegisteredCustomer">View All Customer</a></td>
                                    <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="paymenthistory?id=<?php echo $_REQUEST['id'];  ?>">Payment History</a></td>
                                    <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="callhistory?id=<?php echo $_REQUEST['id'];  ?>">Call History</a></td>

                      </tr> </table></td>

                      </tr>
                       <tr>
                            <th align="left" colspan="2">&nbsp;</th>

                      </tr>
                       <tr  style="background-color: #CCCCFF;color: #000000;">
                          <th  width="50%"   style="text-align:left;" >Customer Detail</th>
							<td  width="50%" ></td>
                      </tr>
                      
                       <tr   style="background-color: #CCCCFF;color: #000000;">
                          <th  id="sf_admin_list_th_id"  style="float:left;" >Description</th>
							<td   >Value</td>
                      </tr>
                          <tr  style="background-color:#FFFFFF">
                    <th    style="float:left;"  >Customer Balance</th>
                     <td  ><?php
                           $uniqueId=$customer->getUniqueid();
                         $cuid=$customer->getId();

                          

                                  $cp = new Criteria();
                                  $cp->add(CustomerProductPeer::CUSTOMER_ID, $cuid);
                                  $custmpr = CustomerProductPeer::doSelectOne($cp);
                                   $p = new Criteria();
                                   $p->add(ProductPeer::ID, $custmpr->getProductId());
                                   $products=ProductPeer::doSelectOne($p);
                                   $pus = 0;
                                    if($uniqueId>200000){
                                  $pus=$products->getProductCountryUs();
                                    }

               if($pus==1){
                           echo   $Tes=ForumTel::getBalanceForumtel($customer->getId());
                               echo " sek balanci ".$amt=CurrencyConverter::convertUsdToSek($Tes);
   echo " SEK";
                            }else{


        echo  $customer_balance;
          echo "Sek";
                            }
                          
                     ?> </td>
                      </tr>

                     
                   <tr  style="background-color:#EEEEFF">
                    <th    style="float:left;"  >Id</th>
                     <td  ><?php  echo $customer->getId() ?></td>
                      </tr>
                     
                      <tr>
                    <th id="sf_admin_list_th_first_name" style="float:left;" >First Name</th>
                      <td><?php echo  $customer->getFirstName() ?></td>
                        </tr>
                      <tr   style="background-color:#EEEEFF">
                    <th id="sf_admin_list_th_last_name"  style="float:left;" >Last Name</th>
                       <td><?php echo  $customer->getLastName() ?></td>
                          </tr>
                      <tr>
		    <th id="sf_admin_list_th_mobile_number" style="float:left;"  >Mobile Number</th>
                      <td><?php echo  $customer->getMobileNumber() ?></td>
                         </tr>
                         <tr  style="background-color:#EEEEFF">

		     <th id="sf_admin_list_th_mobile_number" style="float:left;"  >Password</th>
                         <td><?php echo  $customer->getPlainText() ?></td>
                       </tr>
                         
                       
<?php
$val="";
$val=$customer->getReferrerId();
if(isset($val) && $val!=""){  ?>
                      <tr  style="background-color:#EEEEFF">
		    <th id="sf_admin_list_th_agent" style="float:left;" >Agent</th>
                    <?php $agent = AgentCompanyPeer::retrieveByPK( $customer->getReferrerId()) ?>
                  <td><?php echo  $agent->getName() ?></td>
                      </tr>
                         <tr>
                      <th id="sf_admin_list_th_agent" style="float:left;" >Agent CVR</th>
                      <td><?php echo  $agent->getCvrNumber() ?></td>
		      </tr>

                      <?php } ?>
                         <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_address"  style="float:left;" >Address</th>
                        <td><?php echo  $customer->getAddress() ?></td>
                      </tr>
                         <tr>
                      <th id="sf_admin_list_th_city"  style="float:left;" >City</th>
                        <td><?php echo  $customer->getCity() ?></td>
                      </tr>
                         <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_po_box_number"  style="float:left;" >PO-BOX Number</th>
                      <td><?php echo  $customer->getPoBoxNumber() ?></td>

                      </tr>
                         <tr>
                      <th id="sf_admin_list_th_email"  style="float:left;" >Email</th>
                         <td><?php echo  $customer->getEmail() ?></td>
                      </tr>
                         <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_created_at"  style="float:left;" >Created At</th>
                            <td><?php echo  $customer->getCreatedAt() ?></td>

  </tr>
                         <tr>

                    <th id="sf_admin_list_th_date_of_birth" style="float:left;" >Date Of Birth</th>
                      <td><?php echo  $customer->getDateOfBirth() ?></td>
                      </tr>
                         <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_auto_refill" style="float:left;" >Auto Refill</th>
                        <?php if ($customer->getAutoRefillAmount()!=NULL && $customer->getAutoRefillAmount()>1){ ?>
                  <td>Yes</td>
                  <?php } else
                      { ?>
                  <td>No</td>
                  <?php } ?>
                        </tr>
                         <tr>
                        <th id="sf_admin_list_th_auto_refill" style="float:left;" >Unique ID</th>
                         <td>  <?php  echo $customer->getUniqueid();     ?>   </td>
                        </tr  >
                         <tr style="background-color:#EEEEFF">
                       <th id="sf_admin_list_th_auto_refill" style="float:left;" >Active No</th>
                        <td>  <?php  $unid   =  $customer->getUniqueid();
        if(isset($unid) && $unid!=""){
            $un = new Criteria();
            $un->add(CallbackLogPeer::UNIQUEID, $unid);

            $un -> addDescendingOrderByColumn(CallbackLogPeer::CREATED);
            $unumber = CallbackLogPeer::doSelectOne($un);
            echo $unumber->getMobileNumber();            
         }else{  }  ?> </td>
                         </tr>
                         <?php  $uid=0;
                      $uid=$customer->getUniqueid();
                      if(isset($uid) && $uid>0){
                      ?>

                       <tr  style="background-color:#FFFFFF">
                    <th    style="float:left;"  >IMSI number</th>
                     <td  ><?php  echo $unumber->getImsi();  ?></td>
                      </tr>
                        <tr  style="background-color:#EEEEFF">
                    <th    style="float:left;"  >IMSI Registration Date</th>
                     <td  ><?php  echo $unumber->getCreated();  ?></td>
                      </tr>

                      <?php } ?>
                  <tr style="background-color:#EEEEFF">
                       <th id="sf_admin_list_th_auto_refill" style="float:left;" >Resenummer </th>
                        <td>  <?php  $cuid   =  $customer->getId();
        if(isset($cuid) && $cuid!=""){
            $un = new Criteria();
            $un->add(SeVoipNumberPeer::CUSTOMER_ID, $cuid);
            $un->add(SeVoipNumberPeer::IS_ASSIGNED, 1);
             $vounumber = SeVoipNumberPeer::doSelectOne($un);
             if(isset($vounumber)&& $vounumber!="" ){
            echo $vounumber->getNumber();
             }
         }else{  }  ?> </td>
                         </tr>

                         <?php
                  if($uniqueId>200000){
                           $us = new Criteria();
            $us->add(UsNumberPeer::CUSTOMER_ID, $cuid);
             $usnumber = UsNumberPeer::doSelectOne($us);
                                       ?>
                          <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_created_at"  style="float:left;" >MSISDN No</th>
                      <td><?php echo  $usnumber->getMsisdn() ?></td>

  </tr>
   <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_created_at"  style="float:left;" >ICCID NO</th>
                      <td><?php echo   $usnumber->getIccid() ?></td>

  </tr>
   <tr  style="background-color:#EEEEFF">
                      <th id="sf_admin_list_th_created_at"  style="float:left;" >US Mobile Number</th>
                      <td><?php echo   $usnumber->getUsMobileNumber() ?></td>

  </tr>


<?php } ?>

                  
              </table>
              
        




