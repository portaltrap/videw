<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <form method="POST" class="registerSection">
            <div class="registerIcon"><img class="img-responsivee" src="<?php $assets('images/loginIcon.svg') ?>" alt=""></div>
            <h1><?php echo lang('login_to_account') ?></h1>
            <h2><?php echo lang('keep_track') ?></h2>

            <?php $theme_view('includes/alert'); ?>

            <div class="form-group text-left">
                <label for="identifier" class="inputsLabelLeft">
                    <b><?php echo lang('identifier') ?></b>
                </label>
                <input value="<?php echo set_value("identifier"); ?>" name="identifier" id="identifier" type="text" class="form-control registerFormInput" placeholder="E-Mail / Username">
                <?php echo form_error('identifier', '<small class="text-danger">', '</small>') ?>
            </div>
            <div class="form-group text-left">
                <label for="password" class="inputsLabelLeft">
                    <b><?php echo lang('password') ?></b>
                    <span class="alreadyAccount m-b-0 float-right"><a href="<?php anchor_to(USER_CONTROLLER . '/reset') ?>"><small><?php echo lang('forgot_password') ?></small></a></span>
                </label>
                <input name="password" id="password" type="password" class="form-control registerFormInput" placeholder="Your Password">
                <?php echo form_error('password', '<small class="text-danger">', '</small>') ?>
            </div>
            <input type="submit" name="submit" value="<?php echo lang('login') ?>" class="btn registerBtn mt-0">
            
            <div class="loginWdSocial">
                <a href="<?php anchor_to(OAUTH_CONTROLLER . '/facebook') ?>" class="btn facebook <?php echo_if(!$social_keys['facebook_status'], 'disabled') ?>"><i class="fab fa-facebook-f"></i> Login with facebook</a>
                <a href="<?php anchor_to(OAUTH_CONTROLLER . '/google') ?>" class="btn gPlus <?php echo_if(!$social_keys['google_status'], 'disabled') ?>"><i class="fab fa-google-plus-g"></i> Login with google</a>
            </div>

            <div class="alreadyAccount"><?php echo lang('not_registered_yet') ?> <a href="<?php anchor_to(USER_CONTROLLER . '/register') ?>"><?php echo lang('click_here') ?></a></div>

        </form>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>