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
				
				<div class="form-row">
				  <label class="required">Account Manager:</label>
				  <div class="content">
				  	<?php echo $company->getAccountManager()?$company->getAccountManager():'N/A' ?>
				  </div>
				</div>
				
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
				  <label class="required">Sim Card Dispatch date:</label>
				  <div class="content">
				  	<?php echo $company->getSimCardDispatchDate() ?>
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
				  	<?php echo $company->getUsageDiscountPc()*100 . '%' ?>
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
		<div id="sales-activity" style="display:none;">
			<h1>last sales activity</h1>
			<fieldset>
				<?php if($LatestSalesActivity = $company->getLatestSalesActivity()): ?>
				<div class="form-row">
				  <label class="required">Created at: </label>
				  <div class="content">
				  	<?php echo $LatestSalesActivity->getCreatedAt() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Ticket nr: </label>
				  <div class="content">
				  	<?php echo $LatestSalesActivity->getTicketNumber() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Sale action: </label>
				  <div class="content">
				  	<?php echo ''.$LatestSalesActivity->getSaleAction() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Comment: </label>
				  <div class="content">
				  	<?php echo $LatestSalesActivity->getComment() ?>
				  </div>
				</div>


				
				



				<div class="form-row">
				  <label class="required">Attached File: </label>
				  <div class="content">
				  	<?php if($LatestSalesActivity->getFilePath()): ?>
				  		<?php echo $LatestSalesActivity->getFilePath() ?>
				  	<?php else: ?>
				  		none
				  	<?php endif; ?>
				  </div>
				</div>
				<?php else: ?>
					<div class="form-row">
					  	<label>
					  		No Sale Activity
					  	</label>
					  	<div class="content">&nbsp;</div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
		<div id="support-activity" style="display:none;">
			<h1>last support activity</h1>
			<fieldset>
				<?php if($LatestSupportActivity = $company->getLatestSupportActivity()): ?>
				<div class="form-row">
				  <label class="required">Employee: </label>
				  <div class="content">
				  	<?php echo ''.$LatestSupportActivity->getEmployee() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Created at: </label>
				  <div class="content">
				  	<?php echo $LatestSupportActivity->getCreatedAt() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Ticket nr: </label>
				  <div class="content">
				  	<?php echo $LatestSupportActivity->getTicketNumber() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Support issue: </label>
				  <div class="content">
				  	<?php echo ''.$LatestSupportActivity->getSupportIssue() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Comment: </label>
				  <div class="content">
				  	<?php echo $LatestSupportActivity->getComment() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Solution: </label>
				  <div class="content">
				  	<?php echo $LatestSupportActivity->getSolution() ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Attached File: </label>
				  <div class="content">
				  	<?php if($LatestSupportActivity->getFilePath()): ?>
				  		<?php echo $LatestSupportActivity->getFilePath() ?>
				  	<?php else: ?>
				  		none
				  	<?php endif; ?>
				  </div>
				</div>
				<?php else: ?>
					<div class="form-row">
					  	<label>
					  		No Support Activity
					  	</label>
					  	<div class="content">&nbsp;</div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
</div>
<script type="text/javascript">
		function companyShow() {
		  var companyShow = document.getElementById("company-info");
		  var salesActivity = document.getElementById("sales-activity");
		  var supportActivity = document.getElementById("support-activity");

		  companyShow.style.display = "block";
		  salesActivity.style.display = "none";
		  supportActivity.style.display = "none";

		};

		function salesShow() {
		  var companyShow = document.getElementById("company-info");
		  var salesActivity = document.getElementById("sales-activity");
		  var supportActivity = document.getElementById("support-activity");

		  companyShow.style.display = "none";
		  salesActivity.style.display = "block";
		  supportActivity.style.display = "none";

		};

		function supportShow() {
		  var companyShow = document.getElementById("company-info");
		  var salesActivity = document.getElementById("sales-activity");
		  var supportActivity = document.getElementById("support-activity");

		  companyShow.style.display = "none";
		  salesActivity.style.display = "none";
		  supportActivity.style.display = "block";

		};
</script> 