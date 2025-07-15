<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <form method="POST" class="registerSection">
            <div class="registerIcon"><img class="img-responsivee" src="<?php $assets('images/registerIcon.svg') ?>" alt=""></div>
            <h1><?php echo lang('reset_password') ?></h1>
            <h2><?php echo lang('choose_password') ?></h2>

            <?php $theme_view('includes/alert'); ?>

            <div class="form-group text-left">
                <label for="password" class="inputsLabelLeft"><b><?php echo lang('new_password') ?></b></label>
                <input name="password" id="password" type="password" class="form-control registerFormInput" placeholder="New Password">
                <?php echo form_error('password', '<small class="text-danger">', '</small>') ?>
            </div>
            <div class="form-group text-left">
                <label for="passwordconf" class="inputsLabelLeft"><b><?php echo lang('new_password_confirm') ?></b></label>
                <input name="passwordconf" id="passwordconf" type="password" class="form-control registerFormInput" placeholder="Confirm New Password">
                <?php echo form_error('passwordconf', '<small class="text-danger">', '</small>') ?>
            </div>

            <input type="submit" name="submit" value="<?php echo lang('reset') ?>" class="btn registerBtn mt-0">
        </form>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>