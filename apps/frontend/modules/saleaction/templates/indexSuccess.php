<h1>Saleaction List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($sale_action_list as $sale_action): ?>
    <tr>
      <td><a href="<?php echo url_for('saleaction/edit?id='.$sale_action->getId()) ?>"><?php echo $sale_action->getId() ?></a></td>
      <td><?php echo $sale_action->getName() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('saleaction/new') ?>">New</a>
