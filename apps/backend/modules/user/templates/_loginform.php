<?php //if($request->getMethod() != 'post') $is_postback = true; ?>
<div style="">
<form id="form1" action="<?php echo url_for('user/login') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>  

    <?php echo $form->renderGlobalErrors() ?>

    <table cellspacing="5px" >
        
        
        <tr>
            <td>
                 <?php echo $form['email']->renderLabel() ?>
            </td>
            <td >
                
            <?php echo $form['email'] ?>
            </td>

            <td><?php
			if(($sf_request->getMethod()=='POST'))
	            	echo $form['email']->renderError() ?>
            </td>
        </tr>                             
        <tr>
            <td>
                  <?php echo $form['password']->renderLabel() ?>
            </td>
            <td>
               
            <?php echo $form['password'] ?>
            <?php // echo input_hidden_tag('referer', $_SERVER["HTTP_REFERER"])  ?>
            </td>
            <td>
            <?php
            if(($sf_request->getMethod()=='POST'))
            	echo $form['password']->renderError() ?>
                </td>
        </tr>
        <tr>
            <td>

            </td>
            <td>
                <button  type="submit">login</button>
            </td>
        </tr>
    </table>                  
</form>
</div>
<div class="clear"></div>