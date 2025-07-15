<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
    <?php echo esc($meta_tags, true); ?>
    <meta name="title" content="<?php echo esc($general['title']) ?>">
    <meta name="description" content="<?php echo esc($general['description']) ?>">
    <meta name="keywords" content="<?php echo esc($general['keywords']) ?>">
    <link rel="icon" href="<?php uploads('img/' . $general['favicon']) ?>">
    <title><?php echo (isset($title) ? esc($title) . ' - ' : '') . esc($general['title']) ?></title>
    <link rel="stylesheet" href="<?php public_assets('css/fonts.min.css') ?>">
    <link rel="stylesheet" href="<?php ($lang_mode == 'rtl') ? public_assets('css/bootstrap.rtl.min.css') : public_assets('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?php public_assets('css/dropzone.min.css') ?>">
    <link rel="stylesheet" href="<?php $assets('css/style.css') ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap">
    <?php if(isset($og_image)) { ?>
        <meta property="og:image" content="<?php echo esc($og_image) ?>">
        <meta property="twitter:image" content="<?php echo esc($og_image) ?>">
    <?php } 
    if(isset($load_css)) { foreach($load_css as $href) { ?>
        <link rel="stylesheet" href="<?php echo $href ?>">
    <?php } } ?>
	<script>"use strict";
		const base_url                  = "<?php anchor_to() ?>";
		const page                      = "<?php echo PAGE_CONTROLLER; ?>";
        const upload                    = "<?php echo UPLOAD_CONTROLLER; ?>";
        const lang_file_not_allowed     = "<?php echo lang('file_not_allowed') ?>";
        <?php if(isset($upload) && isset($upload['max_size_kb']) && isset($upload['chunk_size'])){?>
        const fileSize                  = "<?php echo number_format($upload['max_size_kb'] / 1024) ?>";
        const chunkSize                 = <?php echo $upload['chunk_size'] * 1024 ?>;
        const mime_types                = "<?php echo $upload['mime_types'] ?>";
        const mime_typ                  = "<?php echo $upload['mime_typ'] ?>";
        
        <?php } ?>
        <?php if(isset($upload_cfg) && isset($upload_cfg['max_size_kb']) && isset($upload_cfg['chunk_size'])){?>

        const fileSize                  = "<?php echo number_format($upload_cfg['max_size_kb'] / 1024) ?>";
        const chunkSize                 = "<?php echo $upload_cfg['chunk_size'] * 1024 ?>";
        const mime_types                = "<?php echo $upload_cfg['mime_types'] ?>";
        const mime_typ                  = "<?php echo $upload_cfg['mime_typ'] ?>";
        <?php } ?>
	</script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php echo esc($scripts['header'], true);
    echo esc($analytics, true); ?>
</head>
<body>