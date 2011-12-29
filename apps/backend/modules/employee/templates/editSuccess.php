<div id="sf_admin_container"><h1>Edit Myemployee</h1>
</div>
<?php if (isset($_REQUEST['message']) && $_REQUEST['message']!= "") {
 ?>

    <div class="save-ok">
        <h2>Employee is added successfully</h2>
    </div>

<?php } ?>


<form id="sf_admin_form" name="sf_admin_edit_form" method="post" enctype="multipart/form-data" action="../../updateEmployee">

    <input type="hidden" name="id"    value="<?php echo $employee->getId(); ?>"  size="25" />
 <div id="sf_admin_content">
    <fieldset id="sf_fieldset_none" class="">
    <table id="sf_admin_container">

        <tr>
            <td style="padding: 5px;">First name:</td>
            <td style="padding: 5px;"><input type="text" name="first_name" id="employee_first_name"  value="<?php echo $employee->getFirstName(); ?>"   class="required"  size="25" /></td>
        </tr>
        <tr>
            <td style="padding: 5px;">Last name:</td>
            <td style="padding: 5px;"> <input type="text" name="last_name" id="employee_last_name"   value="<?php echo $employee->getLastName(); ?>"   class="required"    size="25" /></td>
        </tr>
        <tr>
            <td style="padding: 5px;">Company:</td>
            <td style="padding: 5px;">
                <select name="company_id" id="employee_company_id"  class="required"  style="width:190px;" disabled="disabled">
                    <option value="" selected="selected"></option>
<?php foreach ($companys as $company) { ?>
                    <option value="<?php echo $comid = $company->getId(); ?>"   <?php $varcom = $employee->getCompanyId();
                    if (isset($varcom) && $varcom == $comid) { ?>  selected="selected" <?php } ?>   ><?php echo $company->getName() ?></option>
<?php } ?>

                </select>  </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Country Code:</td>
            <td style="padding: 5px;"> <input type="text" name="country_code" id="employee_country_code"   class="required"   value="<?php echo $employee->getCountryCode(); ?>" size="25" /> </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Mobile number:</td>
            <td style="padding: 5px;"> <input type="text" name="mobile_number" id="employee_mobile_number"   class="required"   value="<?php echo $employee->getMobileNumber(); ?>"   size="25" /> </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Email:</td>
            <td style="padding: 5px;"> <input type="text" name="email" id="employee_email"   class="required"   value="<?php echo $employee->getEmail(); ?>"  size="25" /> </td>
        </tr>
      
        <tr>
            <td style="padding: 5px;">Rese number:</td>
            <td  style="padding: 5px;">
                <select name="registration_type" id="employee_company_id"   <?php  $varval = $employee->getRegistrationType();
                if (isset($varval) && $varval == "1") { ?>  disabled="disabled"   <?php } ?> >
                    <option value="0"  <?php  $varval = $employee->getRegistrationType();
                 if (isset($varval) && $varval == "0") { ?>  selected="selected" <?php  } ?> >No</option>
                    <option value="1"  <?php  $varval = $employee->getRegistrationType();
                if (isset($varval) && $varval == "1") { ?>  selected="selected" <?php  } ?> > yes</option>
                </select> </td>
        </tr>


        
         <!-- <tr>
            <td>App code:</td>
            <td> <input type="text" name="app_code" id="employee_app_code"  value="<?php //echo $employee->getAppCode(); ?>"  size="25" /></td>
        </tr>
        <tr>
            <td>Is app registered:</td>
            <td><input type="checkbox" name="is_app_registered" id="employee_is_app_registered" value="1"  <?php // $varap = $employee->getIsAppRegistered();
             //   if (isset($varap) && $varap == 1) { ?>  checked="checked" <?php // } ?>  /> </td>
        </tr>
        <tr>
            <td>Password:</td>
            <td>  <input type="text" name="password" id="employee_password"  value="<?php // echo $employee->getPassword(); ?>"  size="25" /></td>
        </tr>-->
        <tr>
            <td style="padding: 5px;">Product:</td>
            <td style="padding: 5px;"> <select name="productid" id="employee_product_id"   class="required" >
                    <option value="" selected="selected"></option>
<?php foreach ($products as $product) { ?>
                    <option value="<?php echo $pid = $product->getId(); ?>"   <?php $varp = $employee->getProductId();
                    if (isset($varp) && $varp == $pid) { ?>  selected="selected" <?php } ?>><?php echo $product->getName() ?></option>
<?php } ?>

                </select></td>
        </tr>


        <tr>
            <td style="padding: 5px;">Product Price:</td>
            <td style="padding: 5px;"> <input type="text" name="price" id="employee_password"   class="required"  value="<?php echo $employee->getProductPrice(); ?>"  size="25" />  </td>
        </tr>
        <tr>
           <td colspan="2"><ul class="sf_admin_actions">

  <li><input class="sf_admin_action_list" value="list" type="button" onclick="document.location.href='../../../employee';" />
  </li><li><input type="submit" name="Update" value="Update" class="sf_admin_action_save" /></li></ul> </td>
        </tr>

    </table>
 </div></div>
</form>
