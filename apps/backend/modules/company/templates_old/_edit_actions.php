<ul class="sf_admin_actions">
 <!-- <li style='margin-right: 50px;'><?php //echo button_to(__('add product orders'), 'product_order/edit?company_id='.$company->getId(), array (
 // 'class' => 'sf_admin_action_list',
//)) ?></li>-->    
  <li><?php echo button_to(__('list'), 'company/list?id='.$company->getId(), array (
  'class' => 'sf_admin_action_list',
)) ?></li>
  <li><?php echo submit_tag(__('save'), array (
  'name' => 'save',
  'class' => 'sf_admin_action_save',
)) ?></li>
  <li><?php echo submit_tag(__('save and add'), array (
  'name' => 'save_and_add',
  'class' => 'sf_admin_action_save_and_add',
)) ?></li>
</ul>
