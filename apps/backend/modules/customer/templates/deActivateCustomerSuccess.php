<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?><div id="sf_admin_container">
<h1>De-Activate Customer</h1>
<br>
<form action="" method="get">
    <label>Customer Id:</label>
    <input type="text" name="customer_id" /><br/><br/>
  <label>Make Unique Id Available Again:</label>
  <input type="checkbox" value="1" name="uniqueId" /><br/><br/>
    <label>&nbsp;&nbsp;</label> <input type="submit" value="De-Activate">
</form>

<br/>
<?php echo $response_text ?>
</div>