<center>
<h2>News & Updates Listing</h2>
</center>
<br/>

					
				
					
					
					

					<?php
					$currentDate = date('Y-m-d');
					foreach($news as $single_news)
					{
							  
							  
							   if($currentDate>=$single_news->getStartingDate())
							   {?>
							    
								  <p>
								  <font size="3">	
								  <b><?php echo $single_news->getStartingDate() ?></b><br/>
								  <?php echo $single_news->getHeading();?> :  <?php echo $single_news->getMessage();?>	
								  <br/><br/>
								  </font>
								  </p>
								
					<?php        }
					

					} ?>
				
					
					