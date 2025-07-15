<?php $theme_view('includes/head'); ?><!-- Docs styles -->
<link rel="stylesheet" href="<?php $assets('player/demo.css') ?>">
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>
    <div class="singleImageMain">
			<div class="container">
				<?php if($upload_cfg['enable_popup']) { ?>
				<div class="text-center" id="img-popup">
					<p class="lead">Click <strong>"Show Video"</strong> to view your video after the timer expires.</p>
					<?php if($upload_cfg['seconds_to_wait'] > 0) { ?>
						<button id="show-img" disabled class="btn btn-danger btn-lg border-0 mt-3 d-none"><i class="fas fa-eye mr-1"></i> Show Video</button>
						<div id="img-wait" class="mt-3"><span>You must wait <strong><span id="timer"><?php echo esc($upload_cfg['seconds_to_wait']) ?></span></strong> seconds before proceeding.</span></div>
					<?php } else { ?>
						<button id="show-img" class="btn btn-danger btn-lg border-0 mt-3"><i class="fas fa-eye mr-1"></i> Show Video</button>
					<?php } ?>
				</div>
				<div class="d-none" id="hidden-img">
				<?php } ?>
				<div class="row">
					<div class="col-lg-9">
						<div class="imageViewer">
							<video id="player" controls></video>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="exploreTabsArea clearfix">
							<?php if($upload['user'] && $upload['user']['privacy']) { ?>
								<h4><?php echo lang('about_user') ?></h4>
								<div class="imgUsername">
									<span class="singleImgUsername">
										<span class="singleImgUsericon"><img class="img-fluid rounded-circle" src="<?php uploads('user/' . $upload['user']['avatar']) ?>"></span>
										<span class="singleImgUsericonText"><?php echo esc($upload['user']['username']) ?></span>
									</span>									
									<div class="singleVia">
										<?php echo date_to_ago('@' . strtotime($upload['upload_date'])) ?>
									</div>
								</div>
							<?php } if($can_user_delete && $user && $user['id'] == $upload['user']['id']) { ?>
								<h4>User Controls</h4>
								<div class="embedLinks">
									<a target="_blank" href="<?php anchor_to(USER_CONTROLLER . '/delete/' . $upload['slug_id']) ?>" class="btn btn-outline-danger btn-block"><i class="fas fa-trash mr-1"></i> Delete This Video</a>
								</div>
								<small class="text-muted mt-2">Only visible to you.</small>
							<?php } ?>
							<h4><?php echo lang('share') ?></h4>
							<div class="singleImgShareArea">
								<div class="singleImageSocial">
									<button id="fb-share" class="singleImgShareIcon iconFb border-0">
										<i class="fab fa-facebook-f"></i>
									</button>
									<button id="tw-share" class="singleImgShareIcon iconTwt border-0">
										<i class="fab fa-twitter"></i>
									</button>
									<button id="ld-share" class="singleImgShareIcon btn-linkedin border-0">
										<i class="fab fa-linkedin-in"></i>
									</button>
									<button id="vk-share" class="singleImgShareIcon btn-vk border-0">
										<i class="fab fa-vk"></i>
									</button>
									<button id="rd-share" class="singleImgShareIcon iconRdt border-0">
										<i class="fab fa-reddit-alien"></i>
									</button>
									<button id="wp-share" class="singleImgShareIcon btn-success border-0">
										<i class="fab fa-whatsapp"></i>
									</button>
								</div>
								<h4><?php echo lang('copy_links') ?></h4>
								<div class="embedLinks">
									<label class="mt-2">Share Link</label>											
									<div class="copyURLMain">
										<div id="direct-lx"><?php anchor_to('v/' . $upload['slug_id']) ?></div>
										<button data-copy="<?php anchor_to('v/' . $upload['slug_id']) ?>" id="direct-copy" class="btn btnCopy" type="button">Copy</button>
									</div>

									<label class="mt-2">BBCode</label>						
									<div class="copyURLMain">
										<div id="bbcode-lx">[url=<?php anchor_to('v/' . $upload['slug_id']) ?>][img]<?php echo $upload['s3'] ? $upload['filename'] : base_url('v/' . $upload['filename']) ?>[/img][/url]</div>
										<button data-copy="[url=<?php anchor_to('v/' . $upload['slug_id']) ?>][img]<?php echo $upload['s3'] ? $upload['filename'] : base_url('v/' . $upload['filename']) ?>[/img][/url]" id="bbcode-copy" class="btn btnCopy" type="button">Copy</button>
									</div>

									<label class="mt-2">HTML Embed</label>											
									<button data-copy="<a href='<?php anchor_to('v/' . $upload['slug_id']) ?>'><img src='<?php echo $upload['s3'] ? $upload['filename'] : base_url('v/' . $upload['filename']) ?>'></a>" id="html-copy" class="btn btn-dark border-0"><i class="fas fa-code mr-1"></i> Copy HTML</button>
								</div>
								<?php if($admin) { ?>
								<h4>Admin Tools</h4>
								<div class="embedLinks">
									<a target="_blank" href="<?php anchor_to(VIDUP_CONTROLLER . '/delete_upload/' . $upload['id']) ?>" class="btn btn-outline-danger btn-block"><i class="fas fa-trash mr-1"></i> Delete This Video</a>
									<h4 class="mt-2">Upload Info</h4>
									<label>Publish Date</label>
									<div class="copyURLMain">
										<div id=""><?php echo $upload['upload_date'] ?></div>
									</div>
									<label class="mt-2">Upload Location</label>
									<div class="copyURLMain">
										<div id=""><?php echo $upload['s3'] ? 'Amazon S3' : 'Local' ?></div>
									</div>
								</div>
								<small class="text-muted mt-2">Only visible to you.</small>
								<?php } ?>
							</div>
							<!-- /single-img-share-area -->
						
						</div>
					</div>
				</div>
				<?php if($upload_cfg['enable_popup']) { ?>
				</div>
				<?php } ?>
			</div>
        </div><!-- /singleImageMain -->

    <?php $theme_view('includes/footer-bar') ?>
</div>
<!-- Plyr core script -->
<script src="<?php $assets('player/plyr.js') ?>" crossorigin="anonymous"></script>
	
<script type="text/javascript">
	const player = new Plyr('#player');
	player.source = {
		debug: true,
		type: 'video',
		title: 'View From A Blue Moon',
		keyboard: {
			global: true
		},
		tooltips: {
			controls: true
		},
		captions: {
			active: true
		},
		sources: [{
			src: '<?php echo ($upload['s3']) ? esc($upload['filename']) : base_url('v/' . esc($upload['filename'])); ?>',
			type: 'video/mp4'
		}]
	};
</script>
<?php $theme_view('includes/foot'); ?>