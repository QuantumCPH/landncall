
<?php if(isset($_REQUEST['message']) && $_REQUEST['message']=="edit"){  ?>

<div class="save-ok">
<h2>Employee updated successfully</h2>
</div>
<?php  }   ?>

<?php if(isset($_REQUEST['message']) && $_REQUEST['message']=="add"){  ?>

<div class="save-ok">
<h2>Employee added successfully</h2>
</div>
<?php  }   ?>








<div  id="sf_admin_container">
<h1>My employee List</h1>
</div>

<div id="sf_admin_header">
<a target="_self" class="external_link" href="<?php echo url_for('employee/add') ?>" style="text-decoration:none;">Create New</a>
</div>
<br/>
<table width="950"  style="border: 1px;" class="sf_admin_list" cellspacing="0">
  <thead>
      <tr style="background-color:#CCCCFF;">
      
      <th align="left"  id="sf_admin_list_th_name">Company</th>
      <th align="left"  id="sf_admin_list_th_name">First name</th>
     
      <th align="left"  id="sf_admin_list_th_name">product</th>
    
      <th align="left" id="sf_admin_list_th_name">Mobile number</th>
        <th align="left"  id="sf_admin_list_th_name">Employee balance</th>
      <th align="left"  id="sf_admin_list_th_name">Created at</th>
<!--        <th align="left">App Registered</th>
      <th align="left">App code</th>
   
      <th align="left">Password</th>-->
        <th align="left"  id="sf_admin_list_th_name">Action</th>
    </tr>
  </thead>
  <tbody>
        <?php      $amount_total = 0;
                       $incrment=1;
   foreach ($employees as $employee): ?>

       <?php
                  if($incrment%2==0){
                  $colorvalue="#FFFFFF";
                  }else{

                      $colorvalue="#EEEEFF";
                      }
 $incrment++;
                  ?>
    <tr  style="background-color:<?php echo $colorvalue; ?>">
    
      <td><?php  $comid=$employee->getCompanyId();
      if(isset($comid) && $comid!=""){
               $c = new Criteria();
      $c->add(CompanyPeer::ID, $employee->getCompanyId());
  $companys = CompanyPeer::doSelectOne($c);

              echo $companys->getName();
      }
              ?></td>
      <td><?php echo htmlspecialchars($employee->getFirstName()); ?></td>
  
      <td>
          <?php  $pid=$employee->getProductId();
      if(isset($pid) && $pid!=""){
               $c = new Criteria();
      $c->add(ProductPeer::ID, $pid);
  $products = ProductPeer::doSelectOne($c);

              echo $products->getName();
      }
              ?>


      </td>
      <td><?php echo $employee->getMobileNumber() ?></td>
      <td> <?php  $mobileID= $employee->getCountryMobileNumber();
                                 $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=a'.$mobileID.'&type=account');
        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);
         $telintaGetBalance;

          $telintaGetBalance1 = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=cb'.$mobileID.'&type=account');
        $telintaGetBalance1 = str_replace('success=OK&Balance=', '', $telintaGetBalance1);
        $telintaGetBalance1 = str_replace('-', '', $telintaGetBalance1);
         $telintaGetBalance;

      echo  $balnc=(float)$telintaGetBalance+(float)$telintaGetBalance;
          echo " Sek";
                                                ?></td>
      <td><?php echo substr($employee->getCreatedAt(),0,10); ?></td>
   
    <!--  <td align="center">  <?php //$appval=$employee->getIsAppRegistered();  if(isset($appval) && $appval==1){   ?> <img alt="Tick" src="/sf/sf_admin/images/tick.png">  <?php //} ?></td>
       <td><?php //echo $employee->getAppCode() ?></td>
       <td><?php //echo $employee->getPassword() ?></td>-->
       <td><a href="<?php echo url_for('employee/edit?id='.$employee->getId()) ?>"><img src="/sf/sf_admin/images/edit_icon.png" title="edit" alt="edit"></a><a href="employee/del?id=<?php echo $employee->getId() ?>" ><img src="/sf/sf_admin/images/delete_icon.png" title="delete" alt="delete"></a>
       <a href="<?php echo url_for('employee/view?id='.$employee->getId()) ?>"><img src="/sf/sf_admin/images/default_icon.png" title="view" alt="view"></a>
        <!--    <a href="<?php echo url_for('employee/view?id='.$employee->getId()) ?>"><img src="/sf/sf_admin/images/default_icon.png" title="view" alt="call history"></a>
     -->  </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br/>
  <div id="sf_admin_header">
<a target="_self" class="external_link" href="<?php echo url_for('employee/add') ?>" style="text-decoration:none;">Create New</a>

</div>

	