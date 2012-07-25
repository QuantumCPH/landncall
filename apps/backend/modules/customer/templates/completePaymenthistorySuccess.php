<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

<div id="sf_admin_container">
    <h1><?php echo  __('Payment History') ?></h1><br />
    <form action="" id="searchform" method="POST" name="searchform" >
        <fieldset>
            <div class="form-row">
                <label>From:</label>
                <div class="content">
                     <?php echo input_date_tag('startdate', date("Y-m-d"), 'rich=true') ?>
                    
                </div>
            </div>
            <div class="form-row">
                <label>To:</label>
                <div class="content">
                     <?php echo input_date_tag('enddate', date("Y-m-d"), 'rich=true') ?>
                    
                </div>
            </div>
            <div class="form-row">
                <label>Type:</label>
                <div class="content">
                    <select name="description">
                        <option value="">All</option>
                        <?php  foreach($alltransactions as $alltransaction){  ?>
                        <option value="<?php  echo $alltransaction->getDescription();  ?>"><?php  echo $alltransaction->getDescription();  ?></option>
                        <?php  }?>
                    </select>
                </div>
            </div>
         </fieldset>
        <ul class="sf_admin_actions">
            <li><input type="submit" name="Search" value="Search" class="sf_admin_action_filter" /></li>
        </ul>
    </form>
</div>
           
<div id="sf_admin_container">
   <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign">
    <tr class="headings">
        <th  width="15%"  class="title"><?php echo __('Order Numer') ?></th>
        <th  width="20%" class="title"><?php echo __('Date &amp; Time') ?></th>
        <th  width="55%" class="title"><?php echo __('Description') ?></th>
        <th width="10%" class="title"><?php echo __('Amount') ?>(SEK)</th>
    </tr>
<?php 
    $amount_total = 0;
    $incrment=1;
    foreach($transactions as $transaction):
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
      <td><?php echo $transaction->getAmount(); $amount_total += $transaction->getAmount() ?></td>
    </tr>
<?php endforeach; 
      if(count($transactions)==0):
?>
    <tr>
        <td colspan="4"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
    </tr>
 <?php else: ?>
    <tr>
        <td align="right" colspan="3"><strong>Total</strong></td>
         <td ><?php echo format_number($amount_total) ?> SEK</td>
     </tr>	
<?php endif; ?>
  </table>
</div>         