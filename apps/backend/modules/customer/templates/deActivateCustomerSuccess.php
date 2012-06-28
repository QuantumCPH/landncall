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
        <tr><td>Customer Id:</td><td><input type="text" name="customer_id" /></td></tr>
          <tr><td>Make Unique Id Available Again:</td> <td><input type="checkbox" value="1" name="uniqueId" /></td></tr>
            <tr><td></td><td> <input type="submit" value="De-Activate"></td></tr>




    </table>
    
</form>

<br/>
<?php echo $response_text ?>
</div>