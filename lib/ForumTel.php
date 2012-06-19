<?php

class ForumTel {

    public static function registerForumtel($customerid) {


        $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
        $usnumber = UsNumberPeer::doSelectOne($tc);
        $transactionid= "466".mt_rand(100000000,999999999);
        $username = "Zapna";
        $password = "ZUkATradafEfA4reYeWr";
        $msisdn = $usnumber->getMsisdn();
        $iccid = $usnumber->getIccid();
        $tarif_name = "PayAsYouGo-Sub";
        $xml_data = '<activate-account trid="'.$transactionid.'">
<authentication>
<username>' . $username . '</username>
<password>' . $password . '</password>
</authentication>
<msisdn>' . $msisdn . '</msisdn>
<iccid>' . $iccid . '</iccid>
<tariff-name>' . $tarif_name . '</tariff-name>
<balance-alert-level>0</balance-alert-level>
</activate-account>';

//https://api.forum-mobile.com/ExternalApi/
     //   $URL = "https://forumtel.com/ExternalApi/Rest/ProvisionServices.ashx";   old url
          $URL = "https://api.forum-mobile.com/ExternalApi/Rest/ProvisionServices.ashx";

        $ch = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     echo  $output = curl_exec($ch);
        curl_close($ch);



                    $ftr = new ForumTelRequests();
                    $ftr->setRequestid($transactionid);
                    $ftr->setResponse($output);
                    $ftr->setRequestType('registration');
                    $ftr->setIccid($iccid);
                    $ftr->setMsisdn($msisdn);
                    $ftr->save();







        return true;
    }

    public static function suspendForumtel($customerid) {

        $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
        $usnumber = UsNumberPeer::doSelectOne($tc);
           $transactionid= "466".mt_rand(100000000,999999999);

        $username = "Zapna";
        $password = "ZUkATradafEfA4reYeWr";
        $msisdn = $usnumber->getMsisdn();
        $iccid = $usnumber->getIccid();

        $xml_data = '<suspend-account trid="'.$transactionid.'">
<authentication>
<username>' . $username . '</username>
<password>' . $password . '</password>
</authentication>
<msisdn>' . $msisdn . '</msisdn>
<iccid>' . $iccid . '</iccid>
</suspend-account>';


        //https://api.forum-mobile.com/ExternalApi/
   //     $URL = "https://forumtel.com/ExternalApi/Rest/ProvisionServices.ashx";   old url
           $URL = "https://api.forum-mobile.com/ExternalApi/Rest/ProvisionServices.ashx";

        $ch = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        echo $output = curl_exec($ch);
        curl_close($ch);


                    $ftr = new ForumTelRequests();
                    $ftr->setRequestid($transactionid);
                    $ftr->setResponse($output);
                    $ftr->setRequestType('suspend');
                    $ftr->setIccid($iccid);
                    $ftr->setMsisdn($msisdn);
                    $ftr->save();


        return true;
    }

    public static function rechargeForumtel($customerid, $amount) {

        $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
        $usnumber = UsNumberPeer::doSelectOne($tc);
        $transactionid= "466".mt_rand(100000000,999999999);

        $username = "Zapna";
        $password = "ZUkATradafEfA4reYeWr";
        $msisdn = $usnumber->getMsisdn();
        $iccid = $usnumber->getIccid();
        $amount = $amount;

        $xml_data = '<top-up-subscriber trid="'.$transactionid.'">
<authentication>
<username>' . $username . '</username>
<password>' . $password . '</password>
</authentication>
<msisdn>' . $msisdn . '</msisdn>
<iccid>' . $iccid . '</iccid>
<amount>' . $amount . '</amount>
</top-up-subscriber>';

  //https://api.forum-mobile.com/ExternalApi/
   //     $URL = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";   old URL
            $URL = "https://api.forum-mobile.com/ExternalApi/Rest/BillingServices.ashx";

        $ch = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        echo $output = curl_exec($ch);
        curl_close($ch);

                    $ftr = new ForumTelRequests();
                    $ftr->setRequestid($transactionid);
                    $ftr->setResponse($output);
                    $ftr->setRequestType('recharge');
                    $ftr->setIccid($iccid);
                    $ftr->setMsisdn($msisdn);
                    $ftr->save();

        return true;
    }

