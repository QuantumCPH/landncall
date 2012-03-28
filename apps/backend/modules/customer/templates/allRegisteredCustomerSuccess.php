<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

		<table width="75%" cellspacing="0" cellpadding="2" style="float:left;">
                    <thead>
                          <tr  ><th colspan="7"> All Registered Customer </th>  </tr>
                        <tr style="background-color: #CCCCFF;color: #000000;">
                     <th width="10%" style="text-align: left" >Id</th>
                  <th  width="20%" style="text-align: left"  >Customer Number</th>
                      <th  width="20%" style="text-align: left" >Mobile Number</th>
                       <th width="20%" style="text-align: left" >First Name</th>
                    <th  width="20%"  style="text-align: left" >Last Name</th>
                       <th  width="20%"  style="text-align: left" >Unique ID</th>


                    <th width="10%" style="text-align: left"  >Action</th>
                        </tr>
		                  </thead>
                  <tfoot>
                    <tr><th colspan="7">
                    <div class="float:right">
                    </div>
                    <?php echo count($customers)." - Results" ?></th></tr>
                  </tfoot>
                  <tbody>
                         <?php   $incrment=1;    ?>
                <?php foreach($customers as $customer): ?>

                 <?php
                  if($incrment%2==0){
                  $colorvalue="#FFFFFF";
                  }else{

                      $colorvalue="#EEEEFF";
                      }
                  
                  ?>

                      <tr style="background-color:<?php echo $colorvalue;   ?>">
                      <td><?php echo $incrment;  ?></td>
                  <td><?php  echo $customer->getId() ?></td>
                   <td><?php echo  $customer->getMobileNumber() ?></td>
                  <td><?php echo  $customer->getFirstName() ?></td>
                    <td><?php echo  $customer->getLastName() ?></td>
                       <td><?php echo  $customer->getUniqueid() ?></td>
                 <td><a href="<?php echo url_for('customer/editcustomer?id='.$customer->getId()) ?>"><img src="/sf/sf_admin/images/edit_icon.png" title="edit" alt="edit"></a>&nbsp;<a href="customerDetail?id=<?php  echo $customer->getId() ?>"><img alt="view Detail" title="view Detail" src="http://admin.zerocall.com/sf/sf_admin/images/default_icon.png" ></a>
                      </td>
             
                </tr>
<?php   $incrment++;    ?>
                <?php endforeach; ?>
                  </tbody>
              </table>
                </div>
            </li>

          </ul>




