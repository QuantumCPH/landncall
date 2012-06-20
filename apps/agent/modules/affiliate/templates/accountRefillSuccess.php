<script type="text/javascript">

	function checkForm()
	{
            
		var objForm = document.getElementById("refill");
		var valid = true;

		var amounts = document.getElementById("amount").value;
               // alert(amounts);
                var orderids = document.getElementById("orderid").value;
                var accepturlstr = "http://landncall.zerocall.com/agent.php/affiliate/thankyou?accept=yes&subscriptionid=&orderid="+orderids+"&amount="+amounts;
                document.getElementById("accepturl").value = accepturlstr;
                
		if(isNaN(objForm.amount.value) || objForm.amount.value < <?php echo 0//$amount ?>)
		{
			alert("<?php echo __('amount error') ?>!");
			objForm.amount.focus();

			valid = false;
		}

		if(objForm.cardno.value.length < 16)
		{
			$('#cardno_error').show();

			if (valid) //still not declarted as invaid
				objForm.cardno.focus();
			valid = false;
		}
		else {
			$('#cardno_error').hide();
		}

		if(isNaN(objForm.cvc.value) || objForm.cvc.value.length < 3 || objForm.cardno.cvc.length > 3)
		{
			$('#cvc_error').show();

			if (valid)
				objForm.cvc.focus();
			valid = false;
		}
		else {
			$('#cvc_error').hide();
		}

		return valid;
	}

	function toggleAutoRefill()
	{
		document.getElementById('user_attr_2').disabled = ! document.getElementById('user_attr_1').checked;
		document.getElementById('user_attr_3').disabled = ! document.getElementById('user_attr_1').checked;

	}

	$('#user_attr_3').blur(function(){
		if ( this.value<0 || this.value>400 || isNaN(this.value) )
			this.value = 0;
	});

	$(document).ready(function(){
		$('#cardno_error, #cvc_error').hide();

		toggleAutoRefill();
	});




</script>
<div id="sf_admin_container"><h1><?php echo __('Account Refill') ?></h1></div>

  <div class="borderDiv">
<form action="https://payment.architrade.com/paymentweb/start.action"  method="post" id="refill" onsubmit="return checkForm()">
  
    <table>
       
        <tr>
            <td>
                <label for="amount">Select refill amount</label>
            </td>
            <td>
                <select name="amount" id="amount">
                    <option value="50000">500</option>
                    <option value="100000">1000</option>
                    <option value="150000">1500</option>
              </select>
            </td>
        </tr>
<!--        <tr>
            <td>
                 <label for="cardtype">Card Type</label>
            </td>
            <td>
                <select name="cardtype" id="cardtype">
                <option value="2">Visa/Dankort</option>
                <option value="18">Visa</option>
              </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="cardno">Credit Card no. (16 digit valid number) </label>
            </td>
            <td>
                <input autocomplete="off" type="text" name="cardno" id="cardno" />
            </td>
        
        </tr>
        <tr>
            <td>
                 <label for="expmonth">Expiry Month</label>
            </td>
            <td>
                 <select name="expmonth" id="expmonth">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
              </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="expyear">Expiry Year</label>
            </td>
            <td>
                 <select name="expyear" id="expyear">
                    <option value="21">21</option>
                    <option value="20">20</option>
                    <option value="19">19</option>
                    <option value="18">18</option>
                    <option value="17">17</option>
                    <option value="16">16</option>
                    <option value="15">15</option>
                    <option value="14">14</option>
                    <option value="13">13</option>
                    <option value="12">12</option>
                    <option value="11">11</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="cvc">CVC</label>
            </td>
            <td>
                <input autocomplete="off" type="text" name="cvc" id="cvc" />
            </td>
        </tr>-->
        <tr>
            <td >
                
            </td>
            <td>
                <input type="hidden" name="merchant" value="90049676" />
		<input type="hidden" name="currency" value="752" />
		<input type="hidden" name="orderid" id="orderid" value="<?php echo $agent_order->getAgentOrderId() ?>" />
	 
<input type="hidden" name="test" value="yes" />
		<input type="hidden" name="calcfee" value="yes" />
		<input type="hidden" name="account" value="YTIP" />
		<input type="hidden" name="status" value="" />
                 <input type="hidden" name="lang" value="sv" />   
              <input type="hidden" name="test" value="yes" />
		<input type="hidden" name="cancelurl" value="http://landncall.zerocall.com/agent.php/affiliate/thankyou/?accept=cancel" />
		<input type="hidden" name="callbackurl" value="http://landncall.zerocall.com/b2c.php/pScripts/accountRefill" />
		<input type="hidden" name="accepturl" id="accepturl"  value="http://landncall.zerocall.com/agent.php/affiliate/thankyou?accept=yes&subscriptionid=&orderid=<?php echo $agent_order->getAgentOrderId(); ?>&amount=50000">

                
                <input type="submit" value="Recharge" />
            </td>
        </tr>
    </table>
 
</form>
  </div>