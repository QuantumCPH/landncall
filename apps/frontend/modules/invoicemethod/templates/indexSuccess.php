<h1>Invoicemethod List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($invoice_method_list as $invoice_method): ?>
    <tr>
      <td><a href="<?php echo url_for('invoicemethod/edit?id='.$invoice_method->getId()) ?>"><?php echo $invoice_method->getId() ?></a></td>
      <td><?php echo $invoice_method->getName() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('invoicemethod/new') ?>">New</a>
