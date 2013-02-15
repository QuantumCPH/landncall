<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<table >
    <?php
    $i = 0;
    foreach ($customers as $customer) {
        ?>
        <tr >
            <td ><?= $customer->getFirstName(); ?></td>
            <td><?= $customer->getLastName(); ?></td>
            <td><?= $customer->getMobileNumber(); ?></td>
            <td><?= Telienta::getBalance($customer); ?></td>
        </tr>

        <?php
         
    }
    ?>
</table>