<div id="sf_admin_container">
 <h1>The News & Updates set for Agents </h1>



<div id="sf_admin_content">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="sf_admin_list">

    <thead><tr>
    <td>ID</td>
	<td>Active?</td>
    <td>Starting Date</td>
    <td>Heading</td>
    <td>Message</td>
    <td> </td>
    
    </tr>
    </thead><tbody>
<?php
foreach($messages as $message)
{?>
    <tr>
        <td><?php echo $message->getId() ?></td>
		<td><?php 
		$currentDate = date('Y-m-d');
		if($currentDate>=$message->getStartingDate() ){
			echo "Yes";
		}
		?> </td>
        <td><?php echo $message->getStartingDate() ?></td>
        <td><?php echo $message->getHeading() ?></td>
        <td><?php echo $message->getMessage() ?></td>
        <td> <a href='<?php echo url_for('agent_company/newsEdit')?>?id=<?php echo $message->getId()?>'><img src="/sf/sf_admin/images/edit_icon.png" title="edit" alt="edit"></a> &nbsp;
        <a href='<?php echo url_for('agent_company/newsDelete')?>?id=<?php echo $message->getId()?>'><img src="/sf/sf_admin/images/delete_icon.png" title="delete" alt="delete"></a> </td>
    </tr>

<?php
}
?>
    </tbody>
</table>
</div>
</div>
