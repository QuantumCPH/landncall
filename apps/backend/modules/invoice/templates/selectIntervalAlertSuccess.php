<style type="text/css">
	body {
		font-family: arial;
	}
	
	form {
		padding:10px;
		margin:5px;
	}
</style>
<?php if ($sf_user->hasFlash('notice')): ?>
	<div class='notice'>
	  <?php echo $sf_user->getFlash('notice') ?>
	</div>
<?php endif; ?>
<?php echo form_tag('invoice/usageAlertReport') ?>
<h2>Reports for SMS and Email</h2>
<label> Start date/time</label>
<!--<input type="text" id='startdate' name='start_date'  />-->
<?php echo input_date_tag('start_date', date('Y-m-d'), 'rich=true') ?>
<br /><br />
<label>  End date/time</label>
<!--<input type="text" id='enddate' name='end_date' />-->
 <?php echo input_date_tag('end_date', date('Y-m-d'), 'rich=true') ?>

<br/>
<input type="submit" value="Generate Report" />
<!--</form>-->