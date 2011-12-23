<h1>Supportissue List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($support_issue_list as $support_issue): ?>
    <tr>
      <td><a href="<?php echo url_for('supportissue/edit?id='.$support_issue->getId()) ?>"><?php echo $support_issue->getId() ?></a></td>
      <td><?php echo $support_issue->getName() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('supportissue/new') ?>">New</a>
