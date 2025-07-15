<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <form method="POST" class="registerSection">
            <div class="registerIcon"><img class="img-responsivee" src="<?php $assets('images/registerIcon.svg') ?>" alt=""></div>
            <h1><?php echo lang('reset_password') ?></h1>
            <h2><?php echo lang('get_started') ?></h2>

            <?php $theme_view('includes/alert'); ?>

            <div class="form-group text-left">
                <label for="identifier" class="inputsLabelLeft"><b><?php echo lang('email') ?></b></label>
                <input value="<?php echo set_value("email"); ?>" name="email" id="email" type="email" class="form-control registerFormInput" placeholder="Your E-Mail Address">
                <?php echo form_error('email', '<small class="text-danger">', '</small>') ?>
            </div>
            <?php if($recaptcha['status']) { ?>
            <div class="form-group text-right">
                <div class="g-recaptcha" data-sitekey="<?php echo esc($recaptcha['site_key']) ?>"></div>
            </div>
            <?php } ?>
            <input type="submit" name="submit" value="<?php echo lang('send_code') ?>" class="btn registerBtn mt-0">
        </form>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>