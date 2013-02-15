<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table >
     <tr >
            <th >First Name</th>
            <th >Last Name</th>
            <th >Mobile Number</th>
            <th >Unique Id</th>
            <th >Created At</th>
            <th > Registration Type</th>            
            <th >Balance</th>
            <th >Agent Company Name</th >
        </tr>
    <?php
    $i = 0;
    foreach ($customers as $customer) {
        ?>
        <tr >
            <td ><?= $customer->getFirstName(); ?></td>
            <td><?= $customer->getLastName(); ?></td>
            <td><?= $customer->getMobileNumber(); ?></td>
            <td><?= $customer->getUniqueId(); ?></td>
            <td><?= $customer->getCreatedAt(); ?></td>
            <td><?= RegistrationTypePeer::retrieveByPK($customer->getRegistrationTypeId())->getDescription(); ?></td>            
            <td><?= Telienta::getBalance($customer); ?></td>
            <td><? if($customer->getReferrerId()!="")
                
                echo AgentCompanyPeer::retrieveByPK($customer->getReferrerId())->getName();
                
                ?></td>
        </tr>

        <?php
         
    }
    ?>
</table>