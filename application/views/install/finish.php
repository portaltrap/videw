<?php
$this->load->view("install/includes/head");
?>
	<div class="installer-container">
		<div class="installer-logo-area">
			<a class="logo-icon" href="<?php echo(VENDOR_URL); ?>" target="_blank"><img src="<?php echo($base_url."public/img/xlscripts.png"); ?>"></a>
		</div>
		<!-- /installer-logo-area -->
		<div class="text-center">
		<div class="welcome-logo-text">
			Welcome To "<span><?php echo(PRODUCT_NAME); ?></span>" Installer
		</div>
		<!-- /welcome-logo-text -->
		</div>
		<!-- /text-center -->
		
		<div class="thankyou-area text-center">
			<div class="thankyou-title">All Set, Installation Done :)</div>			
			<!-- /thankyou-title -->
		</div>
		<!-- /thankyou-area -->
		
		<div class="tabs-area">
			<?php
			$this->load->view("install/includes/tabs");
			?>
			<!-- /tabs-nav -->
			
			<div class="tabs-content">
				<div class="installed clearfix">
					<div class="left-thumb"><i class="fas fa-thumbs-up"></i></div>
					<!-- /left-thumb -->
					<div class="right-thumb">Awesome ! Script Installed Successfully</div>
					<!-- /right-thumb -->
				</div>
				<!-- /installed -->
				
				<div class="thankyou-area text-left">
					<div class="thankyou-title">Please Remember :</div>			
					<!-- /thankyou-title -->
					<div class="thankyou-w-installation">Follow Instructions below for better experience.</div>
					<!-- /thankyou-w-installation -->
					<div class="thankyou-w-support">
						<ul>
							<li>Always check for updates on <a href="<?php echo(VENDOR_URL); ?>" target="_blank"><?php echo(VENDOR_NAME); ?></a>.</li>
							<li>To avoid possible bugs and issues always keep your product updated.</li>
							<li>If you encounter any problem please contact us via our <a href="<?php echo(VENDOR_URL); ?>/support" target="_blank">Support Portal</a>.</li>
							<li>Never Install Pirated or Nulled version of product it usually contains viruses & backdoors that can damage your server. Always use original product from <a href="<?php echo(VENDOR_URL); ?>" target="_blank"><?php echo(VENDOR_NAME); ?></a></li>
						</ul>
					</div>
					<!-- /thankyou-w-support -->
				</div>
				<!-- /thankyou-area -->
				
				<div class="tab-button-area text-center">
				<a class="btn btn-tabs" href="<?php echo($base_url); ?>">Visit Website</a>
				<a class="btn btn-tabs" href="<?php echo($base_url.ADMIN_CONTROLLER); ?>">Visit Admin</a>
				</div>
				<!-- /tab-button-area -->
				
			</div>
			<!-- /tabs-content -->
		</div>
		<!-- /tabs-area -->
<?php
$this->load->view("install/includes/footer");
$this->load->view("install/includes/footer_end");
?>