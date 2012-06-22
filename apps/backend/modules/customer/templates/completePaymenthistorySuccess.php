<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

 
              <!--Always use tables for tabular data-->
<div id="sf_admin_container"><h1><?php echo  __('Payment History') ?></h1><br />
<form action="" id="searchform" method="POST" name="searchform" >


                <div class="dateBox-pt">
           <div class="formRow-pt" style="float:left;">
                    <label class="datelable" style="width:35px;margin-top: 3px;">From:</label>
                    <input type="text"   name="startdate" autocomplete="off" id="stdate" style="width: 110px;" value="<?php  if(isset($startdate)){ echo $startdate; }  ?>" />
                </div>
                <div class="formRow-pt1" style="float:left;margin-left:7px;">
                    &nbsp;<label class="datelable" style="width:35px;margin-top: 3px;">To:</label>
                    <input type="text"   name="enddate" autocomplete="off" id="endate" style="width: 110px;" value="<?php   if(isset($enddate)){ echo $enddate; }  ?>" />
                </div>
                <div class="formRow-pt1" style="float:left;margin-left:7px;">
                    &nbsp;<label class="datelable" style="width:20px;margin-top: 3px;">Type </label> <select name="description">
                        <option value="">All</option>
                    <?php  foreach($alltransactions as $alltransaction){  ?>

                    <option value="<?php  echo $alltransaction->getDescription();  ?>"><?php  echo $alltransaction->getDescription();  ?></option>
                  <?php  }
                    ?>



                    </select>
                 &nbsp;
               <span style="margin-left:10px;"><input type="submit" name="Search" value="Search" class="searchbtn" /></span>
                </div>

            </div><br clear="all" />

            </form><br /></div>
<div id="sf_admin_container">
    
    <table width="100%" cellspacing="0" cellpadding="2" class="callhistory tblAlign">
    
    <tr class="headings">
      <th  width="15%"  class="title"><?php echo __('Order Numer') ?></th>
      <th  width="20%" class="title"><?php echo __('Date &amp; Time') ?></th>
      <th  width="55%" class="title"><?php echo __('Description') ?></th>
      <th width="10%" class="title"><?php echo __('Amount') ?>(NOK)</th>       
    </tr>
                <?php 
                $amount_total = 0;
                $incrment=1;
                foreach($transactions as $transaction): ?>

                 <?php
                  if($incrment%2==0){
                   $class= 'class="even"';
                  }else{
                    $class= 'class="odd"';
                      }
 $incrment++;
                  ?>
                <tr <?php echo $class;?>>
                  <td><?php  echo $transaction->getOrderId() ?></td>
                  <td><?php echo  $transaction->getCreatedAt() ?></td>
                  <td><?php echo $transaction->getDescription() ?></td>
                  <td><?php echo $transaction->getAmount(); $amount_total += $transaction->getAmount() ?>
                            <?php 
//                            if($lang=="pl"){
//                                echo ('plz');
//                            }else if($lang=="en"){
//                                echo ('eur');
//                            }else{
                                echo ('NOK');
//                            } ?></td>
                
                </tr>
                <?php endforeach; ?>
                <?php if(count($transactions)==0): ?>
                <tr>
                	<td colspan="4"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
                </tr>
                <?php else: ?>
                <tr>
                	<td align="right" colspan="3"><strong>Total</strong></td>
                	<td ><?php echo format_number($amount_total) ?>
                            <?php 
//                            if($lang=="pl"){
//                                echo ('plz');
//                            }else if($lang=="en"){
//                                echo ('eur');
//                            }else{
                                echo ('NOK');
//                            } ?></td>
                </tr>	
                <?php endif; ?>
              </table>
</div>         