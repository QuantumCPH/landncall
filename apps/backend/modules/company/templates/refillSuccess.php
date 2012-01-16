<?php /*if($sf_user->hasFlash('message')){ ?>


<div class="save-ok">
<h2><?PHP echo __($sf_user->getFlash('message'));?> </h2>
</div>
  
<?php    }*/   ?>

<div id="sf_admin_container"><h1>Refill</h1></div>

<form id="sf_admin_form" name="sf_admin_edit_form" method="post" enctype="multipart/form-data" action="Refill">
    <div id="sf_admin_content">
    <fieldset id="sf_fieldset_none" class="">
    <table style="padding: 0px;"  id="sf_admin_container" >
    <tr>
        <td style="padding: 5px;">Company:</td>
        <td style="padding: 5px;">
            <select name="company_id" id="employee_company_id"    class="required"  style="width:190px;">
            <option value="" selected="selected">Select Company</option>
            <?php foreach($companys as $company){  ?>
            <option value="<?php echo $company->getId();   ?>"><?php echo $company->getName()   ?></option>
            <?php   }  ?>
            </select>
        </td>
    </tr>
        <tr>
        <td style="padding: 5px;">Refill:</td>
        <td style="padding: 5px;">
            <input type="text" id="refill" name="refill" class="required digits" style="width:180px;">
<!--            <select name="refill" id="refill" class="required"  style="width:190px;">
            <?php   $value= ProductPeer::getRefillHashChoices();
                    foreach($value as $key=>$values){  ?>
                    <option value="<?php echo $key;   ?>"><?php echo $values;   ?></option>
            <?php   }  ?>
            </select>-->
        </td>
    </tr>
    <tr>
       <td colspan="2">
           <ul class="sf_admin_actions">
           <li><input type="submit" name="save" value="save" class="sf_admin_action_save" /></li>
           </ul>
      </td>
    </tr>
    </table>
    </fieldset>
    </div>
    </form>
