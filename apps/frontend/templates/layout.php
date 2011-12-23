<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body>
  	<table>
	  	<thead>
	  		<tr>
	  			<th>&nbsp;</th>
	  			<th>&nbsp;</th>	  			
	  		</tr>
	  	</thead>
	  	<tbody>
	  		<tr>
	  			<td>
	  				<ul>
	  					<li><?php echo link_to('customer types', 'customertype/index') ?></li>
	  					<li><?php echo link_to('invoice methods', 'invoicemethod/index') ?></li>
	  					<li><?php echo link_to('sale actions', 'saleaction/index') ?></li>
	  					<li><?php echo link_to('support issues', 'supportissue/index') ?></li>
	  					<li><?php echo link_to('companies list', 'company/index') ?></li>
	  					<li><?php echo link_to('employee lists', 'employee/index') ?></li>
	  				</ul>
	  			</td>
	  			<td>
	  				<?php echo $sf_content ?>
	  			</td>
	  		</tr>
	  	</tbody>
  	</table>
    
  </body>
</html>
