<center>
<h2>Login to Landncall Admin</h2>

		<?php if($sf_user->hasFlash('message')): ?>
			<div class="message">
				<?php echo $sf_user->getFlash('message'); ?>
			</div>
		<?php endif; ?>
                
                    <?php include_partial('loginform', array('form' => $loginForm)) ?>
                
               

</center>