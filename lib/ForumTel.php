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
        $URL = "https://forumtel.com/ExternalApi/Rest/ProvisionServices.ashx";   //  old url
     //     $URL = "https://api.forum-mobile.com/ExternalApi/Rest/ProvisionServices.ashx"; // new url

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
        
        if(strpos($data, "HTTP 404")!==false){
          emailLib::sendErrorInForumTel("Error in Register on ForumTel", "Error in register on forumTel for MSISDN No $msisdn , request id is $transactionid , tarif name $tarif_name. <br/> Following error occurred.<br /> <br />$data");
        }else{
            
        }
        
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
        $URL = "https://forumtel.com/ExternalApi/Rest/ProvisionServices.ashx";   // old url
//           $URL = "https://api.forum-mobile.com/ExternalApi/Rest/ProvisionServices.ashx";    //  new url

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
        $URL = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";    // old URL
    //        $URL = "https://api.forum-mobile.com/ExternalApi/Rest/BillingServices.ashx";   // new url

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
        
        if(strpos($data, "HTTP 404")!==false){
          emailLib::sendErrorInForumTel("Error in Recharge on ForumTel", "Error in recharge on forumTel for MSISDN No $msisdn , request id is $transactionid , amount $amount. <br/> Following error occurred.<br /> <br />$data");
        }else{
            
        }
        return true;
    }

    public static function getBalanceForumtel($customer) {

        $customerid = $customer;
        
        
        $max_retries = 10;
        $retry_count = 0;
        
        $tc = new Criteria();
        $tc->add(UsNumberPeer::CUSTOMER_ID, $customerid);
        $usnumber = UsNumberPeer::doSelectOne($tc);
        $transactionid= "466".mt_rand(100000000,999999999);

        $username = "Zapna";
        $password = "ZUkATradafEfA4reYeWr";
        $msisdn = $usnumber->getMsisdn();
        $iccid = $usnumber->getIccid();
      

          //https://api.forum-mobile.com/ExternalApi/
       // $url = "https://api.forum-mobile.com/ExternalApi/Rest/BillingServices.ashx";  // new url
          $url = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";   //  old url 
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
        
     while ($retry_count < $max_retries) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $data = curl_exec($ch);
        $output = $data;
        
       // var_dump($data); die;
        if(isset ($data) && strpos($data, "HTTP 404")===false){
            $data = substr($data, 215);
            $xml_obj = new SimpleXMLElement($data);
    
            $data = $xml_obj->balance[0];
            
            $ftr = new ForumTelRequests();
            $ftr->setRequestid($transactionid);
            $ftr->setResponse($output);
            $ftr->setRequestType('get balance');
            $ftr->setIccid($iccid);
            $ftr->setMsisdn($msisdn);
            $ftr->save(); 
            return $data;
        }else{
          //  $output = $data;
            
            $ftr = new ForumTelRequests();
            $ftr->setRequestid($transactionid);
            $ftr->setResponse($output);
            $ftr->setRequestType('get balance');
            $ftr->setIccid($iccid);
            $ftr->setMsisdn($msisdn);
            $ftr->save();             
        }
        
        sleep(0.5);
        $retry_count++;
      }
      if(strpos($data, "HTTP 404")!==false && $retry_count==$max_retries){
       emailLib::sendErrorInForumTel("Error in fetching balance", "Error in fetching balance for MSISDN No $msisdn and request id is $transactionid. Error is Even After Max Retries " . $max_retries . "  <br/> Following error occurred.<br /> <br />$data");
      }
      
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
            $url = "https://forumtel.com/ExternalApi/Rest/ProvisionServices.ashx";   // old url
     //       $url = "https://api.forum-mobile.com/ExternalApi/Rest/ProvisionServices.ashx";  //new url
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

        if(strpos($data, "HTTP 404")!==false){
         emailLib::sendErrorInForumTel("Error in get us mobile number", "Error in get us mobile number for MSISDN No $msisdn and request id is $transactionid. <br/> Following error occurred.<br /> <br />$data");
           
        }else{
         $data = substr($data, 215);
         $xml_obj = new SimpleXMLElement($data);

//die;$data = $xml_obj->balance[0]->attributes()->amount;
         $test = $xml_obj->xpath('usa-mdn');
         $usnumberget=$test[0];
         $usnumber->setUsMobileNumber($usnumberget);

         $usnumber->save();   
        }
        
         
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
             $url = "https://forumtel.com/ExternalApi/Rest/BillingServices.ashx";       ///    old url
        //     $url = "https://api.forum-mobile.com/ExternalApi/Rest/BillingServices.ashx";   //// new url
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