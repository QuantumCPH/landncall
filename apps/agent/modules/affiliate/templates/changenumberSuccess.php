<div id="sf_admin_container"><h1><?php echo __('Customer Detail') ?></h1></div>

 <div class="borderDiv">
<form name="" method="post"  action="<?php echo url_for(sfConfig::get('app_main_url').'affiliate/numberProcess') ?>">
<input type="hidden" value="<?php echo  $customer->getMobileNumber(); ?>" name="mobile_number" />
<input type="hidden" value="<?php echo  $product->getId();  ?>" name="productid" />
<input type="hidden" value="<?php echo  $product->getPrice();  ?>" name="extra_refill" />
<input type="hidden" value="<?php echo  $newNumber;  ?>" name="newnumber" />
<input type="hidden" value="<?php echo  $countrycode;  ?>" name="countrycode" />
<table width="70%">
<tr>
<tr>
<th width="50%">
New Mobile Number
</th>
<th width="50%">
<?php echo  $newNumber;  ?>
</th>
</tr>
<th width="50%">
Customer Name
</th>
<th width="50%">
<?php echo  $customer->getFirstName(); ?>&nbsp;<?php echo  $customer->getLastName(); ?>
</th>
</tr><tr>
<th width="50%">
Old Mobile Number
</th>
<th width="50%">
<?php echo  $customer->getMobileNumber(); ?>
</th>
</tr><tr>
<th colspan="2" width="100%" align="left">
Product Detail
</th>
</tr>
<tr>
<th width="50%">
<?php echo $product->getDescription(); ?>
</th>
<th width="50%">
<?php echo  $product->getPrice(); ?>
</th>
</tr>
<tr><th  width="50%">&nbsp;</th>
<th  width="50%">
 <input style="width:100px;text-align:center" type="submit" name="Pay" value="pay" />
</th>
</tr>
</table>
</form>