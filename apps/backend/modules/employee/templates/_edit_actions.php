<ul class="sf_admin_actions">
  <li style="margin-right:25px;"><?php echo button_to(__('add new employee products'), 'employee_product/edit?employee_id='.$employee->getId(), array (
  'class' => 'sf_admin_action_list',
)) ?></li>
  <li><?php echo button_to(__('list'), 'employee/list?id='.$employee->getId(), array (
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
