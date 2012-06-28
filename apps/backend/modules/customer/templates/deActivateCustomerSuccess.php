<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?><div id="sf_admin_container">
<h1>De-Activate Customer</h1>
<br>
<form action="" method="get">

    <table border="0">
        <tr><td style="border:none !important;">Customer Id:</td><td style="border:none !important;"><input type="text" name="customer_id" /></td></tr>
          <tr><td style="border:none !important;">Make Unique Id Available Again:</td> <td style="border:none !important;"><input type="checkbox" value="1" name="uniqueId" /></td></tr>
            <tr><td style="border:none !important;"></td><td style="border:none !important;"> <input type="submit" value="De-Activate"></td></tr>




    </table>
    
</form>

<br/>
<?php echo $response_text ?>
</div>