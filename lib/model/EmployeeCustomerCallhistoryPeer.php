<?php

class EmployeeCustomerCallhistoryPeer extends BaseEmployeeCustomerCallhistoryPeer
{
        static public function getTotalCallDuration(Employee $employee,$call,PropelPDO $con = null)
    {
       if($con === null) {
          $con = Propel::getConnection(EmployeeCustomerCallhistoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
       }

       $stmt = $con->prepare('SELECT sec_to_time(' . EmployeeCustomerCallhistoryPeer::CHARGED_QUANTITY . ') FROM ' . EmployeeCustomerCallhistoryPeer::TABLE_NAME . ' Where '. EmployeeCustomerCallhistoryPeer::PARENT_ID .'='.$employee->getId().' and '. EmployeeCustomerCallhistoryPeer::ID .'='.$call->getId().' and '. EmployeeCustomerCallhistoryPeer::PARENT_TABLE.'= "employee"');
       $stmt->execute();
       return $stmt->fetchColumn();
    }
   
    static public function getCallDuration($call_id,PropelPDO $con = null)
    {
       if($con === null) {
          $con = Propel::getConnection(EmployeeCustomerCallhistoryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
       }

       $stmt = $con->prepare('SELECT sec_to_time(' . EmployeeCustomerCallhistoryPeer::CHARGED_QUANTITY . ') FROM ' . EmployeeCustomerCallhistoryPeer::TABLE_NAME . ' Where '. EmployeeCustomerCallhistoryPeer::ID .'='.$call_id);
       $stmt->execute();
       return $stmt->fetchColumn();
    }
}
