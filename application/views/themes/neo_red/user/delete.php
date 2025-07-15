<?php $theme_view('includes/head'); ?>
<div class="mainBg profileMainBg">
    <?php $theme_view('includes/header-bar') ?>
    <div class="container pt-5">
        <div class="row">
            <div class="col-lg-6 col-12 text-center text-lg-left">
                <p><?php echo lang('confirm_removal') ?> - <strong><?php echo $upload['slug_id'] ?></strong></p>
            </div>
            <div class="col-lg-6 col-12 text-center text-lg-right">
                <a href="<?php anchor_to(USER_CONTROLLER . '/profile') ?>" class="btn btn-success border-0"><i class="fas fa-arrow-left mr-1"></i> <?php echo lang('back_profile') ?></a>
                <a href="<?php anchor_to(USER_CONTROLLER . '/delete/' . $upload['slug_id'] . '/true') ?>" class="btn btn-danger border-0 mr-1"><i class="fas fa-trash"></i> <?php echo lang('delete_image') ?></a>
            </div>
        </div>
        <div class="row mt-2">
            <img class="img-fluid" width="400" src="<?php echo $upload['s3'] ? $upload['imgname'] : base_url('i/' . $upload['imgname']) ?>">
        </div>
    </div>
    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>