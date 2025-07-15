<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container text-center">
        <h1 class="display-1">404</h1>
        <p>Page Not Found</p>
        <p>
            <a class="btn btn-danger btn-lg border-0" href="<?php anchor_to() ?>"><i class="fas fa-home mr-1"></i> Home</a>
        </p>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>