<div id="sf_admin_container">
	<div id="sf_admin_content">
	<!-- employee/list?filters[company_id]=1 -->
	<a href="<?php echo url_for('employee/index').'?company_id='.$company->getId()."&filter=filter" ?>" class="external_link" target="_self">Employees (<?php echo count($company->getEmployees()) ?>)</a>
	<a href="<?php echo url_for('company/usage').'?company_id='.$company->getId(); ?>" class="external_link" target="_self">Usage</a>
	<!--
	<a onclick="companyShow();" style="cursor:pointer;">Company Info</a>
	&nbsp; | &nbsp;
	<a onclick="salesShow();" style="cursor:pointer;">Sales Activity</a>
	&nbsp; | &nbsp;
	<a onclick="supportShow();" style="cursor:pointer;">Support Activity</a>
	 -->
		<div id="company-info">
		    <h1>company details</h1>
			<fieldset>
				<div class="form-row">
				  <label class="required">Company Name:</label>
				  <div class="content">
				  	<?php echo $company->getName() ?> &nbsp; <?php echo link_to('edit info', 'company/edit?id='.$company->getId()) ?>
				  </div>
				</div>

	<div class="form-row">
				  <label class="required">Balance view: </label>
				  <div class="content"><?php
                                 $uniqueId=$company->getVatNo();
                                 $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name='.$uniqueId.'&type=customer');
        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);
        echo  $telintaGetBalance;
          echo "Sek";
                           ?>
				   
				  </div>
				</div>
				<div class="form-row">
				  <label class="required">Vat Number:</label>
				  <div class="content">
				  	<?php echo $company->getVatNo()?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Address:</label>
				  <div class="content">
				  	<?php echo $company->getAddress() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Post Code:</label>
				  <div class="content">
				  	<?php echo $company->getPostCode() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Country:</label>
				  <div class="content">
				  	<?php echo $company->getCountry()?$company->getCountry()->getName():'N/A' ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">City:</label>
				  <div class="content">
				  	<?php echo $company->getCity()?$company->getCity()->getName():'N/A' ?>
				  </div>
				</div>


				<div class="form-row">
				  <label class="required">Contact Name:</label>
				  <div class="content">
				  <?php echo $company->getContactName()?>
				  </div>
				</div>

                                <div class="form-row">
				  <label class="required">Contact e-mail:</label>
				  <div class="content">
				  <?php echo $company->getEmail()?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Head Phone Nr:</label>
				  <div class="content">
				  	<?php echo $company->getHeadPhoneNumber() ?>
				  </div>
				</div>


				<div class="form-row">
				  <label class="required">Fax Number:</label>
				  <div class="content">
				  	<?php echo $company->getFaxNumber() ?>
				  </div>
				</div>
				
				<div class="form-row">
				  <label class="required">Webstie:</label>
				  <div class="content">
				  	<?php echo $company->getWebsite() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Company Size</label>
				  <div class="content">
				  	<?php echo $company->getCompanySize()?$company->getCompanySize():'N/A' ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Company Type</label>
				  <div class="content">
				  	<?php echo $company->getCompanyType()?$company->getCompanyType():'N/A' ?>
				  </div>
				</div>
							
				<div class="form-row">
				  <label class="required">Invoice Method:</label>
				  <div class="content">
				  	<?php echo $company->getInvoiceMethod()?$company->getInvoiceMethod():'N/A' ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Customer Type:</label>
				  <div class="content">
				  	<?php echo $company->getCustomerType()?$company->getCustomerType():'N/A' ?>
				  </div>
				</div>
				
<!--				<div class="form-row">
				  <label class="required">Account Manager:</label>
				  <div class="content">
				  	<?php //echo $company->getAccountManager()?$company->getAccountManager():'N/A' ?>
				  </div>
				</div>-->
				
				<div class="form-row">
				  <label class="required">Agent Company:</label>
				  <div class="content">
				  	<?php echo $company->getAgentCompany()?$company->getAgentCompany():'N/A' ?>
				  </div>
				</div>
				
				<div class="form-row">
				  <label class="required">Status:</label>
				  <div class="content">
				  	<?php echo ''.$company->getStatus() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Registered at:</label>
				  <div class="content">
				  	<?php echo $company->getRegistrationDate() ?>
				  </div>
				</div>

			 		
				
				
				
				<div class="form-row">
				  <label class="required">Package:</label>
				  <div class="content">
				  	<?php echo $company->getPackage()?$company->getPackage():'N/A' ?>
				  </div>
				</div>
				
				<div class="form-row">
				  <label class="required">Usage Discount %:</label>
				  <div class="content">
				  	<?php echo $company->getUsageDiscountPc(). '%' ?>
				  </div>
				</div>		

                            
				<div class="form-row">
				  <label class="required">Registration Doc:</label>
				  <div class="content">
					<?php if($company->getFilePath()): ?>
						<a href="<?php echo public_path('/uploads/'.$company->getFilePath()) ?>" target="_blank">Download attachement</a>
					<?php else: ?>
						none
					<?php endif; ?>
				  </div>
				</div>
			</fieldset>
		</div>
	
	</div>
</div>