<div class="form-row">
  <?php echo label_for('company[country_id]', 'country', '') ?>
  <div class="content<?php if ($sf_request->hasError('company{country_id}')): ?> form-error<?php endif; ?>">
  <?php if ($sf_request->hasError('company{country_id}')): ?>
    <?php echo form_error('company{country_id}', array('class' => 'form-error-msg')) ?>
  <?php endif; ?>

   <?php echo select_tag('company[country_id]', options_for_select($countries_list, $country_id), 
  								array('onchange'=> remote_function(array(
							    			'update'  => 'countrycity',
							    			'url'     => 'company/countrycity',
  											'with' => "'country_id=' + this.options[this.selectedIndex].value"
							  			))
									)) ?>
    </div>
</div>

<?php if($cities_list): ?>
	<div class="form-row">
	  <?php echo label_for('company[city_id]', 'city', '') ?>
	  <div class="content<?php if ($sf_request->hasError('company{city_id}')): ?> form-error<?php endif; ?>">
	  <?php if ($sf_request->hasError('company{city_id}')): ?>
	    <?php echo form_error('company{city_id}', array('class' => 'form-error-msg')) ?>
	  <?php endif; ?>
	
	  <?php echo select_tag('company[city_id]', options_for_select($cities_list, null)) ?>
	    </div>
	</div>
<?php endif; ?>