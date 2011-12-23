<form id="form1" action="<?php echo url_for('agentUser/login') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>  

    <?php echo $form->renderGlobalErrors() ?>
    <?php echo $form['username']->renderLabel() ?>
    <?php echo $form['username']->renderError() ?>
    <?php echo $form['username'] ?>

    <div class="clear"></div>

    <?php echo $form['password']->renderLabel() ?>
    <?php echo $form['password']->renderError() ?>
    <?php echo $form['password'] ?>

    <button  type="submit"><?php echo __('login') ?></button>

    <div class="clear"></div>
</form>