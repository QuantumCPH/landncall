<div id="sf_admin_container"><h1><?php echo __('Customer Detail') ?></h1></div>
<div class="borderDiv">
    <form name="" method="post"  action="<?php echo url_for(sfConfig::get('app_main_url').'affiliate/numberProcess') ?>">
    <input type="hidden" value="<?php echo  $customer->getMobileNumber(); ?>" name="mobile_number" />
    <input type="hidden" value="<?php echo  $product->getId();  ?>" name="productid" />
    <input type="hidden" value="<?php echo  $product->getPrice();  ?>" name="extra_refill" />
    <input type="hidden" value="<?php echo  $newNumber;  ?>" name="newnumber" />
    <input type="hidden" value="<?php echo  $countrycode;  ?>" name="countrycode" />
    <ul class="fl col">
        <li>New Mobile Number</li>
        <li><?php echo  $newNumber;  ?></li>
        <li>Customer Name</li>
        <li><?php echo  $customer->getFirstName(); ?>&nbsp;<?php echo  $customer->getLastName(); ?></li>
        <li>Old Mobile Number</li>
        <li><?php echo  $customer->getMobileNumber(); ?></li>
        <li>Product Detail</li>
        <li><?php echo $product->getDescription(); ?></li>
        <li><?php echo  $product->getPrice(); ?></li>
        <li><input type="submit" name="Pay" value="pay" /></li>
    </ul>
    </form>
</div>