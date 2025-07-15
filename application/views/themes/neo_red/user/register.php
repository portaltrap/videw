<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <form method="POST" class="registerSection">
            <div class="registerIcon"><img class="img-responsivee" src="<?php $assets('images/registerIcon.svg') ?>" alt=""></div>
            <h1><?php echo lang('register_account') ?></h1>
            <h2><?php echo lang('keep_track') ?></h2>

            <?php $theme_view('includes/alert'); ?>

            <div class="form-group text-left">
                <label for="username" class="inputsLabelLeft"><b><?php echo lang('username') ?></b></label>
                <input value="<?php echo set_value("username"); ?>" name="username" id="username" type="text" class="form-control registerFormInput" placeholder="Your Username">
                <?php echo form_error('username', '<small class="text-danger">', '</small>') ?>
            </div>
            <div class="form-group text-left">
                <label for="email" class="inputsLabelLeft"><b><?php echo lang('email') ?></b></label>
                <input value="<?php echo set_value("email"); ?>" name="email" id="email" type="email" class="form-control registerFormInput" placeholder="E-Mail Address">
                <?php echo form_error('email', '<small class="text-danger">', '</small>') ?>
            </div>
            <div class="form-group text-left">
                <label for="password" class="inputsLabelLeft"><b><?php echo lang('password') ?></b></label>
                <input name="password" id="password" type="password" class="form-control registerFormInput" placeholder="Enter your Password">
                <?php echo form_error('password', '<small class="text-danger">', '</small>') ?>
            </div>
            <input type="submit" name="submit" value="<?php echo lang('register') ?>" class="btn registerBtn mt-0">

            <div class="loginWdSocial">
                <a href="<?php anchor_to(OAUTH_CONTROLLER . '/facebook') ?>" class="btn facebook <?php echo_if(!$social_keys['facebook_status'], 'disabled') ?>"><i class="fab fa-facebook-f"></i> Login with facebook</a>
                <a href="<?php anchor_to(OAUTH_CONTROLLER . '/google') ?>" class="btn gPlus <?php echo_if(!$social_keys['google_status'], 'disabled') ?>"><i class="fab fa-google-plus-g"></i> Login with google</a>
            </div>

            <div class="alreadyAccount"><?php echo lang('already_registered') ?> <a href="<?php anchor_to(USER_CONTROLLER . '/login') ?>"><?php echo lang('login_here') ?></a></div>
        </form>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>