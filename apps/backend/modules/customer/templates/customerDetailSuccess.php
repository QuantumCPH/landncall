<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$pus=0;
?>
<div id="sf_admin_container">
     <ul class="customerMenu" style="margin:10px 0;">
            <li><a class="external_link" href="allRegisteredCustomer"><?php echo  __('View All Customer') ?></a></li>
            <li><a class="external_link" href="paymenthistory?id=<?php echo $_REQUEST['id'];  ?>"><?php echo  __('Payment History') ?></a></li>
            <li><a class="external_link"  href="callhistory?id=<?php echo $_REQUEST['id'];  ?>"><?php echo  __('Call History') ?></a></li>
        </ul>
<h1><?php echo  __('Customer Detail') ?></h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign">



                          <tr>
                    <td width="11%" class="leftHeadign">Customer Balance</td>
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

                                  $pus=$products->getProductCountryUs();
                                   

               if($pus==1){
                            $Tes=ForumTel::getBalanceForumtel($customer->getId());
                               echo  $amt=CurrencyConverter::convertUsdToSek($Tes);
   echo " SEK";
                            }else{


        echo  $customer_balance;
          echo "Sek";
                            }
                          
                     ?> </td>
                      </tr>

                     
                   <tr>
                    <td    class="leftHeadign"  >Id</td>
                     <td  ><?php  echo $customer->getId() ?></td>
                      </tr>
                     
                      <tr>
                    <td id="sf_admin_list_th_first_name" class="leftHeadign" >First Name</td>
                      <td><?php echo  $customer->getFirstName() ?></td>
                        </tr>
                      <tr >
                    <td id="sf_admin_list_th_last_name"  class="leftHeadign" >Last Name</td>
                       <td><?php echo  $customer->getLastName() ?></td>
                          </tr>
                      <tr>
		    <td id="sf_admin_list_th_mobile_number" class="leftHeadign"  >Mobile Number</td>
                      <td><?php echo  $customer->getMobileNumber() ?></td>
                         </tr>
                         <tr>

		     <td id="sf_admin_list_th_mobile_number" class="leftHeadign"  >Password</td>
                         <td><?php echo  $customer->getPlainText() ?></td>
                       </tr>
                         
                       
<?php
$val="";
$val=$customer->getReferrerId();
if(isset($val) && $val!=""){  ?>
                      <tr>
		    <td id="sf_admin_list_th_agent" class="leftHeadign" >Agent</td>
                    <?php $agent = AgentCompanyPeer::retrieveByPK( $customer->getReferrerId()) ?>
                  <td><?php echo  $agent->getName() ?></td>
                      </tr>
                         <tr>
                      <td id="sf_admin_list_th_agent" class="leftHeadign" >Agent CVR</td>
                      <td><?php echo  $agent->getCvrNumber() ?></td>
		      </tr>

                      <?php } ?>
                         <tr>
                      <td id="sf_admin_list_th_address"  class="leftHeadign" >Address</td>
                        <td><?php echo  $customer->getAddress() ?></td>
                      </tr>
                         <tr>
                      <td id="sf_admin_list_th_city"  class="leftHeadign" >City</td>
                        <td><?php echo  $customer->getCity() ?></td>
                      </tr>
                         <tr>
                      <td id="sf_admin_list_th_po_box_number"  class="leftHeadign" >PO-BOX Number</td>
                      <td><?php echo  $customer->getPoBoxNumber() ?></td>

                      </tr>
                         <tr>
                      <td id="sf_admin_list_th_email"  class="leftHeadign" >Email</td>
                         <td><?php echo  $customer->getEmail() ?></td>
                      </tr>
                         <tr>
                      <td id="sf_admin_list_th_created_at"  class="leftHeadign" >Created At</td>
                            <td><?php echo  $customer->getCreatedAt() ?></td>

  </tr>
                         <tr>

                    <td id="sf_admin_list_th_date_of_birth" class="leftHeadign" >Date Of Birth</td>
                      <td><?php echo  $customer->getDateOfBirth() ?></td>
                      </tr>
                         <tr>
                      <td id="sf_admin_list_th_auto_refill" class="leftHeadign" >Auto Refill</td>
                        <?php if ($customer->getAutoRefillAmount()!=NULL && $customer->getAutoRefillAmount()>1){ ?>
                  <td>Yes</td>
                  <?php } else
                      { ?>
                  <td>No</td>
                  <?php } ?>
                        </tr>
                         <tr>
                        <td id="sf_admin_list_th_auto_refill" class="leftHeadign" >Unique ID</td>
                         <td>  <?php  echo $customer->getUniqueid();     ?>   </td>
                        </tr  >
                         <tr style="background-color:#EEEEFF">
                       <td id="sf_admin_list_th_auto_refill" class="leftHeadign" >Active No</td>
                        <td>  <?php  $unid   =  $customer->getUniqueid();
        if(isset($unid) && $unid!=""){
            $un = new Criteria();
            $un->add(CallbackLogPeer::UNIQUEID, $unid);

            $un -> addDescendingOrderByColumn(CallbackLogPeer::CREATED);
            $unumber = CallbackLogPeer::doSelectOne($un);

               if($pus==1){
   $us = new Criteria();
            $us->add(UsNumberPeer::CUSTOMER_ID, $cuid);
             $usnumber = UsNumberPeer::doSelectOne($us);
             echo   $usnumber->getUsMobileNumber();
               }else{
                   echo $unumber->getMobileNumber();    
               }

         }else{  }  ?> </td>
                         </tr>
                         <?php  $uid=0;
                      $uid=$customer->getUniqueid();
                      if(isset($uid) && $uid>0){
                      ?>

                       <tr  style="background-color:#FFFFFF">
                    <td    class="leftHeadign"  >IMSI number</td>
                     <td  ><?php  echo $unumber->getImsi();  ?></td>
                      </tr>
                        <tr>
                    <td    class="leftHeadign"  >IMSI Registration Date</td>
                     <td  ><?php  echo $unumber->getCreated();  ?></td>
                      </tr>

                      <?php } ?>
                  <tr style="background-color:#EEEEFF">
                       <td id="sf_admin_list_th_auto_refill" class="leftHeadign" >Resenummer </td>
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
                 if($pus==1){
                        
                                       ?>
                          <tr>
                      <td id="sf_admin_list_th_created_at"  class="leftHeadign" >MSISDN No</td>
                      <td><?php echo  $usnumber->getMsisdn() ?></td>

  </tr>
   <tr>
                      <td id="sf_admin_list_th_created_at"  class="leftHeadign" >ICCID NO</td>
                      <td><?php echo   $usnumber->getIccid() ?></td>

  </tr>
   <tr>
                      <td id="sf_admin_list_th_created_at"  class="leftHeadign" >US Mobile Number</td>
                      <td><?php echo   $usnumber->getUsMobileNumber() ?></td>

  </tr>


<?php } ?>

                  
              </table>
              
        




