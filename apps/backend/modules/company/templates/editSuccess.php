<?php use_helper('Object', 'Validation', 'ObjectAdmin', 'I18N', 'Date') ?>

<?php use_stylesheet('/sf/sf_admin/css/main') ?>

<div id="sf_admin_container">

<h1><?php echo __('Create/Edit Company', 
array()) ?></h1>

<div id="sf_admin_header">
<?php include_partial('company/edit_header', array('company' => $company)) ?>
</div>

<div id="sf_admin_content">
<?php include_partial('company/edit_messages', array('company' => $company, 'labels' => $labels)) ?>
<?php include_partial('company/edit_form', array('company' => $company, 'labels' => $labels)) ?>
</div>

<div id="sf_admin_footer">
<?php include_partial('company/edit_footer', array('company' => $company)) ?>
</div>

</div>
