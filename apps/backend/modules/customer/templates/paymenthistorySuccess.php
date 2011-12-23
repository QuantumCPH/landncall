<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

<?php /* 
            <li>
              <label>Phone number:</label>
              <select>
                <option>&nbsp;</option>
              </select>
            </li>
            <li>
              <label>From:</label>
              <select class="quater">
                <option>&nbsp;</option>
              </select>
              <select class="quater">
                <option>&nbsp;</option>
              </select>
              <select class="quater">
                <option>&nbsp;</option>
              </select>
            </li>
            <li>
              <label>To:</label>
              <select class="quater">
                <option>&nbsp;</option>
              </select>
              <select class="quater">
                <option>&nbsp;</option>
              </select>
              <select class="quater">
                <option>&nbsp;</option>
              </select>
            </li>
            <li>
              <button><?php echo __('Show') ?></button>
            </li>
*/ ?>
             
              <!--Always use tables for tabular data-->
              <table width="70%" cellspacing="0" cellpadding="0" class="callhistory" style="float: left;">
                  <tr>
                            <th align="left" colspan="4">&nbsp;</th>

                      </tr>
                              <tr>
                            <th align="left" colspan="4"> <table border="0" cellspacing="4" cellpadding="4" >  <tr  style="background-color: #838483;color:#FFFFFF;padding: 5px;">
                                    <td align="left" ><a  style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="allRegisteredCustomer">View All Customer</a></td>
                                    <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="customerDetail?id=<?php echo $_REQUEST['id'];  ?>">Customer Detail</a></td>
                                    <td align="left"><a style="background-color: #838483;color:#FFFFFF;text-decoration: none;" href="callhistory?id=<?php echo $_REQUEST['id'];  ?>">Call History</a></td>

                      </tr> </table></th>
                          

                      </tr>                              <tr><th colspan="4"  style="background-color: #CCCCFF;color: #000000;text-align: left">Payment History</th></tr>
                            
                   <tr style="background-color: #CCCCFF;color: #000000;">
                       <th width="15%"  align="left"><?php echo __('Order Numer') ?></th>
                          <th width="25%"  align="left"><?php echo __('Date &amp; Time') ?></th>
                          <th width="50%"  align="left"><?php echo __('Description') ?></th>
                          <th width="10%" align="left"><?php echo __('Amount') ?></th>
                              </tr>
                <?php 
                $amount_total = 0;
                $incrment=1;
                foreach($transactions as $transaction): ?>

                 <?php
                  if($incrment%2==0){
                  $colorvalue="#FFFFFF";
                  }else{

                      $colorvalue="#EEEEFF";
                      }
 $incrment++;
                  ?>
                <tr  style="background-color:<?php echo $colorvalue;   ?>">
                  <td><?php  echo $transaction->getOrderId() ?></td>
                  <td><?php echo  $transaction->getCreatedAt() ?></td>
                  <td><?php echo $transaction->getDescription() ?></td>
                  <td><?php echo $transaction->getAmount(); $amount_total += $transaction->getAmount() ?>
                            <?php if($lang=="pl"){
                                echo ('plz');
                            }else if($lang=="en"){
                                echo ('eur');
                            }else{
                                echo ('SEK');
                            } ?></td>
                
                </tr>
                <?php endforeach; ?>
                <?php if(count($transactions)==0): ?>
                <tr>
                	<td colspan="5"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
                </tr>
                <?php else: ?>
                <tr>
                	<td colspan="3" align="right"><strong>Total</strong></td>
                	<td><?php echo format_number($amount_total) ?>
                            <?php if($lang=="pl"){
                                echo ('plz');
                            }else if($lang=="en"){
                                echo ('eur');
                            }else{
                                echo ('SEK');
                            } ?></td>
                	<td>&nbsp;</td>
                </tr>	
                <?php endif; ?>
              </table>
           