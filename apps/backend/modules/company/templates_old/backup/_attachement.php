<?php if($company->getFilePath()): ?>
	<a href="<?php echo public_path('/uploads/'.$company->getFilePath()) ?>" target="_blank">Download attachement</a>
<?php else: ?>
	none
<?php endif; ?>