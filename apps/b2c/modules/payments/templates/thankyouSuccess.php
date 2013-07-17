<?php use_helper('I18N') ?>

<table width="100%">   <tr> <td align="center"><?php include_partial('customer/dashboard_header', array('customer'=> null, 'section'=>__('Betalningsbekräftelse')) ) ?> </td></tr>
<tr><td align="center">

	<div align="center" style="margin:20px auto">
	<?php
		echo "<p>";
                echo __("SmartSim tackar f&ouml;r din best&auml;llning.Du kommer strax att f&aring; en leveransbekr&auml;ftelse");
                echo "</p>";
                echo "<p>";
		echo __("skickat till den epostadress som du angav vid registreringen.Tveka inte att ta kontakt<br/> med oss p&aring; <a href='mailto:support@smartsim.se'>support@smartsim.se</a> om du har n&aring;gra fr&aring;gor");
                echo "</p>";
               
	?>
	.

  </div> <!-- end left-col -->
 
  </td></tr>
  </table>