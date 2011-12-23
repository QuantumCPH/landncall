
<div id="sf_admin_container">
	<div id="sf_admin_content">
		<div id="company-info">
		    <h1>Employee Details</h1>
			<fieldset>
				<div class="form-row">
				  <label class="required">Employee Name:</label>
				  <div class="content">
				  	<?php echo trim($employee->getLastName() . $employee->getFirstName()) ?> &nbsp; <?php echo link_to('edit info', 'employee/edit?id='.$employee->getId()) ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Company:</label>
				  <div class="content">
				  	<?php echo ($employee->getCompany()?$employee->getCompany():'N/A') ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Email:</label>
				  <div class="content">
				  	<?php echo ($employee->getEmail()?$employee->getEmail():'N/A') ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Mobile Number</label>
				  <div class="content">
				  	<?php echo $employee->getMobileNumber() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Created at:</label>
				  <div class="content">
				  	<?php echo $employee->getCreatedAt() ?>
				  </div>
				</div>
			</fieldset>
		</div>
	</div>
</div>