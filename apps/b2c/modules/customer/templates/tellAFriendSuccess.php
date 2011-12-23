<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>


<script type="text/javascript">
function countChar(str)
{
var chars = document.getElementById('chars');
chars.value = "Characters: "+str.length+"/434";
}
</script>

	  <script type="text/javascript">
	    $(document).ready(function() {
	      $("#form1").validate({
	        rules: {
	          name: {
                     required: true,
                      maxlength:50
              },// simple rule, converted to {required:true}

	          email: {// compound rule
	          required: true,
	          email: true
	        },
	        message: {
	          required: true
	        },
	        phone: {
	          number: true,
                  minlength:8
	        }
	        },
	        messages: {
	          message: "Please enter a comment."
	        }
	      });
	    });

            
	  </script>
           <style type="text/css">

            * { font-family: Verdana; font-size: 11px; line-height: 14px; align:center;}

	    .submit { margin-left: 125px; margin-top: 10px;}
	    .label { display: block; float: left; width: 90px; text-align: right; margin-right: 5px; }
	    .form-row { padding: 5px 0; clear: both; width: 700px; }
	    label.error { width: 250px; display: block; float: left; color: red; padding-left: 10px; }
	    input[type=text], text { width: 200px;  }
            input[type=textarea], textarea { width: 400px;  }
	    textarea { height: 80px; }
	  </style>


<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Refill') ) ) ?>
<?php

            if(isset ($_POST['email']) && isset ($_POST['name'])&& isset ($_POST['message'] ))
            {?>
<div class="alert_bar">
	
              <?php echo __("Din inbjudan till ").$_POST['name'].__(" har skickats"); ?>

            

</div>
<?php }

?>
<div class="left-col">
    <?php include_partial('navigation', array('selected'=>'', 'customer_id'=>$customer->getId())) ?>
	
  
<br/>
<br/><br/>&nbsp;<br/>&nbsp;

<center>
    <h1 style="font-family: Verdana; font-size: 18px; line-height: 20px; align:center; padding-top: 10px;"> <?php echo __('Tell a friend about zer0call!') ?></h1>
</center>
	  

          

<br/>
<h3><?php echo __('Tell your friends about zer0call & earn extra balance!') ?></h3>
<p style="align:justified;"><?php echo __('Tell your friends about zer0call and earn 10dkk as soon as your friends have made their first payment with zer0call! Spread the word about zer0call and let your friends know about LandNCall AB services.') ?></p>
<br/>
<h3><?php echo __('How does it work?'); ?></h3>
<p style="align:justified;"><?php echo __('You can tell your friends about zer0call in two simple ways:') ?><br />
    
<?php echo __('Fill out the fields below and click the Send Email button - your friend will receive an Email') ?></p>
<br/>
<h3><?php echo __("What you'll get from this?") ?></h3>
<p style="align:justified;"><?php echo __("As soon as your friends have made their first payment with zer0call, youll automatically receive 10dkk in your zer0call balance - so if 10 of your friends register on zer0call, youll gain 100dkk in your account. Just share the news and enjoy this great new treat from zer0call.") ?> </p>
     <div class="split-form">
      <div class="fl col">
	    <form  id="form1" method="POST" action="<?php echo url_for('customer/tellAFriend', true) ?>">
                <table>
                    <tr>
                        <td><?php echo __("Your Friend's Name") ?></td>
                        <td><?php echo __("Your Friend's Phone Number") ?></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="name" />&nbsp;
                        </td>
                        <td><input type="text" name="phone" />ex. 0701234567
                        </td>
                    </tr>
                    <tr> 
                        <td ><?php echo __("Your Friend's Email") ?></td>
                    
                        <td ><?php //echo __("Your Friend's Country") ?></td>
                    </tr>
                    
                    <tr> 
                        <td ><input type="text" name="email" /></td>
                        <td style="display:none;" ><input type="text" name="country" value="Sweden" disabled /></td>
                    </tr>
                    <tr>
                        <td  colspan="2" align="center"><?php echo __("Your Message") ?></td>

                    </tr>
                    <tr>
                        <td  colspan="2" align="center"><textarea name="message" ><?php echo __("Smartsim använder du för att ringa otroligt billigt till utlandet (du spar upp till 90%) och för att kapa priserna när du är utomlands (du spar 30-80%). Just nu kostar Smartsim 99 kr och du kan ringa för hela beloppet. Smartsim har ingen månadsavgift eller dolda avgifter.") ?> </textarea></td>

                    </tr>
                </table>
                <input type="submit" class="butonsigninsmall" name="submit" value="<?php echo __('Send Email to join Zero call') ?>">
	     
	    </form>
	  
    </div>
  </div>
          </div>
 <?php include_partial('sidebar') ?>
<script>
//$(".dashboard").innerHTML("");
$('.dashboard').html('');

//$(".alert_bar").css("display","none");
</script>