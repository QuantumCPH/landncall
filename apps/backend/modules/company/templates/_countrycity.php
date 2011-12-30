<?php //include the prototype.js otherwise the following js code will not work ?>

>
<div class="grid_5 omega">
    <?php echo $form['city_id']->renderLabel() ?>
            <?php echo $form['city_id']->renderError() ?>
            <?php echo $form['city_id'] ?>
</div>
<div class="grid_5 alpha">
    <?php echo $form['country_id']->renderLabel() ?>
            <?php echo $form['country_id']->renderError() ?>
            <?php echo $form['country_id']
                            ?>
</div