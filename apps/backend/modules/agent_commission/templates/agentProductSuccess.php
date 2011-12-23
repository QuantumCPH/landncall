
<form method="post" action="">
    
    <div class="save-ok">
  <h2><?php  if(isset($message)){ echo  $message;  } ?></h2>
</div>

    <table>

        <tr bgcolor="#5970B2" style="color:#FFF;"><th  colspan="8" align="left" > Product For Agent: <?php
                 
             echo  $agenttdata->getName();
                
                  ?> </th></tr>
        <tr bgcolor="#5970B2" style="color:#FFF;"><th width="20%" align="left">Product Name  </th> <th  width="10%" align="left"> Action</th><th  width="10%" align="left"> Reg share value</th><th  width="10%" align="left"> Reg. share value %</th><th  width="10%" align="left"> Reg share enable</th><th  width="10%" align="left"> Extra payments share value</th><th  width="10%" align="left"> extra refill share value %</th><th  width="10%" align="left"> Extra payments share enable</th></tr>

<input type="hidden" name="agentid" value="<?php echo $agentid; ?>" />
 <?php    foreach($products as $product){?>
 
<tr><td><?php  echo $product->getName() ?></td> <td><input name="product['<?php echo  $product->getId() ?>']" value="<?php echo  $product->getId() ?>" type="checkbox"  <?php
                                                      $dc = new Criteria();
                  $dc->add(AgentProductPeer::AGENT_ID, $agentid);
                  $dc->add(AgentProductPeer::PRODUCT_ID, $product->getId() );
                  $temp = AgentProductPeer::doCount($dc);

                  ?>  <?php  if(isset($temp) && $temp>0){ ?>   checked="checked"  <?php  } ?> />
   </td>
   <td><input name="pv['<?php echo  $product->getId() ?>']"  <?php  if(isset($temp) && $temp>0){ ?>  value="<?php
   $varval="0";
   $dc = new Criteria();
                  $dc->add(AgentProductPeer::AGENT_ID, $agentid);
                  $dc->add(AgentProductPeer::PRODUCT_ID, $product->getId() );
                  
                     $tempv = AgentProductPeer::doSelectOne($dc);
                    $varval=$tempv->getRegShareValue();
                     $extravarval=$tempv->getExtraPaymentsShareValue();
 if(isset($varval) && $varval!="" ){  echo  $varval;  } ?>"   <?php  } ?> type="text"    />
</td>
<td><input name="pvp['<?php echo  $product->getId() ?>']" value="1" type="checkbox" <?php  if(isset($temp) && $temp>0){ ?>  <?php  if($tempv->getIsRegShareValuePc()==1 ){  ?>  checked="checked"  <?php  } ?>   <?php  } ?>  />
</td>
<td><input name="pve['<?php echo  $product->getId() ?>']" value="1" type="checkbox"   <?php  if(isset($temp) && $temp>0){ ?>  <?php  if($tempv->getRegShareEnable()==1 ){  ?>  checked="checked"  <?php  } ?>   <?php  } ?>   />
</td>
<td><input name="epv['<?php echo  $product->getId() ?>']"  type="text"   <?php  if(isset($temp) && $temp>0){ ?>  value="<?php
   $varval="0";
   $dc = new Criteria();
                  $dc->add(AgentProductPeer::AGENT_ID, $agentid);
                  $dc->add(AgentProductPeer::PRODUCT_ID, $product->getId() );
                   $tempv = AgentProductPeer::doSelectOne($dc);

                     $extravarval=$tempv->getExtraPaymentsShareValue();
 if(isset($extravarval) && $extravarval!="" ){  echo  $extravarval;  } ?>"   <?php  } ?>   />
</td>
<td><input name="epvp['<?php echo  $product->getId() ?>']" value="1" type="checkbox"   <?php  if(isset($temp) && $temp>0){ ?>  <?php  if($tempv->getIsExtraPaymentsShareValuePc()==1 ){  ?>  checked="checked"  <?php  } ?>   <?php  } ?>   />
</td>
<td><input name="epve['<?php echo  $product->getId() ?>']" value="1" type="checkbox"   <?php  if(isset($temp) && $temp>0){ ?>  <?php  if($tempv->getExtraPaymentsShareEnable()==1 ){  ?>  checked="checked"  <?php  } ?>   <?php  } ?>   />
</td>

</tr>
    <?php } ?><tr><td colspan="2" align="center">&nbsp; </td></tr> <tr><td colspan="8" align="center">
<input  type="submit" name="submit" value="Update" />
      </td></tr> </table>
</form>