    public static function getBalanceForumtel($customer) {

        $customerid = $customer;

        $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
        $usnumber = UsNumberPeer::doSelectOne($tc);
        $transactionid= "466".mt_rand(100000000,999999999);

        $username = "Zapna";
        $password = "ZUkATradafEfA4reYeWr";
        $msisdn = $usnumber->getMsisdn();
        $iccid = $usnumber->getIccid();


          //https://api.forum-mobile.com/ExternalApi/
        $url = "https://api.forum-mobile.com/ExternalApi/Rest/BillingServices.ashx";
       ///     $url = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";   old url
        $post_string = '<get-subscriber-balance trid="'.$transactionid.'">
        <authentication>
        <username>' . $username . '</username>
        <password>' . $password . '</password>
        </authentication>
        <msisdn>' . $msisdn . '</msisdn>
         <iccid>' . $iccid . '</iccid>
        </get-subscriber-balance>';




        $header = array();
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: " . strlen($post_string);
        $header[] = "Connection: close";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $data = curl_exec($ch);
        $output=$data;
        $data = substr($data, 215);
        var_dump($data);
        if(isset ($data) && $data!=""){
            $xml_obj = new SimpleXMLElement($data);
     //var_dump($xml_obj);
    //echo "<hr/>";
    //die;$data = $xml_obj->balance[0]->attributes()->amount;
            $data = $xml_obj->balance[0];
            
            $ftr = new ForumTelRequests();
            $ftr->setRequestid($transactionid);
            $ftr->setResponse($output);
            $ftr->setRequestType('get balance');
            $ftr->setIccid($iccid);
            $ftr->setMsisdn($msisdn);
            $ftr->save(); 
        }else{
            emailLib::sendErrorInForumTel("Error in fetching balance", "Error in fetching balance for customer $customerid .");
            return false;
        }           
       return $data;
    }
//////////////////////////////////////////////////////////////////////
     public static function getUsMobileNumber($customer) {

        $customerid = $customer;

            $tc = new Criteria();
            $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
            $usnumber = UsNumberPeer::doSelectOne($tc);
                 $transactionid= "466".mt_rand(100000000,999999999);
            $username = "Zapna";
            $password = "ZUkATradafEfA4reYeWr";
            $msisdn = $usnumber->getMsisdn();
            $iccid = $usnumber->getIccid();
      //https://api.forum-mobile.com/ExternalApi/
     //       $url = "https://forumtel.com/ExternalApi/Rest/ProvisionServices.ashx";   old url
                $url = "https://api.forum-mobile.com/ExternalApi/Rest/ProvisionServices.ashx";
            $post_string = '<get-usa-mdn trid="'.$transactionid.'">
            <authentication>
            <username>' . $username . '</username>
            <password>' . $password . '</password>
            </authentication>
            <msisdn>' . $msisdn . '</msisdn>
            <iccid>' . $iccid . '</iccid>
            </get-usa-mdn>';

        $header = array();
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: " . strlen($post_string);
        $header[] = "Connection: close";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HEADER, true);

     echo  $data = curl_exec($ch);






          $ftr = new ForumTelRequests();
                    $ftr->setRequestid($transactionid);
                    $ftr->setResponse($data);
                    $ftr->setRequestType('get us mobile number');
                    $ftr->setIccid($iccid);
                    $ftr->setMsisdn($msisdn);
                    $ftr->save();

  $data = substr($data, 215);
        $xml_obj = new SimpleXMLElement($data);

//die;$data = $xml_obj->balance[0]->attributes()->amount;
        $test = $xml_obj->xpath('usa-mdn');
         $usnumberget=$test[0];
$usnumber->setUsMobileNumber($usnumberget);

$usnumber->save();
         
    }
/////////////////////////////////////////////////////////////////
      public static function reSetBalance($customer) {

        $customerid = $customer;

            $tc = new Criteria();
            $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
            $usnumber = UsNumberPeer::doSelectOne($tc);
                 $transactionid= "466".mt_rand(100000000,999999999);
            $username = "Zapna";
            $password = "ZUkATradafEfA4reYeWr";
            $msisdn = $usnumber->getMsisdn();
            $iccid = $usnumber->getIccid();

              //https://api.forum-mobile.com/ExternalApi/
  //          $url = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";   old url
             $url = "https://api.forum-mobile.com/ExternalApi/Rest/BillingServices.ashx";
            $post_string = '<reset-subscriber-balance trid="'.$transactionid.'">
            <authentication>
            <username>' . $username . '</username>
            <password>' . $password . '</password>
            </authentication>
            <msisdn>' . $msisdn . '</msisdn>
            <iccid>' . $iccid . '</iccid>
            </reset-subscriber-balance>';

        $header = array();
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: " . strlen($post_string);
        $header[] = "Connection: close";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $data = curl_exec($ch);

                        $ftr = new ForumTelRequests();
                        $ftr->setRequestid($transactionid);
                        $ftr->setResponse($data);
                        $ftr->setRequestType('Reset balance');
                        $ftr->setIccid($iccid);
                        $ftr->setMsisdn($msisdn);
                        $ftr->save();
                         curl_close($ch);

    }
}

?>