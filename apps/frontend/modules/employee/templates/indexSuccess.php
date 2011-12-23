<h1>Employee List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Company</th>
      <th>First name</th>
      <th>Last name</th>
      <th>Email</th>
      <th>Assigned serial</th>
      <th>Mobile model</th>
      <th>Created at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($employee_list as $employee): ?>
    <tr>
      <td><a href="<?php echo url_for('employee/edit?id='.$employee->getId()) ?>"><?php echo $employee->getId() ?></a></td>
      <td><?php echo $employee->getCompanyId() ?></td>
      <td><?php echo $employee->getFirstName() ?></td>
      <td><?php echo $employee->getLastName() ?></td>
      <td><?php echo $employee->getEmail() ?></td>
      <td><?php echo $employee->getAssignedSerial() ?></td>
      <td><?php echo $employee->getMobileModel() ?></td>
      <td><?php echo $employee->getCreatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('employee/new') ?>">New</a>
