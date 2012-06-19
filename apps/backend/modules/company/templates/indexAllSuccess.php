<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

<div  id="sf_admin_container">
    <h1><?php echo __('Edit Credit Limit of Companies') ?></h1><br />

<?php if ($sf_user->hasFlash('message')): ?>
    <br />
    <div class="save-ok">
        <h2><?php echo __($sf_user->getFlash('message')) ?></h2>
    </div>
    <br/>
<?php endif; ?>

<form id="sf_admin_form" name="sf_admin_edit_form" method="post" enctype="multipart/form-data" action="editCreditLimit">
    <fieldset>
        <div class="form-row">
            <label for="filters_company_name"><?php echo __('Credit Limit:') ?></label>
            <div class="content"><input type="text" name="creditlimit" class="required" ></div>
        </div>
    </fieldset>
    <br>
<table width="100%" cellspacing="0" cellpadding="2" class="sf_admin_list">
        <tr class="headings">
      <th><input type="checkbox" id="selectall"  checked="checked"/></th>
      <th><?php echo __('Company Name') ?></th>
      <th><?php echo __('Vat no') ?></th>
         <th><?php echo __('Credit Limit') ?></th>
      <th><?php echo __('Contact name') ?></th>
      <th><?php echo __('Mobile Number') ?></th>
    </tr>
<?php
$incrment=1;
foreach ($companies as $company):
    if($incrment%2==0){
        $class= 'class="even"';
    }else{
        $class= 'class="odd"';
    }
        $incrment++;
?>
    <tr <?php echo $class; ?>>
        <td><input type="checkbox" name="company_id[]" class="case"  value="<?PHP echo $company->getId();?>" checked="checked"></td>
        <td><?php echo $company->getName(); ?></td>
        <td><?php echo $company->getVatNo(); ?></td>
          <td><?php echo $company->getCreditLimit(); ?></td>
        <td><?php echo $company->getContactName();?></td>
        <td><?php echo $company->getHeadPhoneNumber(); ?></td>
    </tr>
 <?php endforeach; ?>
</table>
<div id="sf_admin_container" style="width:100%">
    <ul class="sf_admin_actions">
        <li>
            <input type="hidden" value="1" name="all_company">
            <input type="submit" name="save" value="<?php echo __('Save Changings') ?>" class="sf_admin_action_save" />
        </li>
    </ul>
</div>
</form>
</div>
