<h1>Customertype List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($customer_type_list as $customer_type): ?>
    <tr>
      <td><a href="<?php echo url_for('customertype/edit?id='.$customer_type->getId()) ?>"><?php echo $customer_type->getId() ?></a></td>
      <td><?php echo $customer_type->getName() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('customertype/new') ?>">New</a>
