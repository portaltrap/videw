<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>

    <div class="container">
        <form method="POST" class="registerSection">
            <div class="registerIcon"><img class="img-responsivee" src="<?php $assets('images/registerIcon.svg') ?>" alt=""></div>
            <h1><?php echo lang('contact_us') ?></h1>
            <h2><?php echo lang('get_in_touch') ?></h2>

            <?php $theme_view('includes/alert'); ?>

            <div class="form-group text-left">
                <label for="email" class="inputsLabelLeft"><b><?php echo lang('email') ?></b></label>
                <input name="email" id="email" type="email" class="form-control registerFormInput" placeholder="someone@domain.com">
                <?php echo form_error('email', '<small class="text-danger">', '</small>') ?>
            </div>
            <div class="form-group text-left">
                <label for="name" class="inputsLabelLeft"><b><?php echo lang('name') ?></b></label>
                <input name="name" id="name" type="text" class="form-control registerFormInput" placeholder="John Doe">
                <?php echo form_error('name', '<small class="text-danger">', '</small>') ?>
            </div>
            <div class="form-group text-left">
                <label for="message" class="inputsLabelLeft"><b><?php echo lang('message') ?></b></label>
                <textarea name="message" id="message" class="form-control formControlTextarea" placeholder="Your message..." rows="4"></textarea>
                <?php echo form_error('message', '<small class="text-danger">', '</small>') ?>
            </div>
            <?php if($recaptcha['status']) { ?>
            <div class="form-group text-right">
                <div class="g-recaptcha" data-sitekey="<?php echo esc($recaptcha['site_key']) ?>"></div>
            </div>
            <?php } ?>
            <input type="submit" name="submit" value="<?php echo lang('send_msg') ?>" class="btn registerBtn mt-0">
        </form>
    </div>

    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>