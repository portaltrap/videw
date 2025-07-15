<footer>
    <?php $theme_view('includes/bottom-ad') ?>

    <div class="container">
        <div class="footerLogo">
            <a href="<?php anchor_to() ?>"><img class="img-responsivee" src="<?php $assets('images/footerLogo.svg') ?>" alt=""></a>
        </div>
        <div class="footerCopyright">
            Powerd by <a target="_blank" href="<?php echo VENDOR_URL ?>"><?php echo PRODUCT_NAME; ?></a> - <?php echo lang('attribution'); ?>
        </div>
        <div class="footerNav">
            <?php foreach($pages as $page) { if($page['position'] >= 2) { ?>
                <a href="<?php anchor_to('page/' . $page['permalink']) ?>"><?php echo esc($page['title'], true) ?></a> -
            <?php } } ?>
            <a href="<?php anchor_to('contact') ?>">Contact Us</a>
        </div>
    </div>
</footer>