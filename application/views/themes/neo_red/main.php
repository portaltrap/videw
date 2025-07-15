<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <div class="mainUploadSection <?php echo_if($ads['top']['status'], 'pt-0') ?>">
            <h1><?php echo lang('main_title') ?></h1>
            <h2><?php echo lang('title_sub') ?></h2>
            <div id="upload-process">
                <div id="uploader-block">
                    <div id="upload-clickable" class="uploaderSection">
                        <div class="uploadIcon"><img class="img-responsivee" src="<?php $assets('images/imageUpload.svg') ?>" alt=""></div>
                        <h3><?php echo lang('drop_here_upload') ?></h3>
                        <p><?php echo lang('file_support') ?>
                        <?php if(isset($upload) && isset($upload['max_size_kb']) && isset($upload['chunk_size'])){?>
                            <?php echo $upload['mime_types'] ?>
                        
                        <?php } ?>
                        <?php if(isset($upload_cfg) && isset($upload_cfg['max_size_kb']) && isset($upload_cfg['chunk_size'])){?>
                            <?php echo $upload_cfg['mime_types'] ?>
                        <?php } ?>
                        </p>
                        <p>
                            <span><?php echo $upload['max_size_kb'] ? 'Max Filesize. ' . ($upload['max_size_kb'] > 1024 ? number_format($upload['max_size_kb'] / 1024) . ' MB(s)' : number_format($upload['max_size_kb']) . ' KB(s)') : 'Unlimited Filesize' ?></span>
                        </p>
                    </div>
                </div>
                <div id="upload-previews"></div>
                <div>
                    <div id="droptemplate" class="uploaderBottom d-none">
                        <div class="spinnerCross">
                            <i data-dz-remove class="fas fa-times crossSp"></i>
                            <div class="spinner-grow text-danger d-none" role="status">
                                <span class="sr-only"><?php echo lang('loading') ?></span>
                            </div>
                        </div>
                        <img src="" class="uploaderImageIcon d-none">
                        <div class="previewLoader">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </div>
                        <div class="uploaderBottomFlex">
                            <div class="uploaderBototmName" data-dz-name></div>
                            <div class="uploaderProgress">
                                <a target="_blank" class="success-link d-none" href="#"></a>
                                <div class="progress upload-progress">
                                    <div data-dz-uploadprogress class="progress-bar bg-primary" role="progressbar" style="width: 0%"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>