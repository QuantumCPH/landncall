<h1>Company List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Cvr number</th>
      <th>Address</th>
      <th>Post code</th>
      <th>Country</th>
      <th>City</th>
      <th>Employee</th>
      <th>Head phone number</th>
      <th>Fax number</th>
      <th>Invoice method</th>
      <th>Customer type</th>
      <th>Created at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($company_list as $company): ?>
    <tr>
      <td><a href="<?php echo url_for('company/edit?id='.$company->getId()) ?>"><?php echo $company->getId() ?></a></td>
      <td><?php echo $company->getName() ?></td>
      <td><?php echo $company->getCvrNumber() ?></td>
      <td><?php echo $company->getAddress() ?></td>
      <td><?php echo $company->getPostCode() ?></td>
      <td><?php echo $company->getCountryId() ?></td>
      <td><?php echo $company->getCityId() ?></td>
      <td><?php echo $company->getEmployeeId() ?></td>
      <td><?php echo $company->getHeadPhoneNumber() ?></td>
      <td><?php echo $company->getFaxNumber() ?></td>
      <td><?php echo $company->getInvoiceMethodId() ?></td>
      <td><?php echo $company->getCustomerTypeId() ?></td>
      <td><?php echo $company->getCreatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('company/new') ?>">New</a>
