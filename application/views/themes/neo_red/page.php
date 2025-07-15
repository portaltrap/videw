<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <h3 class="text-center p-5"><?php echo esc($page['title'], true) ?></h3>
        <div>
            <?php echo esc($page['content'], true); ?>
        </div>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>