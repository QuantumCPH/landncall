<?php if(isset($_REQUEST['message']) && $_REQUEST['message']!=""){  ?>

<div class="save-ok">
<h2>Employee is added successfully</h2>
</div>
<?php  }   ?>
<div id="sf_admin_container">
<h1>New My employee</h1></div>
<form id="sf_admin_form" name="sf_admin_edit_form" method="post" enctype="multipart/form-data" action="saveEmployee">
    <div id="sf_admin_content">
    <fieldset id="sf_fieldset_none" class="">
    <table style="padding: 0px;"  id="sf_admin_container" >
        <tr>
        <td style="padding: 5px;">First name:</td>
        <td style="padding: 5px;"><input type="text" name="first_name" id="employee_first_name"  class="required"  size="25" /></td>
                </tr>
                 <tr>
        <td style="padding: 5px;">Last name:</td>
        <td style="padding: 5px;"> <input type="text" name="last_name" id="employee_last_name"   class="required"   size="25" /></td>
                </tr>
                 <tr>
        <td style="padding: 5px;">Company:</td>
        <td style="padding: 5px;">
  <select name="company_id" id="employee_company_id"    class="required"  style="width:190px;">
      <option value="" selected="selected"></option>
      <?php foreach($companys as $company){  ?>
<option value="<?php echo $company->getId();   ?>"><?php echo $company->getName()   ?></option>
<?php   }  ?>
</select>  </td>
                </tr>
                  <tr>
        <td style="padding: 5px;">Country Code:</td>
        <td style="padding: 5px;"> <input type="text" name="country_code" id="employee_country_code"   size="25"   class="required" /> </td>
                </tr>
                 <tr>
        <td style="padding: 5px;">Mobile number:</td>
        <td style="padding: 5px;"> <input type="text" name="mobile_number" id="employee_mobile_number"  size="25"   class="required" /> </td>
                </tr>
                 <tr>
        <td style="padding: 5px;">Email:</td>
        <td style="padding: 5px;"> <input type="text" name="email" id="employee_email"   class="required email"  size="25" /> </td>
                </tr>
                 <tr>
        <td style="padding: 5px;">Rese number:</td>
        <td style="padding: 5px;">
  <select name="registration_type" id="employee_registration_type">
         <option value="0"> no</option>
      <option value="1"> yes</option>
    
</select> </td>
                </tr>
  <!--<tr>
        <td>App code:</td>
        <td> <input type="text" name="app_code" id="employee_app_code" value="" size="25" /></td>
                </tr>
   <tr>
        <td>Is app registered:</td>
        <td><input type="checkbox" name="is_app_registered" id="employee_is_app_registered" value="1" /> </td>
                </tr>
             <tr>
        <td>Password:</td>
        <td>  <input type="text" name="password" id="employee_password" value="" size="25" /></td>
                </tr>-->
                  <tr>
        <td style="padding: 5px;">Product:</td>
        <td style="padding: 5px;"> <select name="productid" id="productid"    class="required"  >
      <option value="" selected="selected"></option>
      <?php foreach($products as $product){  ?>
<option value="<?php echo $product->getId();   ?>"><?php echo $product->getName()   ?></option>
<?php   }  ?>
</select></td>
                </tr>
                  <tr>
        <td style="padding: 5px;">Product Price:</td>
        <td style="padding: 5px;"> <input type="text" name="price" id="employee_password"   size="25" />  </td>
                </tr>
   <tr>

       <td colspan="2"><ul class="sf_admin_actions">

  <li>  <input class="sf_admin_action_list" value="list" type="button" onclick="document.location.href='../employee';" /></li>
  <li><input type="submit" name="save" value="save" class="sf_admin_action_save" /> </li>

</ul>
           

        </td>
                </tr>
    </table></fieldset>
    </div>
</form>
