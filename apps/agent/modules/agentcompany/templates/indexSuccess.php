<h1>Agentcompany List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Cvr number</th>
      <th>Ean number</th>
      <th>Address</th>
      <th>Post code</th>
      <th>Country</th>
      <th>City</th>
      <th>Contact name</th>
      <th>Email</th>
      <th>Head phone number</th>
      <th>Fax number</th>
      <th>Website</th>
      <th>Status</th>
      <th>Company form</th>
      <th>Product detail</th>
      <th>Commission period</th>
      <th>Account manager</th>
      <th>Created at</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($agent_company_list as $agent_company): ?>
    <tr>
      <td><a href="<?php echo url_for('agentcompany/edit?id='.$agent_company->getId()) ?>"><?php echo $agent_company->getId() ?></a></td>
      <td><?php echo $agent_company->getName() ?></td>
      <td><?php echo $agent_company->getCvrNumber() ?></td>
      <td><?php echo $agent_company->getEanNumber() ?></td>
      <td><?php echo $agent_company->getAddress() ?></td>
      <td><?php echo $agent_company->getPostCode() ?></td>
      <td><?php echo $agent_company->getCountryId() ?></td>
      <td><?php echo $agent_company->getCityId() ?></td>
      <td><?php echo $agent_company->getContactName() ?></td>
      <td><?php echo $agent_company->getEmail() ?></td>
      <td><?php echo $agent_company->getHeadPhoneNumber() ?></td>
      <td><?php echo $agent_company->getFaxNumber() ?></td>
      <td><?php echo $agent_company->getWebsite() ?></td>
      <td><?php echo $agent_company->getStatusId() ?></td>
      <td><?php echo $agent_company->getCompanyFormId() ?></td>
      <td><?php echo $agent_company->getProductDetail() ?></td>
      <td><?php echo $agent_company->getCommissionPeriodId() ?></td>
      <td><?php echo $agent_company->getAccountManagerId() ?></td>
      <td><?php echo $agent_company->getCreatedAt() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('agentcompany/new') ?>">New</a>
