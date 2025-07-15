<?php
$this->load->view("install/includes/head");
?>
	<div class="installer-container">
		<div class="installer-logo-area">
			<a class="logo-icon" href="<?php echo(VENDOR_URL); ?>" target="_blank"><img src="<?php echo($base_url . "public/img/xlscripts.png"); ?>"></a>
		</div>
		<!-- /installer-logo-area -->
		<div class="text-center">
		<div class="welcome-logo-text">
			Welcome To "<span><?php echo(PRODUCT_NAME); ?></span>" Installer
		</div>
		<!-- /welcome-logo-text -->
		</div>
		<!-- /text-center -->
		<?php if(!isset($license_response["response"]["type"])) { ?>
		<div class="text-center">
			<div class="alert alert-info">Enter Your License Details Below & Click Verify</div>
		</div>
		<?php } ?>
		<div class="tabs-area">
			<?php
			$this->load->view("install/includes/tabs");
			?>
			<!-- /tabs-nav -->
			
			<div class="tabs-content">
			<form method="post">
			<?php if(isset($license_response["response"]["type"])) { ?>
			<div class="alert alert-<?php echo($license_response["response"]["type"]=="error" ? "danger" : "success"); ?> text-center"><i class="fa fa-<?php echo($license_response["response"]["type"]=="error" ? "exclamation-triangle" : "check"); ?>"></i> <?php echo esc($license_response["response"]["msg"]); ?></div>
			<?php } else { ?>
			<div class="alert alert-success text-center">If you don't have license you can get one from <a href="<?php echo(API_URL."product_unique_id/".PRODUCT_ID) ?>" target="_blank"><span class="badge badge-primary"><?php echo(VENDOR_NAME); ?></span></a></div>
			<?php } ?>
					<label class="label-tabs">Purchase Code</label>
					<div class="text-fld-div">
						<input type="text" name="license_key" placeholder="Enter your Purchase Code" value="<?php echo esc(isset($license_response["response"]['license_key']) ? $license_response["response"]['license_key']:""); ?>" class="form-control text-fld" required>
						<?php echo form_error('license_key', '<div class="m-t-5 text-danger"><small>', '</small></div>'); ?>
					</div>
					<label><center><a target="_blank" href="https://bit.ly/2QCCRlD">NULLED scriptzzz!</a></center></label>
					<label class="label-tabs">Envato Token</label>
					<div class="text-fld-div">
						<input type="text" name="token" placeholder="Enter your Envao Token" value="<?php echo esc(isset($license_response["response"]['token']) ? $license_response["response"]['token']:""); ?>" class="form-control text-fld" required>
						<?php echo form_error('token', '<div class="m-t-5 text-danger"><small>', '</small></div>'); ?>
					</div>
					<div class="tab-button-area text-right">
					<?php if(isset($license_response["response"]["type"]) && $license_response["response"]["type"]=="success") { ?>
					<a class="btn btn-tabs" href="<?php echo($base_url."install/".$next_page); ?>">Next</a>
					<?php } else { ?>
					<input type="hidden" name="submit" value="submit">
					<button class="btn btn-tabs btn-submit" type="submit">Verify</button>
					<?php } ?>
					</div>
			</form>
				<!-- /tab-button-area -->
			</div>
			<!-- /tabs-content -->
		</div>
		<!-- /tabs-area -->
<?php
$this->load->view("install/includes/footer");
$this->load->view("install/includes/footer_end");
?>