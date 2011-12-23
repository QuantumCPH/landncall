<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
  <div class="left-col">
    <div class="split-form-sign-up">
      <div class="step-details"> <strong>Bliv kunde <span class="inactive">- Step 1: Register </span><span class="active">- Step 2: Payment</span></strong> </div>
      <div class="fl col">
        <form>
          <ul>
            <li>
              <label>k�b ekstra taletid *:</label>
              <input class="radbx" type="radio" checked="checked" value="" />
              <span class="fl">100,00 kr</span>
              <input type="radio" class="radbx" value="" />
              <span class="fl">200,00 kr</span> </li>
            <li>
              <label>Payment details:</label>
            </li>
            <li>
              <label>Select product name<br />
              extra refill amount</label>
              <label class="fr ac">100,00<br />
              100,00</label>
            </li>
            <li>
              <label>VAT (25%)<br />
              Total amount</label>
              <label class="fr ac">40,00<br />
              200,00</label>
            </li>
            <li>
              <label>Please enter your credit informations below:</label>
            </li>
            <li>
              <label>Total amount</label>
              <input type="text" />
            </li>
            
            <li>
              <label>Enter credit card no.</label>
              <input />
            </li>
            <li>
              <label>Enter expire month:</label>
              <select>
                <option>&nbsp;</option>
              </select>
            </li>
            <li>
              <label>Enter expire year:</label>
              <select>
                <option>&nbsp;</option>
              </select>
            </li>
            <li>
              <label>Card Security Code<br />
              (CSC)</label>
              <input />
            </li>
          </ul>
        </form>
      </div>
      <div class="fr col">
        <ul>
          <li class="fr buttonplacement2">
              </li>
        </ul>  

          <!--   <input  style="cursor: pointer;"  class="loginbuttun"  type="submit" name="submit" value="N�ste">-->
<!--          <button>N�ste</button>-->
        
      </div>
    </div>
	<?php include_partial('steps_indicator', array('active_step'=>2)) ?>
  </div>
